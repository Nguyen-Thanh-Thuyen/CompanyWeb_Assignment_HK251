<?php

// index.php - Global Router

// 1. Load Configuration
require_once 'config/config.php';

// 2. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Get Page Parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 4. Database Connection
// (Kept for legacy controllers that might still need it passed manually)
$db_connection = isset($pdo) ? $pdo : null;

// ====================================================================
// ROUTING SWITCH
// ====================================================================

switch ($page) {

    // --- HOME ---
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

        // ====================================================
        // 1. AUTHENTICATION (Login, Register, Logout, Profile)
        // ====================================================
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

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'profile':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->profile();
        break;

        // ====================================================
        // 2. PRODUCTS (List & Detail)
        // ====================================================
    case 'products':
    case 'product_list':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController(); // No params needed
        $controller->index();
        break;

    case 'product_detail':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController(); // No params needed
        $controller->detail();
        break;

        // ====================================================
        // 3. CART & CHECKOUT FLOW
        // ====================================================
    case 'cart':
    case 'add_to_cart':
    case 'update_cart':
    case 'remove_from_cart':
    case 'checkout':
        require_once 'controllers/CartController.php';
        $controller = new CartController(); // No params needed

        switch ($page) {
            case 'cart':             $controller->index();
                break;
            case 'add_to_cart':      $controller->addToCart();
                break;
            case 'update_cart':      $controller->updateCart();
                break;
            case 'remove_from_cart': $controller->removeFromCart();
                break;
            case 'checkout':         $controller->checkout();
                break;
        }
        break;

        // ====================================================
        // 4. PAYMENT PROCESS
        // ====================================================
    case 'payment':
    case 'process_payment':
        require_once 'controllers/PaymentController.php';
        $controller = new PaymentController(); // No params needed

        if ($page === 'payment') {
            $controller->index();
        } else {
            $controller->process();
        }
        break;

        // ====================================================
        // 5. ORDERS (User & Admin)
        // ====================================================
    case 'my_orders':
    case 'order_detail':
    case 'admin_order_list':
    case 'admin_order_detail':
    case 'admin_order_update_status':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController(); // No params needed

        switch ($page) {
            // User Actions
            case 'my_orders':               $controller->myOrders();
                break;
            case 'order_detail':            $controller->detail();
                break;
                // Admin Actions
            case 'admin_order_list':        $controller->adminList();
                break;
            case 'admin_order_detail':      $controller->adminDetail();
                break;
            case 'admin_order_update_status': $controller->updateStatus();
                break;
        }
        break;

        // ====================================================
        // 6. ADMIN: PRODUCT MANAGEMENT
        // ====================================================
    case 'admin_product_list':
    case 'admin_product_create':
    case 'admin_product_edit':
    case 'admin_product_delete':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController(); // No params needed

        switch ($page) {
            case 'admin_product_list':   $controller->adminList();
                break;
            case 'admin_product_create': $controller->create();
                break;
            case 'admin_product_edit':   $controller->edit();
                break;
            case 'admin_product_delete': $controller->delete();
                break;
        }
        break;

        // ====================================================
        // 7. CONTENT PAGES (News, About, Contact, FAQ)
        // ====================================================
        // Note: Passing $db_connection here for legacy controllers

        // --- NEWS ---
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

        // --- ADMIN NEWS ---
    case 'admin_news_list':
    case 'admin_news_create':
    case 'admin_news_edit':
    case 'admin_news_delete':
        require_once 'controllers/AdminNewsController.php';
        $controller = new AdminNewsController($db_connection);
        if ($page === 'admin_news_list') {
            $controller->index();
        } elseif ($page === 'admin_news_create') {
            $controller->create();
        } elseif ($page === 'admin_news_edit') {
            $controller->edit();
        } else {
            $controller->delete();
        }
        break;

        // --- ABOUT & FAQ ---
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

        // --- CONTACT ---
    case 'contact':
    case 'contact_submit':
        require_once 'controllers/ContactController.php';
        $controller = new ContactController($db_connection);
        if ($page === 'contact') {
            $controller->index();
        } else {
            $controller->send();
        }
        break;

        // ====================================================
        // 8. OTHER ADMIN MODULES
        // ====================================================

        // --- ADMIN SETTINGS ---
    case 'admin_settings':
        require_once 'controllers/AdminSettingController.php';
        $controller = new AdminSettingController($db_connection);
        $controller->index();
        break;

    case 'admin_page_settings':
        require_once 'controllers/AdminPageSettingController.php';
        $controller = new AdminPageSettingController($db_connection);
        $controller->index();
        break;
    case 'admin_dashboard':
        require_once 'controllers/AdminDashboardController.php';
        $controller = new AdminDashboardController();
        $controller->index();
        break;
        // --- ADMIN CONTACTS ---
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

        // --- ADMIN FAQ ---
    case 'admin_faq_list':
    case 'admin_faq_create':
    case 'admin_faq_edit':
    case 'admin_faq_delete':
        require_once 'controllers/AdminFaqController.php';
        $controller = new AdminFaqController($db_connection);
        if ($page === 'admin_faq_list') {
            $controller->index();
        } elseif ($page === 'admin_faq_create') {
            $controller->create();
        } elseif ($page === 'admin_faq_edit') {
            $controller->edit();
        } else {
            $controller->delete();
        }
        break;

        // --- ADMIN COMMENTS ---
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

        // ====================================================
        // 404 NOT FOUND
        // ====================================================
    default:
        http_response_code(404);
        require_once 'views/layouts/header.php';
        echo '<div class="container my-5 text-center">';
        echo '<h1 class="display-1">404</h1>';
        echo '<p class="lead">Trang bạn tìm kiếm không tồn tại.</p>';
        echo '<a href="index.php" class="btn btn-primary">Về trang chủ</a>';
        echo '</div>';
        require_once 'views/layouts/footer.php';
        break;
}
