<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/ContactModel.php';

class AdminContactController extends BaseController {
    private $contactModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->contactModel = new ContactModel($db);
    }

    public function index() {
        $this->requireAdmin();

        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $contacts = $this->contactModel->getAll($limit, $offset);
        $totalContacts = $this->contactModel->countAll();
        $totalPages = ceil($totalContacts / $limit);

        $data = [
            'page_title' => 'Quản lý Liên hệ',
            'contacts' => $contacts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalContacts' => $totalContacts
        ];

        $this->loadAdminView('admin/contact/index', $data);
    }

    public function update_status() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $status = $_POST['status']; // 'new', 'read', 'replied'
            
            if ($this->contactModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function delete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            if ($this->contactModel->delete($id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
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
