<?php

// index.php - Global Router/Entry Point

// Load file cấu hình đầu tiên (Giả định config/config.php định nghĩa BASE_URL và khởi tạo $pdo)
require_once 'config/config.php';

// Bắt đầu session (rất cần thiết cho giỏ hàng, flash messages...)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tham số 'page' từ URL (VD: index.php?page=admin_settings)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Khởi tạo biến $db_connection (sử dụng $pdo từ config)
$db_connection = isset($pdo) ? $pdo : null;

// ====================================================================
// HÀM HELPER GIẢ ĐỊNH (Cần được tích hợp vào BaseController)
// Giả định hàm này gọi BaseController::loadView() để chèn Header/Footer
// ====================================================================
function render_page($viewPath, $data = [], $settings = [])
{
    // Để tích hợp header/footer:
    // Tệp view (ví dụ: views/product/list.php) phải được gọi bởi một hàm
    // bên trong Controller (ví dụ: $this->loadView('product/list', $data)).

    // TRONG CONTROLLER BẠN CẦN LÀM ĐIỀU NÀY:
    // function loadView($view, $data = [], $settings = []) {
    //     extract($data);
    //     require_once 'views/layouts/header.php'; // <--- Tải Header
    //     require_once 'views/' . $view . '.php';  // <--- Tải View cụ thể
    //     require_once 'views/layouts/footer.php'; // <--- Tải Footer
    // }

    // Vì router không biết $settings, bạn cần đảm bảo $settings được load trong Controller hoặc BaseController.

    // --- KHỞI CHẠY CONTROLLER (Logic giữ nguyên) ---
    extract($data);
    // Đây chỉ là một placeholder để tránh lỗi khi bạn test
    // Trong môi trường MVC thực, code này KHÔNG NÊN nằm trong Router.
    // Các Controller PHẢI chịu trách nhiệm load View.
    if (!empty($viewPath)) {
        // Thực thi view logic...
        // Tạm thời, chúng ta sẽ để Controller tự lo liệu.
    }
}
// ====================================================================


switch ($page) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController($db_connection);
        $controller->index();
        break;

        // --- USER/GUEST: LIÊN HỆ ---
    case 'contact':
    case 'contact_submit':
        require_once 'controllers/ContactController.php';
        // Giả định ContactController cần DB để lưu form
        $controller = new ContactController($db_connection);
        if ($page === 'contact') {
            $controller->index();
        } else {
            $controller->send();
        }
        break;

        // --- KHU VỰC ADMIN: CÀI ĐẶT ---
    case 'admin_settings':
        require_once 'controllers/AdminSettingController.php';
        $controller = new AdminSettingController($db_connection);
        $controller->index();
        break;

        // --- KHU VỰC ADMIN: LIÊN HỆ ---
    case 'admin_contacts':
    case 'admin_contact_status':
    case 'admin_contact_delete':
        require_once 'controllers/AdminContactController.php';
        $controller = new AdminContactController($db_connection);
        if ($page === 'admin_contacts') {
            $controller->index();
        } elseif ($page === 'admin_contact_status') {
            $controller->update_status();
        } else {
            $controller->delete();
        }
        break;

        // --- KHU VỰC USER/GUEST: GIỚI THIỆU & HỎI/ĐÁP ---
    case 'about':
        require_once 'controllers/AboutController.php';
        $controller = new AboutController($db_connection);
        $controller->index();
        break;

    case 'faq':
        require_once 'controllers/FaqController.php';
        $controller = new FaqController($db_connection);
        $controller->index();
        break;

    case 'admin_page_settings':
        require_once 'controllers/AdminPageSettingController.php';
        $controller = new AdminPageSettingController($db_connection);
        $controller->index();
        break;

        // --- KHU VỰC ADMIN: QUẢN LÝ HỎI/ĐÁP ---
    case 'admin_faq_list':
    case 'admin_faq_create':
    case 'admin_faq_edit':
    case 'admin_faq_delete':
        require_once 'controllers/AdminFaqController.php';
        $controller = new AdminFaqController($db_connection);

        switch ($page) {
            case 'admin_faq_list':
                $controller->index();
                break;
            case 'admin_faq_create':
                $controller->create();
                break;
            case 'admin_faq_edit':
                $controller->edit();
                break;
            case 'admin_faq_delete':
                $controller->delete();
                break;
        }
        break;

        // ========================================
        // PHẦN #3 - PRODUCT & CART SYSTEM
        // ========================================

        // --- USER/GUEST & ADMIN: SẢN PHẨM ---
    case 'products':
    case 'product_list':
    case 'product_detail':
    case 'admin_product_list':
    case 'admin_product_create':
    case 'admin_product_edit':
    case 'admin_product_delete':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db_connection);

        switch ($page) {
            case 'products':
            case 'product_list':
                $controller->index();
                break;
            case 'product_detail':
                $controller->detail();
                break;
            case 'admin_product_list':
                $controller->adminList();
                break;
            case 'admin_product_create':
                $controller->create();
                break;
            case 'admin_product_edit':
                $controller->edit();
                break;
            case 'admin_product_delete':
                $controller->delete();
                break;
        }
        break;

        // --- USER/GUEST: GIỎ HÀNG ---
    case 'cart':
    case 'add_to_cart':
    case 'update_cart':
    case 'remove_from_cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db_connection);

        switch ($page) {
            case 'cart':
                $controller->index();
                break;
            case 'add_to_cart':
                $controller->addToCart();
                break;
            case 'update_cart':
                $controller->updateCart();
                break;
            case 'remove_from_cart':
                $controller->removeFromCart();
                break;
        }
        break;

        // --- USER/ADMIN: ĐƠN HÀNG ---
    case 'checkout':
    case 'order_detail':
    case 'my_orders':
    case 'admin_order_list':
    case 'admin_order_detail':
    case 'admin_order_update_status':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db_connection);

        switch ($page) {
            case 'checkout':
                $controller->checkout();
                break;
            case 'order_detail':
                $controller->detail();
                break;
            case 'my_orders':
                $controller->myOrders();
                break;
            case 'admin_order_list':
                $controller->adminList();
                break;
            case 'admin_order_detail':
                $controller->adminDetail();
                break;
            case 'admin_order_update_status':
                $controller->updateStatus();
                break;
        }
        break;

        // ========================================
        // PHẦN #4 - NEWS & COMMENT SYSTEM
        // ========================================

        // --- USER/GUEST: TIN TỨC/BÀI VIẾT ---
    case 'news_list':
    case 'news_detail':
        require_once 'controllers/NewsController.php';
        $controller = new NewsController($db_connection);

        if ($page === 'news_list') {
            $controller->index();
        } else {
            $controller->detail();
        }
        break;

        // --- KHU VỰC ADMIN: QUẢN LÝ TIN TỨC ---
    case 'admin_news_list':
    case 'admin_news_create':
    case 'admin_news_edit':
    case 'admin_news_delete':
        require_once 'controllers/AdminNewsController.php';
        $controller = new AdminNewsController($db_connection);

        switch ($page) {
            case 'admin_news_list':
                $controller->index();
                break;
            case 'admin_news_create':
                $controller->create();
                break;
            case 'admin_news_edit':
                $controller->edit();
                break;
            case 'admin_news_delete':
                $controller->delete();
                break;
        }
        break;
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'login_submit':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->handleLogin();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    // --- REGISTER ROUTES ---
    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'register_submit':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->handleRegister();
        break;
        // --- KHU VỰC ADMIN: QUẢN LÝ BÌNH LUẬN ---
    case 'admin_comment_list':
    case 'admin_comment_delete':
        require_once 'controllers/AdminCommentController.php';
        $controller = new AdminCommentController($db_connection);

        if ($page === 'admin_comment_list') {
            $controller->index();
        } else {
            $controller->delete();
        }
        break;

    default:
        // Đặt mã trạng thái HTTP 404
        http_response_code(404);
        // Có thể load một view 404 tùy chỉnh ở đây
        echo "<h1>404</h1><p>Không tìm thấy trang.</p>";
        break;
}
