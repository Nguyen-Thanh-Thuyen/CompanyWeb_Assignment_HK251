<?php
// Load file cấu hình đầu tiên
require_once 'config/config.php';

// Lấy tham số 'page' từ URL (VD: index.php?page=admin_settings)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    
    case 'contact': // Hiển thị form
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $controller->index();
        break;

    case 'contact_submit': // Xử lý gửi form
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $controller->send();
        break;

    case 'admin_settings':
        require_once 'controllers/AdminSettingController.php';
        $controller = new AdminSettingController();
        $controller->index();
        break;
       
    // --- KHU VỰC ADMIN: LIÊN HỆ ---    
    // 1. Xem danh sách
    case 'admin_contacts':
        require_once 'controllers/AdminContactController.php';
        $controller = new AdminContactController();
        $controller->index();
        break;

    // 2. Cập nhật trạng thái (Mới <-> Đã đọc)
    case 'admin_contact_status':
        require_once 'controllers/AdminContactController.php';
        $controller = new AdminContactController();
        $controller->update_status();
        break;

    // 3. Xóa liên hệ
    case 'admin_contact_delete':
        require_once 'controllers/AdminContactController.php';
        $controller = new AdminContactController();
        $controller->delete();
        break;
        
    default:
        echo "404 - Không tìm thấy trang";
        break;
}
?>