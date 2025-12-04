<?php
// controllers/AboutController.php
require_once 'BaseController.php';

class AboutController extends BaseController {
    
    public function __construct() {
        // Initialize DB if needed, though static pages might not need it
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
    }

    public function index() {
        $data = [
            'page_title' => 'Về chúng tôi - Câu chuyện thương hiệu'
        ];
        // Loading views/client/about.php
        $this->loadView('client/about', $data);
    }
}
?>
