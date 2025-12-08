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
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);

        $this->cartModel = new CartModel($db);
        $this->productModel = new ProductModel($db);
        $this->cartItemModel = new CartItemModel($db);
    }

    public function index() {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['user_id'])) {
            $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
            if ($cart) {
                $cartItems = $this->cartModel->getCartItems($cart['id']);
                foreach ($cartItems as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        } else {
            $cartSession = $_SESSION['cart'] ?? [];
            foreach ($cartSession as $id => $rawQty) {
                $qty = is_array($rawQty) ? ($rawQty['quantity'] ?? 1) : intval($rawQty);
                $product = $this->productModel->getById($id);
                if ($product) {
                    $product['quantity'] = $qty; 
                    $product['product_id'] = $product['id']; 
                    $cartItems[] = $product;
                    $total += $product['price'] * $qty;
                }
            }
        }

        $data = [
            'cartItems' => $cartItems,
            'total' => $total,
            'page_title' => 'Giỏ hàng'
        ];

        $this->loadView('product/cart', $data);
    }

    public function checkout() {

        // Nếu chưa login → bắt đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?page=login');
        }

        // LẤY CART TỪ DATABASE
        $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
        $cartItems = $this->cartModel->getCartItems($cart['id']);

        // Cart DB RỖNG → redirect về trang sản phẩm
        if (empty($cartItems)) {
            $this->redirect('index.php?page=product_list');
        }

        // Lưu ghi chú
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['checkout_note'] = htmlspecialchars($_POST['note'] ?? '');
        }

        $this->redirect('index.php?page=payment');
}


    public function addToCart() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid Request']);
            exit;
        }

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        $product = $this->productModel->getById($productId);
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        if (isset($_SESSION['user_id'])) {
            $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
            
            if (!$cart) {
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: Không thể tạo giỏ hàng (Missing DB Table)']);
                exit;
            }

            $result = $this->cartModel->addItem($cart['id'], $productId, $quantity, $product['price']);
        } 
        else {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            
            if (isset($_SESSION['cart'][$productId])) {
                $currentVal = $_SESSION['cart'][$productId];
                $currentQty = is_array($currentVal) ? ($currentVal['quantity'] ?? 0) : intval($currentVal);
                $_SESSION['cart'][$productId] = $currentQty + $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
            $result = true;
        }

        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Đã thêm vào giỏ hàng',
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm vào giỏ hàng']);
        }
        exit;
    }

    public function updateCart() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

        if ($productId > 0) {
            if (isset($_SESSION['user_id'])) {
                $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
                if ($cart) {
                    $this->cartModel->updateQuantity($cart['id'], $productId, $quantity);
                }
            } 
            else {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $_SESSION['cart'][$productId] = $quantity;
                }
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật thành công',
                'cartCount' => $this->getCartCount()
            ]);
        }
        exit;
    }

    public function removeFromCart() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId > 0) {
            if (isset($_SESSION['user_id'])) {
                $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
                if ($cart) {
                    $this->cartModel->removeProduct($cart['id'], $productId);
                }
            } 
            else {
                if (isset($_SESSION['cart'][$productId])) {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Đã xóa sản phẩm',
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi xóa sản phẩm']);
        }
        exit;
    }

    private function getCartCount() {
        if (isset($_SESSION['user_id'])) {
            $cart = $this->cartModel->getOrCreateActiveCart($_SESSION['user_id']);
            if (!$cart) return 0;
            
            $items = $this->cartModel->getCartItems($cart['id']);
            $count = 0;
            foreach($items as $item) $count += $item['quantity'];
            return $count;
        } 
        
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $rawQty) {
                $qty = is_array($rawQty) ? ($rawQty['quantity'] ?? 1) : intval($rawQty);
                $count += $qty;
            }
        }
        return $count;
    }
}
?>
