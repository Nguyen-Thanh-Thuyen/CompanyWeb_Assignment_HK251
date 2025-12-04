<?php
require_once __DIR__ . '/../config/database.php';

class FaqModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM faqs ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM faqs WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($question, $answer) {
        $stmt = $this->conn->prepare("INSERT INTO faqs (question, answer) VALUES (:question, :answer)");
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
    }

    public function update($id, $question, $answer) {
        $stmt = $this->conn->prepare("UPDATE faqs SET question = :question, answer = :answer WHERE id = :id");
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
