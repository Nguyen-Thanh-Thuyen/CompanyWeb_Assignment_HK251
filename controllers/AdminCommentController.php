<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/CommentModel.php';

class AdminCommentController extends BaseController {
    private $commentModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->commentModel = new CommentModel($db);
    }

    public function index() {
        $this->requireAdmin();

        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 20; // 20 comments per page
        $offset = ($page - 1) * $limit;

        $comments = $this->commentModel->getAll($limit, $offset);
        $total = $this->commentModel->countAll();
        $totalPages = ceil($total / $limit);

        $this->loadAdminView('admin/comment/index', [
            'page_title' => 'Quản lý bình luận',
            'comments' => $comments,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function delete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            if ($this->commentModel->delete($id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi xóa bình luận']);
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
