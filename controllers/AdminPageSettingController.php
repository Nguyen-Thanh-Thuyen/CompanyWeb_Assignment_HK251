<?php
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
// Tương tự AdminSettingController
class AdminPageSettingController {
    public function index() {
        // 1. Xử lý logic GET/POST (hiển thị form hoặc lưu dữ liệu)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý lưu thông tin trang Giới thiệu vào database
            $this->saveSettings($_POST);
        }

        // 2. Lấy dữ liệu hiện tại và hiển thị View
        $data = $model->getPageSettings('about');
        require_once 'views/admin/page_settings.php';
    }
}
?>
