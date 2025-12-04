<?php
// controllers/OrderController.php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/OrderModel.php';

class OrderController extends BaseController {
    private $orderModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->orderModel = new OrderModel($db);
    }

    // =========================================================================
    // CLIENT SIDE (Customer View)
    // =========================================================================

    /**
     * View logged-in user's order history
     */
    public function myOrders() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        $orders = $this->orderModel->getOrdersByUser($_SESSION['user_id']);

        $data = [
            'orders' => $orders,
            'page_title' => 'Lịch sử đơn hàng'
        ];
        $this->loadView('order/index', $data);
    }

    /**
     * View details of a specific order (Client)
     */
    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Client: Check ownership (Pass $_SESSION['user_id'])
        $order = $this->orderModel->getOrderById($orderId, $_SESSION['user_id']);

        if (!$order) {
            $this->redirect('index.php?page=my_orders');
            return;
        }

        $items = $this->orderModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'items' => $items,
            'page_title' => 'Chi tiết đơn hàng #' . $orderId
        ];
        $this->loadView('order/detail', $data);
    }

    // =========================================================================
    // ADMIN SIDE (Admin Management)
    // =========================================================================

    /**
     * ADMIN: List all orders with pagination
     * Route: index.php?page=admin_order_list
     */
    public function adminList() {
        $this->requireAdmin();

        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getAllOrders($limit, $offset);
        $totalOrders = $this->orderModel->countAll();
        $totalPages = ceil($totalOrders / $limit);

        $data = [
            'page_title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders
        ];

        // Uses Tabler Admin Layout
        $this->loadAdminView('admin/order/index', $data);
    }

    /**
     * ADMIN: View Order Detail
     * Route: index.php?page=admin_order_detail&id=X
     */
    public function adminDetail() {
        $this->requireAdmin();
        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Admin: Pass null as userId to bypass ownership check
        $order = $this->orderModel->getOrderById($orderId, null);
        
        if (!$order) {
            $this->redirect('index.php?page=admin_order_list');
        }

        $items = $this->orderModel->getOrderItems($orderId);

        $this->loadAdminView('admin/order/detail', [
            'page_title' => 'Chi tiết đơn hàng #' . $orderId,
            'order' => $order,
            'items' => $items
        ]);
    }

    /**
     * ADMIN: Update Status
     * Route: index.php?page=admin_order_update_status
     */
    public function updateStatus() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = intval($_POST['order_id']);
            $status = $_POST['status'];
            
            $result = $this->orderModel->updateStatus($orderId, $status);

            // Check if AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => $result, 'message' => $result ? 'Cập nhật thành công' : 'Lỗi cập nhật']);
            } else {
                // Standard Post: Redirect back
                $this->redirect('index.php?page=admin_order_detail&id=' . $orderId);
            }
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?page=home');
        }
    }

    /**
     * Helper to load Admin Views with the Admin Layout
     */
    protected function loadAdminView($view, $data = []) {
        extract($data);
        require_once ROOT_PATH . '/views/layouts/admin_layout.php';
    }
}
