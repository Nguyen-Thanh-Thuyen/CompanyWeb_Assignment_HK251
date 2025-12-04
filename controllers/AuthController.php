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
        // If already logged in, redirect
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

            // 1. Find user by email
            $user = $this->userModel->findByEmail($email);

            // 2. Verify Password
            if ($user && password_verify($password, $user['password'])) {
                // 3. Set Session Variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // 4. Redirect based on Role
                $this->redirectBasedOnRole();
            } else {
                // Login Failed
                $data = ['error' => 'Email hoặc mật khẩu không chính xác!'];
                $this->loadView('auth/login', $data);
            }
        }
    }

    // --- REGISTER FEATURE (NEW) ---

    // Show Register Form
    public function register() {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=home');
        }
        $this->loadView('auth/register');
    }

    // Handle Register POST request
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Sanitize & Get Input
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            $errors = [];

            // 2. Validation
            if (empty($name) || empty($email) || empty($password)) {
                $errors[] = "Vui lòng điền đầy đủ thông tin.";
            }

            if ($password !== $confirmPassword) {
                $errors[] = "Mật khẩu xác nhận không khớp.";
            }

            // Check duplicate email
            if ($this->userModel->findByEmail($email)) {
                $errors[] = "Email này đã được đăng ký.";
            }

            // 3. Process Registration
            if (empty($errors)) {
                // Create user via Model (hashing happens inside Model)
                if ($this->userModel->create($name, $email, $password)) {
                    // Success: Load login page with success message
                    $this->loadView('auth/login', ['success' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
                    return;
                } else {
                    $errors[] = "Lỗi hệ thống. Vui lòng thử lại sau.";
                }
            }

            // 4. Failed: Reload Register form with errors and old input
            $data = [
                'error' => implode('<br>', $errors),
                'old' => ['name' => $name, 'email' => $email]
            ];
            $this->loadView('auth/register', $data);
        }
    }

    // --- LOGOUT & HELPERS ---

    // Logout
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('index.php?page=login');
    }

    // Helper: Redirect based on user role
    private function redirectBasedOnRole() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            // Redirect Admin to Dashboard or Product List
            $this->redirect('index.php?page=admin_product_list');
        } else {
            // Redirect User to Home
            $this->redirect('index.php?page=home');
        }
    }
}
?>
