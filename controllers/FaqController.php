<?php
require_once 'models/FaqModel.php'; 
require_once 'views/FaqView.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class FaqController {
    // Có thể cần constructor nếu có kết nối $pdo
    // public function __construct($pdo) { ... } 

    public function index() {
        // 1. Lấy dữ liệu: Danh sách câu hỏi và câu trả lời
        $model = new FaqModel();
        $faqs = $model->getAllFaqs(); 

        // 2. Hiển thị View
        $view = new FaqView();
        $view->render(['faqs' => $faqs]);
    }
}
?>
