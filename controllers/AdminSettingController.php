<?php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/SettingModel.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class AdminSettingController {
    private $model;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->model = new SettingModel($this->db);
    }

    public function index() {
        // Xử lý khi người dùng nhấn nút "Lưu" (POST request)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->saveSettings();
        }

        // Lấy dữ liệu cũ để hiển thị ra form
        $settings = $this->model->getInfo();
        
        // Gọi View để hiển thị
        include ROOT_PATH . '/views/admin/settings.php';
    }

    private function saveSettings() {
        $id = $_POST['id'];
        $currentLogo = $_POST['current_logo'];
        $logoPath = $currentLogo; // Mặc định giữ logo cũ

        // --- XỬ LÝ UPLOAD ẢNH ---
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $fileName = basename($_FILES["logo"]["name"]);
            // Đặt tên file mới để tránh trùng (thêm timestamp)
            $newFileName = time() . '_' . $fileName;
            $targetFile = UPLOAD_PATH . $newFileName;
            
            // Kiểm tra đuôi file
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowed)) {
                // Di chuyển file từ bộ nhớ tạm vào thư mục D:\XAMPP\htdocs\WEB\public\uploads\
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                    $logoPath = $newFileName; // Chỉ lưu tên file vào DB
                }
            }
        }
        // -------------------------thông tin công ty-------------------------
        $data = [
            'id' => $id,
            'company_name' => $_POST['company_name'],
            'phone_number' => $_POST['phone_number'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'intro_text' => $_POST['intro_text'],
            'logo_path' => $logoPath
        ];

        if ($this->model->updateInfo($data)) {
            $message = "Cập nhật thành công!";
        } else {
            $error = "Có lỗi xảy ra!";
        }
        
        // Reload lại trang để thấy thay đổi
        header("Location: index.php?page=admin_settings");
    }
}
?>
