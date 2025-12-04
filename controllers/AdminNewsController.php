<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/NewsModel.php';

class AdminNewsController extends BaseController {
    private $newsModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->newsModel = new NewsModel($db);
    }

    public function index() {
        $this->requireAdmin();
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getAll($limit, $offset);
        $totalNews = $this->newsModel->countAll();
        $totalPages = ceil($totalNews / $limit);

        $this->loadAdminView('admin/news/index', [
            'page_title' => 'Quản lý Tin tức',
            'news_list' => $news,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        $this->loadAdminView('admin/news/create', ['page_title' => 'Thêm tin tức']);
    }

    private function store() {
        // Simple Image Upload
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $target = 'public/uploads/news/' . uniqid() . '_' . basename($_FILES['image']['name']);
            if (!is_dir('public/uploads/news/')) mkdir('public/uploads/news/', 0777, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = $target;
            }
        }

        $data = [
            'title' => $_POST['title'],
            'summary' => $_POST['summary'],
            'content' => $_POST['content'],
            'image' => $imagePath
        ];

        $this->newsModel->create($data);
        $this->redirect('index.php?page=admin_news_list');
    }

    public function edit() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? 0;
        $news = $this->newsModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id, $news['image']);
            return;
        }

        $this->loadAdminView('admin/news/edit', [
            'page_title' => 'Sửa tin tức', 
            'news' => $news
        ]);
    }

    private function update($id, $oldImage) {
        $imagePath = $oldImage;
        if (!empty($_FILES['image']['name'])) {
            $target = 'public/uploads/news/' . uniqid() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = $target;
            }
        }

        $data = [
            'title' => $_POST['title'],
            'summary' => $_POST['summary'],
            'content' => $_POST['content'],
            'image' => $imagePath
        ];

        $this->newsModel->update($id, $data);
        $this->redirect('index.php?page=admin_news_list');
    }

    public function delete() {
        $this->requireAdmin();
        $id = $_POST['id'] ?? 0;
        if ($this->newsModel->delete($id)) {
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
