<?php
// controllers/PaymentController.php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/OrderModel.php';
require_once ROOT_PATH . '/models/CartModel.php';

class PaymentController extends BaseController {
    private $productModel;
    private $orderModel;
    private $cartModel;
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);

        $this->productModel = new ProductModel($db);
        $this->orderModel   = new OrderModel($db);
        $this->cartModel    = new CartModel($db);
    }

    // =============================
    // HIỂN THỊ TRANG THANH TOÁN
    // =============================
   public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        $userId = $_SESSION['user_id'];

        // LẤY GIỎ HÀNG TỪ DATABASE (đúng với user đã login)
        $cart = $this->cartModel->getOrCreateActiveCart($userId);
        $cartItems = $this->cartModel->getCartItems($cart['id']);

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $data = [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => [
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email']
            ],
            'note' => $_SESSION['checkout_note'] ?? '',
            'page_title' => 'Thanh toán'
        ];

        $this->loadView('product/payment', $data);
}


    // =============================
    // XỬ LÝ ĐẶT HÀNG
    // =============================
    public function process() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=home');
        }

        $userId = $_SESSION['user_id'];
        $note = $_SESSION['checkout_note'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        // LẤY GIỎ HÀNG ĐÚNG CHUẨN TỪ DATABASE
        $cart = $this->cartModel->getOrCreateActiveCart($userId);
        $cartItems = $this->cartModel->getCartItems($cart['id']);

        // TÍNH TỔNG TIỀN
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // TẠO ĐƠN HÀNG
        $orderId = $this->orderModel->createOrder(
            $userId,
            $total,
            $paymentMethod,
            $note
        );

        if ($orderId) {
            // LƯU TỪNG SẢN PHẨM CỦA ĐƠN HÀNG
            foreach ($cartItems as $item) {
                $this->orderModel->addOrderItem(
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity']
                );
            }

            // XÓA GIỎ HÀNG DATABASE (clear cart)
            $this->cartModel->clearCart($cart['id']);

            // XÓA SESSION NOTE
            unset($_SESSION['checkout_note']);

            echo "<script>alert('Đặt hàng thành công!'); 
                window.location.href='index.php?page=my_orders';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi khi tạo đơn hàng. Vui lòng thử lại!'); window.history.back();</script>";
        }
    }

}
?>
