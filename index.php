<?php

// index.php - Global Router

// 1. Load Configuration
require_once 'config/config.php';

// 2. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Get Page Parameter
$page = $_GET['page'] ?? 'home';

// 4. Database Connection (for legacy controllers)
$db_connection = $pdo ?? null;

// A helper to load controller classes safely
function loadController($file, $class, $param = null) {
    require_once "controllers/$file.php";
    return $param ? new $class($param) : new $class();
}

// ====================================================================
// ROUTING SWITCH
// ====================================================================

switch ($page) {

    // HOME -----------------------------------------------------------
    case 'home':
        $controller = loadController('HomeController', 'HomeController');
        $controller->index();
        break;

    // AUTH -----------------------------------------------------------
    case 'login':
    case 'login_submit':
    case 'register':
    case 'register_submit':
    case 'logout':
    case 'profile':
    case 'update_profile':
    case 'change_password':
    case 'upload_avatar':
        $controller = loadController('AuthController', 'AuthController');

        match ($page) {
            'login'           => $controller->login(),
            'login_submit'    => $controller->handleLogin(),
            'register'        => $controller->register(),
            'register_submit' => $controller->handleRegister(),
            'logout'          => $controller->logout(),
            'profile'         => $controller->profile(),
            'update_profile'  => $controller->updateProfile(),
            'change_password' => $controller->changePassword(),
            'upload_avatar'   => $controller->uploadAvatar()
        };
        break;

    // PRODUCTS -------------------------------------------------------
    case 'products':
    case 'product_list':
    case 'product_detail':
    case 'add_comment':
        $controller = loadController('ProductController', 'ProductController');
        
        match ($page) {
            'products', 'product_list' => $controller->index(),
            'product_detail'           => $controller->detail(),
            'add_comment'              => $controller->addComment()
        };
        break;

    // CART -----------------------------------------------------------
    case 'cart':
    case 'add_to_cart':
    case 'update_cart':
    case 'remove_from_cart':
    case 'checkout':
        $controller = loadController('CartController', 'CartController');

        match ($page) {
            'cart'            => $controller->index(),
            'add_to_cart'     => $controller->addToCart(),
            'update_cart'     => $controller->updateCart(),
            'remove_from_cart'=> $controller->removeFromCart(),
            'checkout'        => $controller->checkout()
        };
        break;

    // PAYMENT --------------------------------------------------------
    case 'payment':
    case 'process_payment':
        $controller = loadController('PaymentController', 'PaymentController');
        ($page === 'payment') ? $controller->index() : $controller->process();
        break;

    // ORDERS (User & Admin) -----------------------------------------
    case 'my_orders':
    case 'order_detail':
    case 'admin_order_list':
    case 'admin_order_detail':
    case 'admin_order_update_status':
        $controller = loadController('OrderController', 'OrderController');

        match ($page) {
            'my_orders'                 => $controller->myOrders(),
            'order_detail'              => $controller->detail(),
            'admin_order_list'          => $controller->adminList(),
            'admin_order_detail'        => $controller->adminDetail(),
            'admin_order_update_status' => $controller->updateStatus()
        };
        break;

    // ADMIN PRODUCT --------------------------------------------------
    case 'admin_product_list':
    case 'admin_product_create':
    case 'admin_product_edit':
    case 'admin_product_delete':
        $controller = loadController('ProductController', 'ProductController');

        match ($page) {
            'admin_product_list'   => $controller->adminList(),
            'admin_product_create' => $controller->create(),
            'admin_product_edit'   => $controller->edit(),
            'admin_product_delete' => $controller->delete()
        };
        break;

    // NEWS -----------------------------------------------------------
    case 'news_list':
    case 'news_detail':
        $controller = loadController('NewsController', 'NewsController', $db_connection);
        ($page === 'news_list') ? $controller->index() : $controller->detail();
        break;

    case 'admin_news_list':
    case 'admin_news_create':
    case 'admin_news_edit':
    case 'admin_news_delete':
        $controller = loadController('AdminNewsController', 'AdminNewsController', $db_connection);

        match ($page) {
            'admin_news_list'   => $controller->index(),
            'admin_news_create' => $controller->create(),
            'admin_news_edit'   => $controller->edit(),
            'admin_news_delete' => $controller->delete()
        };
        break;

    // STATIC CONTENT -------------------------------------------------
    case 'about':
        $controller = loadController('AboutController', 'AboutController', $db_connection);
        $controller->index();
        break;

    case 'faq':
        $controller = loadController('FaqController', 'FaqController', $db_connection);
        $controller->index();
        break;

    // CONTACT --------------------------------------------------------
    case 'contact':
    case 'contact_submit':
        $controller = loadController('ContactController', 'ContactController', $db_connection);
        ($page === 'contact') ? $controller->index() : $controller->send();
        break;

    // ADMIN SETTINGS -------------------------------------------------
    case 'admin_settings':
        // Passes $db_connection to AdminSettingController's constructor
        loadController('AdminSettingController', 'AdminSettingController', $db_connection)->index();
        break;

    case 'admin_page_settings':
        loadController('AdminPageSettingController', 'AdminPageSettingController', $db_connection)->index();
        break;

    case 'admin_dashboard':
        loadController('AdminDashboardController', 'AdminDashboardController')->index();
        break;

    // ADMIN CONTACTS -------------------------------------------------
    case 'admin_contacts':
    case 'admin_contact_status':
    case 'admin_contact_delete':
        $controller = loadController('AdminContactController', 'AdminContactController', $db_connection);

        match ($page) {
            'admin_contacts'       => $controller->index(),
            'admin_contact_status' => $controller->update_status(),
            'admin_contact_delete' => $controller->delete()
        };
        break;

    // ADMIN FAQ ------------------------------------------------------
    case 'admin_faq_list':
    case 'admin_faq_create':
    case 'admin_faq_edit':
    case 'admin_faq_delete':
        $controller = loadController('AdminFaqController', 'AdminFaqController', $db_connection);

        match ($page) {
            'admin_faq_list'   => $controller->index(),
            'admin_faq_create' => $controller->create(),
            'admin_faq_edit'   => $controller->edit(),
            'admin_faq_delete' => $controller->delete()
        };
        break;

    // ADMIN COMMENTS -------------------------------------------------
    case 'admin_comment_list':
    case 'admin_comment_delete':
        $controller = loadController('AdminCommentController', 'AdminCommentController', $db_connection);
        ($page === 'admin_comment_list') ? $controller->index() : $controller->delete();
        break;

    // ADMIN USER -----------------------------------------------------
    case 'admin_user_list':
    case 'admin_user_status':
    case 'admin_user_reset_password':
        $controller = loadController('AdminUserController', 'AdminUserController');

        match ($page) {
            'admin_user_list'           => $controller->index(),
            'admin_user_status'         => $controller->updateStatus(),
            'admin_user_reset_password' => $controller->resetPassword()
        };
        break;

    // 404 -------------------------------------------------------------
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
