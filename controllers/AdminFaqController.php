<?php
// Cần model FaqModel để tương tác DB
 require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class AdminFaqController {
    // Xem danh sách
    public function index() {
        // Hiển thị danh sách FAQ, có form tìm kiếm/phân trang
    }

    // Hiển thị form và xử lý thêm mới
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->addFaq($_POST['question'], $_POST['answer']);
        }
        require_once 'views/admin/faq_create.php';
    }

    // Hiển thị form và xử lý chỉnh sửa
    public function edit() {
        // Lấy ID FAQ từ GET, load dữ liệu, xử lý POST
    }

    // Xử lý xóa
    public function delete() {
        $model->deleteFaq($_GET['id']);
        // Chuyển hướng về admin_faq_list
    }
}
?>
