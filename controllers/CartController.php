<?php
/**
 * CartController.php
 * Controller xử lý giỏ hàng (cho cả guest và user)
 */

require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CartItemModel.php';

class CartController {
    private $cartModel;
    private $productModel;
    private $cartItemModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->cartModel = new CartModel($db);
        $this->productModel = new ProductModel($db);
        $this->cartItemModel = new CartItemModel($db);
    }

    /**
     * USER/GUEST: Hiển thị giỏ hàng
     * Route: index.php?page=cart
     */
    public function index() {
        // TODO: Check if user is logged in
        // if (isLoggedIn()) {
        //     $cartItems = $this->getCartItemsForUser($_SESSION['user']['id']);
        // } else {
        //     $cartItems = $this->getCartItemsFromSession();
        // }

        // Tạm thời dùng session cho guest
        $cartItems = $this->getCartItemsFromSession();
        $total = $this->calculateTotal($cartItems);

        $data = [
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->loadView('product/cart', $data);
    }

    /**
     * USER/GUEST: Thêm sản phẩm vào giỏ
     * Route: index.php?page=add_to_cart (POST)
     */
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=product_list');
            return;
        }

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($productId <= 0 || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        // Kiểm tra sản phẩm tồn tại và còn hàng
        $product = $this->productModel->getById($productId);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            return;
        }

        if ($product['stock'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không đủ số lượng']);
            return;
        }

        // TODO: Check if user is logged in
        // if (isLoggedIn()) {
        //     $result = $this->addToCartDatabase($_SESSION['user']['id'], $productId, $quantity, $product['price']);
        // } else {
        //     $result = $this->addToCartSession($productId, $quantity, $product['price'], $product['name'], $product['image']);
        // }

        // Tạm thời dùng session
        $result = $this->addToCartSession($productId, $quantity, $product['price'], $product['name'], $product['image']);

        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Thêm vào giỏ hàng thành công',
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thêm vào giỏ hàng thất bại']);
        }
    }

    /**
     * USER/GUEST: Cập nhật số lượng sản phẩm trong giỏ
     * Route: index.php?page=update_cart (POST)
     */
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=cart');
            return;
        }

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        // TODO: Check if user is logged in
        // if (isLoggedIn()) {
        //     $result = $this->updateCartDatabase($productId, $quantity);
        // } else {
        //     $result = $this->updateCartSession($productId, $quantity);
        // }

        // Tạm thời dùng session
        $result = $this->updateCartSession($productId, $quantity);

        if ($result) {
            $cartItems = $this->getCartItemsFromSession();
            $total = $this->calculateTotal($cartItems);

            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật giỏ hàng thành công',
                'total' => number_format($total, 0, ',', '.'),
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật giỏ hàng thất bại']);
        }
    }

    /**
     * USER/GUEST: Xóa sản phẩm khỏi giỏ
     * Route: index.php?page=remove_from_cart (POST)
     */
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=cart');
            return;
        }

        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        // TODO: Check if user is logged in
        // if (isLoggedIn()) {
        //     $result = $this->removeFromCartDatabase($productId);
        // } else {
        //     $result = $this->removeFromCartSession($productId);
        // }

        // Tạm thời dùng session
        $result = $this->removeFromCartSession($productId);

        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Xóa sản phẩm thành công',
                'cartCount' => $this->getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa sản phẩm thất bại']);
        }
    }

    // ==========================================
    // SESSION CART METHODS (for guests)
    // ==========================================

    /**
     * Thêm sản phẩm vào session cart (guest)
     */
    private function addToCartSession($productId, $quantity, $price, $name, $image) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu đã có sản phẩm trong giỏ, cộng thêm quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image
            ];
        }

        return true;
    }

    /**
     * Cập nhật số lượng trong session cart
     */
    private function updateCartSession($productId, $quantity) {
        if (!isset($_SESSION['cart'][$productId])) {
            return false;
        }

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }

        return true;
    }

    /**
     * Xóa sản phẩm khỏi session cart
     */
    private function removeFromCartSession($productId) {
        if (!isset($_SESSION['cart'][$productId])) {
            return false;
        }

        unset($_SESSION['cart'][$productId]);
        return true;
    }

    /**
     * Lấy cart items từ session
     */
    private function getCartItemsFromSession() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    // ==========================================
    // DATABASE CART METHODS (for logged-in users)
    // ==========================================

    /**
     * TODO: Thêm sản phẩm vào database cart (user đã login)
     */
    private function addToCartDatabase($userId, $productId, $quantity, $price) {
        $cart = $this->cartModel->getOrCreateActiveCart($userId);
        
        if (!$cart) {
            return false;
        }

        return $this->cartModel->addItem($cart['id'], $productId, $quantity, $price);
    }

    /**
     * TODO: Lấy cart items từ database (user đã login)
     */
    private function getCartItemsForUser($userId) {
        $cart = $this->cartModel->getOrCreateActiveCart($userId);
        
        if (!$cart) {
            return [];
        }

        return $this->cartModel->getCartItems($cart['id']);
    }

    /**
     * TODO: Update cart trong database
     */
    private function updateCartDatabase($cartItemId, $quantity) {
        // Implement logic update cart item trong database
        return $this->cartModel->updateItemQuantity($cartItemId, $quantity);
    }

    /**
     * TODO: Xóa item khỏi database cart
     */
    private function removeFromCartDatabase($cartItemId) {
        return $this->cartModel->removeItem($cartItemId);
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Tính tổng giá trị giỏ hàng
     */
    private function calculateTotal($cartItems) {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Đếm số lượng items trong giỏ
     */
    private function getCartCount() {
        // TODO: Check if user is logged in
        // if (isLoggedIn()) {
        //     return $this->cartItemModel->getTotalQuantityByUser($_SESSION['user']['id']);
        // }

        // Guest: đếm từ session
        $cartItems = $this->getCartItemsFromSession();
        $count = 0;
        foreach ($cartItems as $item) {
            $count += $item['quantity'];
        }
        return $count;
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