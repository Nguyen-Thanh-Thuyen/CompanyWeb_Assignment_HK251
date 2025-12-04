<?php
require_once 'models/NewsModel.php'; 
require_once 'BaseController.php'; // Nếu bạn chưa có autoloader
class NewsController {
    // Xem danh sách bài viết (bao gồm tìm kiếm)
    public function index() {
        $model = new NewsModel();
        $keyword = $_GET['keyword'] ?? '';
        $news = $model->getNewsList($keyword);
        require_once 'views/news_list.php';
    }

    // Xem chi tiết bài viết
    public function detail() {
        $id = $_GET['id'] ?? null;
        $model = new NewsModel();
        $article = $model->getArticleById($id);
        $comments = $model->getCommentsByArticleId($id); // Có thể dùng CommentModel

        // Xử lý POST bình luận tại đây nếu có

        // require_once 'views/news_detail.php';
    }
}
?>
