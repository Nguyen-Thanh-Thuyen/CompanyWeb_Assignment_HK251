<?php
class NewsModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy danh sách bài viết (có thể tìm kiếm theo từ khóa)
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Giới hạn số lượng bài viết
     * @return array Danh sách bài viết
     */
    public function getNewsList($keyword = '', $limit = 10) {
        try {
            $sql = "SELECT id, title, slug, summary, created_at, image FROM news WHERE 1=1";
            
            if (!empty($keyword)) {
                $sql .= " AND title LIKE :keyword";
            }
            $sql .= " ORDER BY created_at DESC LIMIT :limit";

            $stmt = $this->pdo->prepare($sql);
            
            if (!empty($keyword)) {
                $search = "%{$keyword}%";
                $stmt->bindParam(':keyword', $search);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Xử lý lỗi
            return [];
        }
    }

    /**
     * Lấy chi tiết một bài viết
     * @param int $id ID bài viết
     * @return array|null Chi tiết bài viết
     */
    public function getArticleById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM news WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return null;
        }
    }

    // Cần bổ sung các hàm: createArticle($data), updateArticle($id, $data), deleteArticle($id)
}
?>
