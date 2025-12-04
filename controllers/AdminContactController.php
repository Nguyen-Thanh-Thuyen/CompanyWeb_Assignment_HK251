<?php
// D:\XAMPP\htdocs\WEB\controllers\AdminContactController.php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/ContactModel.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class AdminContactController {
    private $model;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->model = new ContactModel($this->db);
    }

    // 1. Hiển thị danh sách liên hệ
    public function index() {
        $contacts = $this->model->getAll(); // Lấy tất cả từ DB
        require_once ROOT_PATH . '/views/admin/contacts.php';
    }

    // 2. Xử lý: Đánh dấu đã đọc / chưa đọc
    public function update_status() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status']; // 'read' hoặc 'new'
            $this->model->updateStatus($id, $status);
        }
        // Quay lại trang danh sách
        header("Location: index.php?page=admin_contacts");
    }

    // 3. Xử lý: Xóa liên hệ
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->delete($id);
        }
        // Quay lại trang danh sách
        header("Location: index.php?page=admin_contacts");
    }
}
?>
