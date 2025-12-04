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

    // --- LOGIN FEATURE ---

    // Show Login Form
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }
        $this->loadView('auth/login');
    }

    // Handle Login POST request
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                $this->redirectBasedOnRole();
            } else {
                $data = ['error' => 'Email hoặc mật khẩu không chính xác!'];
                $this->loadView('auth/login', $data);
            }
        }
    }
  // --- PROFILE (THE MISSING METHOD) ---
    public function profile() {
        // 1. Check Login
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        // 2. Get User Info from DB
        $user = $this->userModel->getById($_SESSION['user_id']);

        // 3. Safety check: If user deleted from DB but session exists
        if (!$user) {
            $this->logout(); 
            return;
        }

        $data = [
            'user' => $user,
            'page_title' => 'Hồ sơ cá nhân'
        ];
        $this->loadView('auth/profile', $data);
    }

    // --- REGISTER FEATURE ---

    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=home');
        }
        $this->loadView('auth/register');
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(strip_tags($_POST['name']));
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

            if (empty($errors)) {
                if ($this->userModel->create($name, $email, $password)) {
                    $this->loadView('auth/login', ['success' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
                    return;
                } else {
                    $errors[] = "Lỗi hệ thống. Vui lòng thử lại sau.";
                }
            }

            $data = [
                'error' => implode('<br>', $errors),
                'old' => ['name' => $name, 'email' => $email]
            ];
            $this->loadView('auth/register', $data);
        }
    }

    // --- LOGOUT & HELPERS ---

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('index.php?page=login');
    }

    // Helper: Redirect based on user role
    private function redirectBasedOnRole() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            // UPDATED: Redirect Admin to the new Dashboard
            $this->redirect('index.php?page=admin_dashboard');
        } else {
            // Redirect User to Home
            $this->redirect('index.php?page=home');
        }
    }
}
?>
