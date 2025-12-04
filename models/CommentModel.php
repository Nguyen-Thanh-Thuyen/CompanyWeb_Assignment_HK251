<?php
class CommentModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Thêm một bình luận mới vào bài viết
     * @param int $articleId ID bài viết
     * @param string $author Tên người bình luận
     * @param string $content Nội dung bình luận
     * @return bool True nếu thành công
     */
    public function addComment($articleId, $author, $content) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO comments (article_id, author, content, created_at, status) 
                VALUES (:aid, :author, :content, NOW(), 'pending')
            ");
            $stmt->bindParam(':aid', $articleId, PDO::PARAM_INT);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':content', $content);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Xử lý lỗi
            return false;
        }
    }

    /**
     * Lấy danh sách bình luận (dành cho Admin)
     * @return array Danh sách bình luận kèm thông tin bài viết
     */
    public function getAllCommentsForAdmin() {
        try {
            $sql = "SELECT c.*, n.title as article_title FROM comments c 
                    JOIN news n ON c.article_id = n.id 
                    ORDER BY c.created_at DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return [];
        }
    }
    
    // Cần bổ sung hàm: deleteComment($id), updateCommentStatus($id, $status)
}
?>
