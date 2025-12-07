<?php
/**
 * CartModel.php
 * Model xử lý giỏ hàng (cho user đã login)
 */

require_once __DIR__ . '/../config/database.php';

class CartModel {
    private $conn;
    private $table = 'carts';
    private $itemsTable = 'cart_items';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy hoặc tạo cart active cho user
     */
    public function getOrCreateActiveCart($userId) {
        try {
            // Tìm cart active
            $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Nếu chưa có, tạo mới
            if (!$cart) {
                $sql = "INSERT INTO {$this->table} (user_id, status) VALUES (?, 'active')";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$userId]);
                
                $cartId = $this->conn->lastInsertId();
                return $this->getById($cartId);
            }
            
            return $cart;
        } catch (PDOException $e) {
            // Return null on error (e.g. table doesn't exist)
            return null;
        }
    }

    public function getById($cartId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getCartItems($cartId) {
        try {
            $sql = "SELECT ci.*, p.name, p.image, p.stock 
                    FROM {$this->itemsTable} ci 
                    INNER JOIN products p ON ci.product_id = p.id 
                    WHERE ci.cart_id = ? 
                    ORDER BY ci.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // =========================================================================
    // CORE CART OPERATIONS (Add, Update, Remove)
    // =========================================================================

    /**
     * Thêm sản phẩm vào cart (Add Button)
     */
    public function addItem($cartId, $productId, $quantity, $price) {
        try {
            // Check existence
            $sql = "SELECT id, quantity FROM {$this->itemsTable} WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId, $productId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($item) {
                // Update quantity
                $newQty = $item['quantity'] + $quantity;
                $sql = "UPDATE {$this->itemsTable} SET quantity = ? WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$newQty, $item['id']]);
            } else {
                // Insert new
                $sql = "INSERT INTO {$this->itemsTable} (cart_id, product_id, quantity, price) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cartId, $productId, $quantity, $price]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Cập nhật số lượng (Input change / Plus / Minus)
     * Using Product ID (New Method)
     */
    public function updateQuantity($cartId, $productId, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeProduct($cartId, $productId);
            }
            
            $sql = "UPDATE {$this->itemsTable} SET quantity = ? WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$quantity, $cartId, $productId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Xóa sản phẩm khỏi cart (Delete Button)
     * Using Product ID (New Method)
     */
    public function removeProduct($cartId, $productId) {
        try {
            $sql = "DELETE FROM {$this->itemsTable} WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId, $productId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function countItems($cartId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->itemsTable} WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function clearCart($cartId) {
        try {
            $sql = "DELETE FROM {$this->itemsTable} WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function markAsOrdered($cartId) {
        try {
            $sql = "UPDATE {$this->table} SET status = 'ordered' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
