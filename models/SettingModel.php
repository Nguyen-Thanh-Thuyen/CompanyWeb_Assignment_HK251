<?php
// models/SettingModel.php
require_once __DIR__ . '/../config/database.php';

class SettingModel {
    private $conn;
    private $table = "website_settings";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getInfo() {
        $query = "SELECT * FROM " . $this->table . " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInfo($data) {
        // Update query to include social links
        $query = "UPDATE " . $this->table . " SET 
                  company_name = :name, 
                  phone_number = :phone, 
                  email = :email,
                  address = :address,
                  intro_text = :intro,
                  logo_path = :logo,
                  facebook_url = :fb,
                  twitter_url = :tw,
                  instagram_url = :insta
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['company_name']);
        $stmt->bindParam(':phone', $data['phone_number']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':intro', $data['intro_text']);
        $stmt->bindParam(':logo', $data['logo_path']);
        // Bind new fields
        $stmt->bindParam(':fb', $data['facebook_url']);
        $stmt->bindParam(':tw', $data['twitter_url']);
        $stmt->bindParam(':insta', $data['instagram_url']);
        
        $stmt->bindParam(':id', $data['id']);
        
        return $stmt->execute();
    }
}
?>
