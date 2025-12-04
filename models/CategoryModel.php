<?php
/**
 * CategoryModel.php
 * Model xử lý bảng categories
 */

class CategoryModel {
    private $conn;
    private $table = 'categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel::getAll() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy 1 danh mục theo ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }
}