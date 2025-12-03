<?php
// D:\XAMPP\htdocs\WEB\models\ContactModel.php
class ContactModel {
    private $conn;
    private $table = "contacts";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. (Khách) Lưu liên hệ mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (full_name, email, phone, message) VALUES (:name, :email, :phone, :msg)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['full_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':msg', $data['message']);
        
        return $stmt->execute();
    }

    // 2. (Admin) Lấy tất cả liên hệ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. (Admin) Đổi trạng thái (Đã đọc / Đã phản hồi)
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 4. (Admin) Xóa liên hệ
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>