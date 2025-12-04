<?php
// models/OrderModel.php
require_once __DIR__ . '/../config/database.php';

class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CLIENT METHODS ---
    public function createOrder($userId, $total, $method, $note) {
        $query = "INSERT INTO orders (user_id, total_amount, payment_method, note) VALUES (:uid, :total, :method, :note)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':method', $method);
        $stmt->bindParam(':note', $note);
        if ($stmt->execute()) return $this->conn->lastInsertId();
        return false;
    }

    public function addOrderItem($orderId, $productId, $name, $price, $qty) {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (:oid, :pid, :name, :price, :qty)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':oid', $orderId);
        $stmt->bindParam(':pid', $productId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':qty', $qty);
        return $stmt->execute();
    }

    public function getOrdersByUser($userId) {
        $query = "SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($orderId, $userId = null) {
        if ($userId) {
            $query = "SELECT * FROM orders WHERE id = :oid AND user_id = :uid LIMIT 1";
        } else {
            $query = "SELECT o.*, u.name as user_name, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = :oid LIMIT 1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':oid', $orderId);
        if ($userId) $stmt->bindParam(':uid', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE order_id = :oid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':oid', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- ADMIN METHODS ---
    public function getAllOrders($limit, $offset) {
        $query = "SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function updateStatus($orderId, $status) {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }

    // [MỚI] Tính tổng doanh thu (chỉ đơn hàng đã hoàn thành)
    public function sumRevenue() {
        $query = "SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'completed'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['revenue'] ?? 0;
    }
}
