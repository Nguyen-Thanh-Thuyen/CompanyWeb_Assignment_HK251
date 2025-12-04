<?php
require_once __DIR__ . '/../config/database.php';

class NewsModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM news");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO news (title, summary, content, image) VALUES (:title, :summary, :content, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':summary', $data['summary']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image', $data['image']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE news SET title = :title, summary = :summary, content = :content, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':summary', $data['summary']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
