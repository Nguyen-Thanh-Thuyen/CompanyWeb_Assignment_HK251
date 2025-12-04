<?php
class FaqModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả các câu hỏi/đáp, sắp xếp theo thứ tự ưu tiên
     * @return array Danh sách FAQ
     */
    public function getAllFaqs() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC, id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Xử lý lỗi
            return [];
        }
    }

    /**
     * Thêm một câu hỏi/đáp mới
     * @param string $question Câu hỏi
     * @param string $answer Câu trả lời
     * @return bool True nếu thành công
     */
    public function addFaq($question, $answer) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO faqs (question, answer, created_at) VALUES (:q, :a, NOW())");
            $stmt->bindParam(':q', $question);
            $stmt->bindParam(':a', $answer);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Xử lý lỗi
            return false;
        }
    }

    // Cần bổ sung các hàm: getFaqById($id), updateFaq($id, $q, $a), deleteFaq($id)
}
?>
