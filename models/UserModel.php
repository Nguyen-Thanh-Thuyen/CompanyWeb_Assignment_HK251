<?php
// models/UserModel.php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password, $role = 'user') {
        $query = "INSERT INTO " . $this->table . " (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT id, name, email, role, created_at FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // [MỚI] Đếm số lượng khách hàng (role = user)
    public function countUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = 'user'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
