<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/UserModel.php';

class AdminUserController extends BaseController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->userModel = new UserModel($db);
    }

    /**
     * List all users
     * Route: index.php?page=admin_user_list
     */
    public function index() {
        $this->requireAdmin();

        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAll($limit, $offset);
        $totalUsers = $this->userModel->countAll();
        $totalPages = ceil($totalUsers / $limit);

        $this->loadAdminView('admin/user/index', [
            'page_title' => 'Quản lý tài khoản',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * Toggle User Status (AJAX)
     * Route: index.php?page=admin_user_status
     */
    public function updateStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $status = $_POST['status']; // 'active' or 'disabled'

            // Prevent disabling yourself
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Không thể vô hiệu hóa chính mình']);
                return;
            }

            if ($this->userModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật']);
            }
        }
    }

    /**
     * Reset Password (AJAX)
     * Route: index.php?page=admin_user_reset_password
     */
    public function resetPassword() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $newPass = "123456"; // Default reset password
            $hashed = password_hash($newPass, PASSWORD_BCRYPT);

            if ($this->userModel->updatePassword($id, $hashed)) {
                echo json_encode(['success' => true, 'message' => 'Mật khẩu đã reset về: 123456']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi reset mật khẩu']);
            }
        }
    }

    private function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?page=home');
        }
    }

    protected function loadAdminView($view, $data = []) {
        extract($data);
        require_once ROOT_PATH . '/views/layouts/admin_layout.php';
    }
}
?>
