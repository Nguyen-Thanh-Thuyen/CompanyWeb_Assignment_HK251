<?php
// models/UserModel.php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // =========================================================================
    // CLIENT SIDE (Login, Register, Profile)
    // =========================================================================

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create Account
    public function create($name, $email, $password, $role = 'user') {
        $query = "INSERT INTO " . $this->table . " (name, email, password, role, status) VALUES (:name, :email, :password, :role, 'active')";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    // Get User by ID (Profile)
    public function getById($id) {
        $query = "SELECT id, name, email, role, status, created_at, phone, address, avatar FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Basic Profile Info
    public function updateProfile($id, $name, $phone, $address) {
        $query = "UPDATE " . $this->table . " SET name = :name, phone = :phone, address = :address WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Update Avatar Path
    public function updateAvatar($id, $avatarPath) {
        $query = "UPDATE " . $this->table . " SET avatar = :avatar WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':avatar', $avatarPath);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get Password Hash (For verification)
    public function getPasswordHash($id) {
        $query = "SELECT password FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['password'] ?? null;
    }

    // Update Password
    public function updatePassword($id, $newHash) {
        $query = "UPDATE " . $this->table . " SET password = :pass WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pass', $newHash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Used for Admin Dashboard Stats (Counts active users)
    public function countUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = 'user'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // =========================================================================
    // ADMIN SIDE (User Management)
    // =========================================================================

    // Get All Users with Pagination
    public function getAll($limit, $offset) {
        $query = "SELECT id, name, email, role, status, created_at, phone, avatar FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count All for Pagination
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Toggle Status (Active/Disabled)
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>

