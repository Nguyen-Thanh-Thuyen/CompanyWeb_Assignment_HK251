<?php
// controllers/HomeController.php

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/SettingModel.php';
require_once 'BaseController.php'; 
require_once ROOT_PATH . '/models/ProductModel.php';

class HomeController extends BaseController {
    private $settingModel;
    private $productModel;

    public function __construct() {
        // 1. Initialize Database
        $database = new Database();
        $db = $database->getConnection();

        // 2. Pass DB connection to BaseController (Parent)
        parent::__construct($db);
        
        // 3. Initialize Models using the inherited $this->db
        $this->settingModel = new SettingModel($this->db);
        $this->productModel = new ProductModel($this->db);
    }

    public function index() {
        // 1. Get Settings
        $settings = $this->settingModel->getInfo();

        // 2. Get Products (8 items)
        $products = $this->productModel->getProducts(8);

        // 3. Prepare data
        $data = [
            'settings' => $settings,
            'products' => $products,
            'page_title' => 'Trang chủ'
        ];

        // 4. Load View using BaseController's method
        // This automatically includes:
        // views/layouts/header.php
        // views/client/home.php
        // views/layouts/footer.php
        $this->loadView('client/home', $data); 
    }
}
?>
