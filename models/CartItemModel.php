<?php
/**
 * CartItemModel.php
 * Model xử lý chi tiết cart_items (nếu cần tách riêng logic)
 * Note: Hiện tại logic cart items đã được xử lý trong CartModel
 * File này để dự phòng nếu cần mở rộng thêm tính năng
 */

class CartItemModel {
    private $conn;
    private $table = 'cart_items';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy cart item theo ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT ci.*, p.name, p.image, p.stock 
                    FROM {$this->table} ci 
                    INNER JOIN products p ON ci.product_id = p.id 
                    WHERE ci.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CartItemModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra cart item có thuộc về user không
     * (Dùng cho security check khi update/delete)
     */
    public function belongsToUser($cartItemId, $userId) {
        try {
            $sql = "SELECT ci.id 
                    FROM {$this->table} ci 
                    INNER JOIN carts c ON ci.cart_id = c.id 
                    WHERE ci.id = ? AND c.user_id = ? AND c.status = 'active'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartItemId, $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            error_log("CartItemModel::belongsToUser() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate quantity với stock
     */
    public function validateQuantity($cartItemId, $newQuantity) {
        try {
            $sql = "SELECT p.stock 
                    FROM {$this->table} ci 
                    INNER JOIN products p ON ci.product_id = p.id 
                    WHERE ci.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cartItemId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result && $result['stock'] >= $newQuantity;
        } catch (PDOException $e) {
            error_log("CartItemModel::validateQuantity() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update quantity (với validation)
     */
    public function updateQuantity($cartItemId, $newQuantity) {
        try {
            // Validate quantity > 0
            if ($newQuantity <= 0) {
                return false;
            }
            
            // Validate với stock
            if (!$this->validateQuantity($cartItemId, $newQuantity)) {
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$newQuantity, $cartItemId]);
        } catch (PDOException $e) {
            error_log("CartItemModel::updateQuantity() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete cart item
     */
    public function delete($cartItemId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartItemId]);
        } catch (PDOException $e) {
            error_log("CartItemModel::delete() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tổng số lượng items của 1 user (hiển thị badge trên header)
     */
    public function getTotalQuantityByUser($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(ci.quantity), 0) as total 
                    FROM {$this->table} ci 
                    INNER JOIN carts c ON ci.cart_id = c.id 
                    WHERE c.user_id = ? AND c.status = 'active'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("CartItemModel::getTotalQuantityByUser() Error: " . $e->getMessage());
            return 0;
        }
    }
}