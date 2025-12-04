<?php
/**
 * OrderController.php
 * Controller xử lý đơn hàng
 */

require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/CartModel.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class OrderController {
    private $orderModel;
    private $cartModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->orderModel = new OrderModel($db);
        $this->cartModel = new CartModel($db);
    }

    /**
     * USER: Checkout - tạo order từ cart
     * Route: index.php?page=checkout (POST)
     * Yêu cầu: User phải đăng nhập
     */
    public function checkout() {
        // TODO: Check if user is logged in
        // if (!isLoggedIn()) {
        //     $_SESSION['error'] = "Vui lòng đăng nhập để đặt hàng";
        //     $this->redirect('index.php?page=login');
        //     return;
        // }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=cart');
            return;
        }

        // TODO: Lấy user_id từ session
        // $userId = $_SESSION['user']['id'];
        $userId = 1; // Fake user ID cho testing

        // Lấy cart active của user
        $cart = $this->cartModel->getOrCreateActiveCart($userId);

        if (!$cart) {
            $_SESSION['error'] = "Không tìm thấy giỏ hàng";
            $this->redirect('index.php?page=cart');
            return;
        }

        // Kiểm tra cart có items không
        $cartItems = $this->cartModel->getCartItems($cart['id']);

        if (empty($cartItems)) {
            $_SESSION['error'] = "Giỏ hàng trống";
            $this->redirect('index.php?page=cart');
            return;
        }

        // Lấy note từ form
        $note = isset($_POST['note']) ? htmlspecialchars(trim($_POST['note'])) : '';

        // Tạo order
        $orderId = $this->orderModel->createFromCart($userId, $cart['id'], $note);

        if ($orderId) {
            $_SESSION['success'] = "Đặt hàng thành công! Mã đơn hàng: #" . $orderId;
            $this->redirect('index.php?page=order_detail&id=' . $orderId);
        } else {
            $_SESSION['error'] = "Đặt hàng thất bại. Vui lòng thử lại";
            $this->redirect('index.php?page=cart');
        }
    }

    /**
     * USER: Xem chi tiết đơn hàng
     * Route: index.php?page=order_detail&id=xxx
     */
    public function detail() {
        // TODO: Check if user is logged in
        // if (!isLoggedIn()) {
        //     $this->redirect('index.php?page=login');
        //     return;
        // }

        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($orderId <= 0) {
            $this->redirect('index.php?page=home');
            return;
        }

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error'] = "Đơn hàng không tồn tại";
            $this->redirect('index.php?page=home');
            return;
        }

        // TODO: Check if order belongs to current user (security)
        // if ($order['user_id'] != $_SESSION['user']['id'] && !isAdmin()) {
        //     $_SESSION['error'] = "Bạn không có quyền xem đơn hàng này";
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        $orderItems = $this->orderModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems
        ];

        $this->loadView('product/order_detail', $data);
    }

    /**
     * USER: Danh sách đơn hàng của user
     * Route: index.php?page=my_orders
     */
    public function myOrders() {
        // TODO: Check if user is logged in
        // if (!isLoggedIn()) {
        //     $this->redirect('index.php?page=login');
        //     return;
        // }

        // TODO: Lấy user_id từ session
        // $userId = $_SESSION['user']['id'];
        $userId = 1; // Fake user ID

        $currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $orders = $this->orderModel->getByUserId($userId, $limit, $offset);

        $data = [
            'orders' => $orders,
            'currentPage' => $currentPage
        ];

        $this->loadView('product/my_orders', $data);
    }

    /**
     * ADMIN: Danh sách tất cả đơn hàng
     * Route: index.php?page=admin_order_list&status=xxx&p=1
     */
    public function adminList() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 20;
        $offset = ($currentPage - 1) * $limit;

        $orders = $this->orderModel->getAllForAdmin($status, $limit, $offset);
        $totalOrders = $this->orderModel->countAll($status);
        $totalPages = ceil($totalOrders / $limit);

        // Lấy thống kê theo status
        $stats = $this->orderModel->getStatsByStatus();

        $data = [
            'orders' => $orders,
            'stats' => $stats,
            'status' => $status,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders
        ];

        $this->loadView('admin/order/index', $data);
    }

    /**
     * ADMIN: Xem chi tiết đơn hàng (admin view)
     * Route: index.php?page=admin_order_detail&id=xxx
     */
    public function adminDetail() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($orderId <= 0) {
            $this->redirect('index.php?page=admin_order_list');
            return;
        }

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error'] = "Đơn hàng không tồn tại";
            $this->redirect('index.php?page=admin_order_list');
            return;
        }

        $orderItems = $this->orderModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems
        ];

        $this->loadView('admin/order/detail', $data);
    }

    /**
     * ADMIN: Cập nhật trạng thái đơn hàng
     * Route: index.php?page=admin_order_update_status (POST)
     */
    public function updateStatus() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        //     return;
        // }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=admin_order_list');
            return;
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if ($orderId <= 0 || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $result = $this->orderModel->updateStatus($orderId, $status);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái thất bại']);
        }
    }

    /**
     * Helper: Load view
     */
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    /**
     * Helper: Redirect
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}
