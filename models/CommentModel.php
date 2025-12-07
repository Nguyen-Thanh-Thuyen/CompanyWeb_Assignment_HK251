<?php
// models/CommentModel.php
require_once __DIR__ . '/../config/database.php';

class CommentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CLIENT METHODS ---
    public function getByProduct($productId) {
        $query = "SELECT c.*, u.name as user_name 
                  FROM comments c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  WHERE c.product_id = :pid 
                  ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($productId, $userId, $content, $rating) {
        $query = "INSERT INTO comments (product_id, user_id, content, rating) 
                  VALUES (:pid, :uid, :content, :rating)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $productId);
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':rating', $rating);
        return $stmt->execute();
    }

    public function countByProduct($productId) {
        $query = "SELECT COUNT(*) as total FROM comments WHERE product_id = :pid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $productId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getAverageRating($productId) {
        $query = "SELECT AVG(rating) as avg_rating FROM comments WHERE product_id = :pid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $productId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($row['avg_rating'] ?? 0, 1);
    }

    // --- ADMIN METHODS (NEW) ---

    // Get all comments for Admin Table (with Product and User info)
    public function getAll($limit, $offset) {
        $query = "SELECT c.*, p.name as product_name, u.name as user_name 
                  FROM comments c
                  LEFT JOIN products p ON c.product_id = p.id
                  LEFT JOIN users u ON c.user_id = u.id
                  ORDER BY c.created_at DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM comments";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function delete($id) {
        $query = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
