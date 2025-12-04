<?php
/**
 * CartController.php
 */

require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CartItemModel.php';
require_once 'BaseController.php'; 

class CartController extends BaseController {
    private $cartModel;
    private $productModel;
    private $cartItemModel;

    public function __construct() {
        // 1. Initialize DB via BaseController
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);

        // 2. Initialize Models
        $this->cartModel = new CartModel($db);
        $this->productModel = new ProductModel($db);
        $this->cartItemModel = new CartItemModel($db);
    }

    /**
     * PAGE: Display Cart
     * Route: index.php?page=cart
     */
    public function index() {
        // 1. Get raw cart data from session [id => qty]
        $cartSession = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;

        // 2. Fetch fresh product details from DB
        foreach ($cartSession as $id => $qty) {
            $product = $this->productModel->getById($id);
            if ($product) {
                // Combine DB data with Session Quantity
                $product['quantity'] = $qty; 
                $cartItems[] = $product;
                $total += $product['price'] * $qty;
            }
        }

        $data = [
            'cartItems' => $cartItems,
            'total' => $total,
            'page_title' => 'Giỏ hàng'
        ];

        // Use BaseController's loadView
        $this->loadView('product/cart', $data);
    }

    /**
     * ACTION: Handle Checkout Logic (The new feature)
     * Route: index.php?page=checkout
     */
    public function checkout() {
        // 1. Check if Cart is empty
        if (empty($_SESSION['cart'])) {
            $this->redirect('index.php?page=product_list');
        }

        // 2. Check Login
        if (!isset($_SESSION['user_id'])) {
            // Optional: You could save a "return_url" in session here
            $this->redirect('index.php?page=login');
        }

        // 3. Save Note to Session (to display on Payment page)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['checkout_note'] = htmlspecialchars($_POST['note'] ?? '');
        }

        // 4. Redirect to Payment Page
        $this->redirect('index.php?page=payment');
    }

    /**
     * AJAX: Add to Cart
     * Route: index.php?page=add_to_cart
     */
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        // 1. Validate Product
        $product = $this->productModel->getById($productId);
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            return;
        }

        // 2. Add to Session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        // 3. Return Success
        echo json_encode([
            'success' => true, 
            'message' => 'Đã thêm vào giỏ hàng',
            'cartCount' => $this->getCartCount()
        ]);
    }

    /**
     * AJAX: Update Quantity
     * Route: index.php?page=update_cart
     */
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

        if ($productId > 0) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }

            // Recalculate Total for JSON response
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $qty) {
                $p = $this->productModel->getById($id);
                if ($p) $total += $p['price'] * $qty;
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật thành công',
                'total' => number_format($total, 0, ',', '.'),
                'cartCount' => $this->getCartCount()
            ]);
        }
    }

    /**
     * AJAX: Remove Item
     * Route: index.php?page=remove_from_cart
     */
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Đã xóa sản phẩm',
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi xóa sản phẩm']);
        }
    }

    /**
     * Helper: Get total items count for Badge
     */
    private function getCartCount() {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $count += $qty;
            }
        }
        return $count;
    }
}
?>
