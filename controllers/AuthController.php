<?php
// controllers/AuthController.php

require_once 'BaseController.php';
require_once ROOT_PATH . '/models/UserModel.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->userModel = new UserModel($db);
    }

    /* ========================
     *        LOGIN
     * ======================== */

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }
        $this->loadView('auth/login');
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=login');
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            if (($user['status'] ?? 'active') === 'disabled') {
                $this->loadView('auth/login', ['error' => 'Tài khoản của bạn đã bị vô hiệu hóa.']);
                return;
            }

            // SESSION SETUP
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_avatar'] = !empty($user['avatar'])
                ? 'uploads/avatars/' . $user['avatar']
                : null;

            // REMEMBER ME
            if ($remember && method_exists($this->userModel, 'updateRememberToken')) {
                $token = bin2hex(random_bytes(32));
                $this->userModel->updateRememberToken($user['id'], $token);
                setcookie('remember_me', $token, time() + 86400 * 30, "/", "", false, true);
            }

            $this->redirectBasedOnRole();
        }

        // FAILED LOGIN
        $this->loadView('auth/login', [
            'error' => 'Email hoặc mật khẩu không chính xác!'
        ]);
    }

    /* ========================
     *        PROFILE
     * ======================== */

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        $user = $this->userModel->getById($_SESSION['user_id']);

        if (!$user) {
            $this->logout();
            return;
        }

        $this->loadView('auth/profile', [
            'user' => $user,
            'page_title' => 'Hồ sơ cá nhân'
        ]);
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=login');
        }

        $name = htmlspecialchars(trim($_POST['name']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $address = htmlspecialchars(trim($_POST['address']));

        if ($this->userModel->updateProfile($_SESSION['user_id'], $name, $phone, $address)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['success'] = "Cập nhật thông tin thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật.";
        }

        $this->redirect('index.php?page=profile');
    }

    /* ========================
     *    CHANGE PASSWORD
     * ======================== */

    public function changePassword() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=login');
        }

        $oldPass = $_POST['old_password'];
        $newPass = $_POST['new_password'];
        $confirmPass = $_POST['confirm_password'];

        $currentHash = $this->userModel->getPasswordHash($_SESSION['user_id']);

        if (!password_verify($oldPass, $currentHash)) {
            $_SESSION['error'] = "Mật khẩu cũ không chính xác.";
        } elseif ($newPass !== $confirmPass) {
            $_SESSION['error'] = "Mật khẩu mới không khớp.";
        } elseif (strlen($newPass) < 6) {
            $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
        } else {
            $newHash = password_hash($newPass, PASSWORD_BCRYPT);

            if ($this->userModel->updatePassword($_SESSION['user_id'], $newHash)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống.";
            }
        }

        $this->redirect('index.php?page=profile');
    }

    /* ========================
     *      UPLOAD AVATAR
     * ======================== */

    public function uploadAvatar() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=login');
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Không có file tải lên.";
            $this->redirect('index.php?page=profile');
        }

        $uploadDir = 'uploads/avatars/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = "Định dạng ảnh không hỗ trợ.";
            $this->redirect('index.php?page=profile');
        }

        $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $this->userModel->updateAvatar($_SESSION['user_id'], $filename);
            $_SESSION['user_avatar'] = $targetPath;
            $_SESSION['success'] = "Cập nhật ảnh đại diện thành công!";
        } else {
            $_SESSION['error'] = "Không thể tải ảnh lên.";
        }

        $this->redirect('index.php?page=profile');
    }

    /* ========================
     *       REGISTER
     * ======================== */

    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=home');
        }
        $this->loadView('auth/register');
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=register');
        }

        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        $errors = [];

        if (empty($name) || empty($email) || empty($password)) {
            $errors[] = "Vui lòng điền đầy đủ thông tin.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Mật khẩu xác nhận không khớp.";
        }

        if ($this->userModel->findByEmail($email)) {
            $errors[] = "Email này đã được đăng ký.";
        }

        if (!empty($errors)) {
            $this->loadView('auth/register', [
                'error' => implode('<br>', $errors),
                'old' => ['name' => $name, 'email' => $email]
            ]);
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ($this->userModel->create($name, $email, $hash)) {
            $this->loadView('auth/login', ['success' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
        } else {
            $this->loadView('auth/register', [
                'error' => 'Lỗi hệ thống. Vui lòng thử lại sau.',
                'old' => ['name' => $name, 'email' => $email]
            ]);
        }
    }

    /* ========================
     *        LOGOUT
     * ======================== */

    public function logout() {
        if (isset($_SESSION['user_id']) && method_exists($this->userModel, 'clearRememberToken')) {
            $this->userModel->clearRememberToken($_SESSION['user_id']);
        }

        session_unset();
        session_destroy();

        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        $this->redirect('index.php?page=login');
    }

    /* ========================
     *      HELPER
     * ======================== */

    private function redirectBasedOnRole() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $this->redirect('index.php?page=admin_dashboard');
        }

        $this->redirect('index.php?page=home');
    }
}
?>

