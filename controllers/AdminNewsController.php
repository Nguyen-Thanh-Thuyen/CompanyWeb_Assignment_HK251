<?php
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
// Tương tự ProductController trong phần Admin
class AdminNewsController {
    // Xem danh sách (bao gồm tìm kiếm)
    public function index() {
        // require_once 'views/admin/news_list.php';
    }

    // Thêm mới
    public function create() {
        // Xử lý POST và upload hình ảnh
    }

    // Sửa
    public function edit() {
        // Xử lý POST và upload hình ảnh
    }

    // Xóa
    public function delete() {
        // $model->deleteArticle($_GET['id']);
    }
}
?>
