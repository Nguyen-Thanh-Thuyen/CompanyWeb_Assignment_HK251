<?php
// controllers/PaymentController.php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/OrderModel.php'; // <--- NEW

class PaymentController extends BaseController {
    private $productModel;
    private $orderModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);
        $this->productModel = new ProductModel($db);
        $this->orderModel = new OrderModel($db); // <--- NEW
    }

    // Show Payment Page (Keep existing index method)
    public function index() {
        // ... (Your existing code for index) ...
        // Ensure you check login and cart existence here
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php?page=login');
        
        // Re-calculate total logic...
        $cartSession = $_SESSION['cart'] ?? [];
        $total = 0;
        $cartItems = [];
        foreach ($cartSession as $id => $rawQty) {
            $qty = is_array($rawQty) ? ($rawQty['quantity'] ?? 1) : intval($rawQty);
            $p = $this->productModel->getById($id);
            if ($p) {
                $p['quantity'] = $qty;
                $cartItems[] = $p;
                $total += $p['price'] * $qty;
            }
        }
        
        $data = [
            'cartItems' => $cartItems, 
            'total' => $total, 
            'user' => ['name' => $_SESSION['user_name'], 'email' => $_SESSION['user_email']],
            'note' => $_SESSION['checkout_note'] ?? '',
            'page_title' => 'Thanh toán'
        ];
        $this->loadView('product/payment', $data);
    }

    // PROCESS THE ORDER (SAVE TO DB)
    public function process() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            $this->redirect('index.php?page=home');
        }

        // 1. Prepare Data
        $userId = $_SESSION['user_id'];
        $note = $_SESSION['checkout_note'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'cod';
        
        // 2. Calculate Total & Get Items
        $cartSession = $_SESSION['cart'];
        $total = 0;
        $orderItems = [];

        foreach ($cartSession as $id => $rawQty) {
            $qty = is_array($rawQty) ? ($rawQty['quantity'] ?? 1) : intval($rawQty);
            $product = $this->productModel->getById($id);
            if ($product) {
                $total += $product['price'] * $qty;
                $orderItems[] = [
                    'id' => $id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'qty' => $qty
                ];
            }
        }

        // 3. Create Order Record
        $orderId = $this->orderModel->createOrder($userId, $total, $paymentMethod, $note);

        if ($orderId) {
            // 4. Save Order Items
            foreach ($orderItems as $item) {
                $this->orderModel->addOrderItem(
                    $orderId, 
                    $item['id'], 
                    $item['name'], 
                    $item['price'], 
                    $item['qty']
                );
                
                // Optional: Decrease Stock in ProductModel here
            }

            // 5. Clear Cart & Session
            unset($_SESSION['cart']);
            unset($_SESSION['checkout_note']);

            // 6. Redirect to "My Orders"
            echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php?page=my_orders';</script>";
        } else {
            echo "<script>alert('Lỗi khi tạo đơn hàng.'); window.history.back();</script>";
        }
    }
}
?>
