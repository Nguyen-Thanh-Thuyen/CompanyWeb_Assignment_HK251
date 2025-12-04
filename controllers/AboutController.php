<?php
require_once 'models/SettingModel.php'; // Để lấy thông tin trang Giới thiệu
require_once 'views/AboutView.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class AboutController {
    public function index() {
        // 1. Lấy dữ liệu: Thông tin giới thiệu (từ database/setting)
        $model = new SettingModel();
        $data = $model->getGeneralSettings(); 

        // 2. Hiển thị View
        $view = new AboutView();
        $view->render($data);
    }
}
?>
