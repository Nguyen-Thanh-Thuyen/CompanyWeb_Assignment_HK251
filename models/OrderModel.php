<?php
/**
 * OrderModel.php
 * Model xử lý đơn hàng
 */

class OrderModel {
    private $conn;
    private $table = 'orders';
    private $itemsTable = 'order_items';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Tạo order từ cart (checkout)
     */
    public function createFromCart($userId, $cartId, $note = '') {
        try {
            $this->conn->beginTransaction();
            
            // 1. Lấy cart items
            $cartModel = new CartModel($this->conn);
            $cartItems = $cartModel->getCartItems($cartId);
            
            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }
            
            // 2. Tính tổng tiền
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            
            // 3. Tạo order
            $sql = "INSERT INTO {$this->table} (user_id, total, status, note) 
                    VALUES (?, ?, 'pending', ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId, $total, $note]);
            
            $orderId = $this->conn->lastInsertId();
            
            // 4. Copy cart items sang order items
            foreach ($cartItems as $item) {
                $sql = "INSERT INTO {$this->itemsTable} (order_id, product_id, quantity, price) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // TODO: Giảm stock của product
                // ProductModel::decreaseStock($item['product_id'], $item['quantity']);
            }
            
            // 5. Đánh dấu cart là ordered
            $cartModel->markAsOrdered($cartId);
            
            $this->conn->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("OrderModel::createFromCart() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy chi tiết order
     */
    public function getById($orderId) {
        try {
            $sql = "SELECT o.*, u.username, u.email 
                    FROM {$this->table} o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    WHERE o.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy order items
     */
    public function getOrderItems($orderId) {
        try {
            $sql = "SELECT oi.*, p.name, p.image 
                    FROM {$this->itemsTable} oi 
                    INNER JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getOrderItems() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách orders của user
     */
    public function getByUserId($userId, $limit = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getByUserId() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADMIN: Lấy tất cả orders
     */
    public function getAllForAdmin($status = '', $limit = 20, $offset = 0) {
        try {
            $sql = "SELECT o.*, u.username, u.email 
                    FROM {$this->table} o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($status)) {
                $sql .= " AND o.status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getAllForAdmin() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADMIN: Đếm tổng orders
     */
    public function countAll($status = '') {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if (!empty($status)) {
                $sql .= " AND status = ?";
                $params[] = $status;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("OrderModel::countAll() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ADMIN: Cập nhật status order
     */
    public function updateStatus($orderId, $status) {
        try {
            $validStatuses = ['pending', 'confirmed', 'shipping', 'completed', 'canceled'];
            
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$status, $orderId]);
        } catch (PDOException $e) {
            error_log("OrderModel::updateStatus() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thống kê orders theo status (dành cho admin dashboard)
     */
    public function getStatsByStatus() {
        try {
            $sql = "SELECT status, COUNT(*) as count, SUM(total) as total_amount 
                    FROM {$this->table} 
                    GROUP BY status";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getStatsByStatus() Error: " . $e->getMessage());
            return [];
        }
    }
}