<?php
// D:\XAMPP\htdocs\WEB\controllers\HomeController.php

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/SettingModel.php';

class HomeController {
    private $model;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->model = new SettingModel($this->db);
    }

    public function index() {
        // 1. Lấy thông tin cấu hình web (Logo, SĐT,...) từ CSDL
        $settings = $this->model->getInfo();

        // 2. Gọi giao diện trang chủ để hiển thị
        require_once ROOT_PATH . '/views/client/home.php';
    }
}
?>