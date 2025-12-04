<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/FaqModel.php';

class AdminFaqController extends BaseController {
    private $faqModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->faqModel = new FaqModel($db);
    }

    public function index() {
        $this->requireAdmin();
        $faqs = $this->faqModel->getAll();
        $this->loadAdminView('admin/faq/index', [
            'page_title' => 'Quản lý FAQ',
            'faqs' => $faqs
        ]);
    }

    public function create() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->faqModel->create($_POST['question'], $_POST['answer']);
            $this->redirect('index.php?page=admin_faq_list');
            return;
        }
        $this->loadAdminView('admin/faq/create', ['page_title' => 'Thêm câu hỏi']);
    }

    public function edit() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->faqModel->update($id, $_POST['question'], $_POST['answer']);
            $this->redirect('index.php?page=admin_faq_list');
            return;
        }

        $faq = $this->faqModel->getById($id);
        $this->loadAdminView('admin/faq/edit', ['page_title' => 'Sửa câu hỏi', 'faq' => $faq]);
    }

    public function delete() {
        $this->requireAdmin();
        $id = $_POST['id'] ?? 0;
        if ($this->faqModel->delete($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
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
