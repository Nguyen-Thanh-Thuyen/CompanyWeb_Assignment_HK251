<?php
// models/ContactModel.php
require_once __DIR__ . '/../config/database.php';

class ContactModel {
    private $conn;
    private $table = "contacts";

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CLIENT SIDE ---
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (full_name, email, phone, message) VALUES (:name, :email, :phone, :msg)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data['full_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':msg', $data['message']);
        return $stmt->execute();
    }

    // --- ADMIN SIDE ---

    // Get All with Pagination
    public function getAll($limit, $offset) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
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

    // Count New (Unread) Contacts for Dashboard
    public function countNew() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = 'new'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Update Status
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Delete Contact
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
