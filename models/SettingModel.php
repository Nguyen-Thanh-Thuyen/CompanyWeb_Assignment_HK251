<?php
class SettingModel {
    private $conn;
    private $table = "website_settings";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getInfo() {
        // Lấy dòng đầu tiên (vì web chỉ có 1 cấu hình chung)
        $query = "SELECT * FROM " . $this->table . " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInfo($data) {
        $query = "UPDATE " . $this->table . " SET 
                  company_name = :name, 
                  phone_number = :phone, 
                  email = :email,
                  address = :address,
                  intro_text = :intro,
                  logo_path = :logo 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Gán dữ liệu an toàn
        $stmt->bindParam(':name', $data['company_name']);
        $stmt->bindParam(':phone', $data['phone_number']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':intro', $data['intro_text']);
        $stmt->bindParam(':logo', $data['logo_path']);
        $stmt->bindParam(':id', $data['id']);
        
        return $stmt->execute();
    }
}
?>