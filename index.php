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

    // ========================================
    // PHẦN #3 - PRODUCT & CART SYSTEM
    // ========================================
    
    // --- USER/GUEST: SẢN PHẨM ---
    case 'product_list':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->list();
        break;

    case 'product_detail':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->detail();
        break;

    // --- USER/GUEST: GIỎ HÀNG ---
    case 'cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->index();
        break;

    case 'add_to_cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->addToCart();
        break;

    case 'update_cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->updateCart();
        break;

    case 'remove_from_cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->removeFromCart();
        break;

    // --- USER: ĐƠN HÀNG ---
    case 'checkout':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->checkout();
        break;

    case 'order_detail':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->detail();
        break;

    case 'my_orders':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->myOrders();
        break;

    // --- ADMIN: QUẢN LÝ SẢN PHẨM ---
    case 'admin_product_list':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->adminList();
        break;

    case 'admin_product_create':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->create();
        break;

    case 'admin_product_edit':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->edit();
        break;

    case 'admin_product_delete':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->delete();
        break;

    // --- ADMIN: QUẢN LÝ ĐƠN HÀNG ---
    case 'admin_order_list':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->adminList();
        break;

    case 'admin_order_detail':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->adminDetail();
        break;

    case 'admin_order_update_status':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($pdo);
        $controller->updateStatus();
        break;

    // ========================================
    // END PHẦN #3
    // ========================================
        
    default:
        echo "404 - Không tìm thấy trang";
        break;
}
?>