<?php
require_once 'models/CommentModel.php';
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class AdminCommentController {
    // Xem danh sách bình luận (có thể lọc theo bài viết)
    public function index() {
        $model = new CommentModel();
        // $comments = $model->getAllComments();
        // require_once 'views/admin/comment_list.php';
    }

    // Xử lý xóa bình luận
    public function delete() {
        // $model->deleteComment($_GET['id']);
        // Chuyển hướng
    }

    // Bổ sung: Duyệt/Ẩn bình luận
    // public function toggle_status() { ... }
}
?>
