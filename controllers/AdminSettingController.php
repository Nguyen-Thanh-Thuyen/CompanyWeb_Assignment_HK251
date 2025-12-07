<?php
// controllers/AdminSettingController.php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/SettingModel.php';
require_once 'BaseController.php';

class AdminSettingController extends BaseController { // Inherit BaseController for loadAdminView
    private $model;

    public function __construct($db_connection) {
        parent::__construct($db_connection);
        $this->model = new SettingModel($this->db);
    }

    public function index() {
        // Handle POST Request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->saveSettings();
            return;
        }

        // Get current settings
        $settings = $this->model->getInfo();
        
        // Use loadAdminView from BaseController to keep layout consistent
        $this->loadAdminView('admin/settings', [
            'settings' => $settings,
            'page_title' => 'Cấu hình Website'
        ]);
    }

    private function saveSettings() {
        $id = $_POST['id'];
        $currentLogo = $_POST['current_logo'];
        $logoPath = $currentLogo; 

        // Handle Image Upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $uploadDir = ROOT_PATH . '/public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $extension = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($extension, $allowed)) {
                $newFileName = 'logo_' . time() . '.' . $extension;
                $targetFile = $uploadDir . $newFileName;
                
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                    $logoPath = $newFileName; 
                }
            }
        }

        // Prepare Data
        $data = [
            'id' => $id,
            'company_name' => $_POST['company_name'],
            'phone_number' => $_POST['phone_number'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'intro_text' => $_POST['intro_text'],
            'logo_path' => $logoPath,
            // New Social Fields
            'facebook_url' => $_POST['facebook_url'] ?? '#',
            'twitter_url' => $_POST['twitter_url'] ?? '#',
            'instagram_url' => $_POST['instagram_url'] ?? '#'
        ];

        if ($this->model->updateInfo($data)) {
            echo "<script>alert('Cập nhật thành công!'); location.href='index.php?page=admin_settings';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!'); location.href='index.php?page=admin_settings';</script>";
        }
    }
}
?>
