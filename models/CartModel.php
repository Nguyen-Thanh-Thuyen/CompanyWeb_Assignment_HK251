<?php
/**
 * CartModel.php
 * Model xử lý giỏ hàng (cho user đã login)
 */

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
            error_log("CartModel::getOrCreateActiveCart() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy cart theo ID
     */
    public function getById($cartId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CartModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả items trong cart (với thông tin product)
     */
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
            error_log("CartModel::getCartItems() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Kiểm tra product đã có trong cart chưa
     */
    private function getCartItem($cartId, $productId) {
        try {
            $sql = "SELECT * FROM {$this->itemsTable} WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId, $productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CartModel::getCartItem() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Thêm sản phẩm vào cart
     */
    public function addItem($cartId, $productId, $quantity, $price) {
        try {
            $existing = $this->getCartItem($cartId, $productId);
            
            if ($existing) {
                // Update quantity
                $sql = "UPDATE {$this->itemsTable} 
                        SET quantity = quantity + ? 
                        WHERE cart_id = ? AND product_id = ?";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$quantity, $cartId, $productId]);
            } else {
                // Insert mới
                $sql = "INSERT INTO {$this->itemsTable} (cart_id, product_id, quantity, price) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cartId, $productId, $quantity, $price]);
            }
        } catch (PDOException $e) {
            error_log("CartModel::addItem() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update số lượng của 1 cart item
     */
    public function updateItemQuantity($cartItemId, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeItem($cartItemId);
            }
            
            $sql = "UPDATE {$this->itemsTable} SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$quantity, $cartItemId]);
        } catch (PDOException $e) {
            error_log("CartModel::updateItemQuantity() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa 1 item khỏi cart
     */
    public function removeItem($cartItemId) {
        try {
            $sql = "DELETE FROM {$this->itemsTable} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartItemId]);
        } catch (PDOException $e) {
            error_log("CartModel::removeItem() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính tổng giá trị cart
     */
    public function getCartTotal($cartId) {
        try {
            $sql = "SELECT SUM(quantity * price) as total FROM {$this->itemsTable} WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("CartModel::getCartTotal() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Đếm số lượng items trong cart
     */
    public function countItems($cartId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->itemsTable} WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("CartModel::countItems() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Clear tất cả items trong cart
     */
    public function clearCart($cartId) {
        try {
            $sql = "DELETE FROM {$this->itemsTable} WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId]);
        } catch (PDOException $e) {
            error_log("CartModel::clearCart() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Đánh dấu cart đã checkout (chuyển status = 'ordered')
     */
    public function markAsOrdered($cartId) {
        try {
            $sql = "UPDATE {$this->table} SET status = 'ordered' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId]);
        } catch (PDOException $e) {
            error_log("CartModel::markAsOrdered() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Merge session cart vào database cart (khi guest login)
     * TODO: Sẽ implement sau khi có Auth
     */
    public function mergeSessionCart($userId, $sessionCartItems) {
        try {
            $cart = $this->getOrCreateActiveCart($userId);
            if (!$cart) return false;
            
            foreach ($sessionCartItems as $item) {
                $this->addItem(
                    $cart['id'],
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("CartModel::mergeSessionCart() Error: " . $e->getMessage());
            return false;
        }
    }
}