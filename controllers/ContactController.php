<?php
// D:\XAMPP\htdocs\WEB\controllers\ContactController.php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/ContactModel.php';
require_once ROOT_PATH . '/models/SettingModel.php'; // Để lấy menu/logo hiện ra view
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class ContactController {
    private $contactModel;
    private $settingModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->contactModel = new ContactModel($this->db);
        $this->settingModel = new SettingModel($this->db);
    }

    // Hiển thị form liên hệ
    public function index() {
        $settings = $this->settingModel->getInfo(); // Lấy header/footer
        require_once ROOT_PATH . '/views/client/contact.php';
    }

    // Xử lý khi bấm nút Gửi
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $message = trim($_POST['message']);
            
            $errors = [];

            // --- VALIDATION SERVER-SIDE (Bắt buộc) ---
            if (empty($full_name)) $errors[] = "Vui lòng nhập họ tên.";
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
            if (empty($message)) $errors[] = "Vui lòng nhập nội dung.";
            // -----------------------------------------

            if (empty($errors)) {
                $data = [
                    'full_name' => $full_name,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message
                ];
                
                if ($this->contactModel->create($data)) {
                    echo "<script>alert('Gửi liên hệ thành công!'); window.location.href='index.php?page=contact';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống!'); window.history.back();</script>";
                }
            } else {
                // Xuất lỗi ra màn hình
                $errorString = implode("\\n", $errors);
                echo "<script>alert('$errorString'); window.history.back();</script>";
            }
        }
    }
}
?>
