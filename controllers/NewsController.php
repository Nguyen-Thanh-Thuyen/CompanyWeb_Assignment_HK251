<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/NewsModel.php';

class NewsController extends BaseController {
    private $newsModel;

    public function __construct() {
        $database = new Database();
        $this->newsModel = new NewsModel($database->getConnection());
    }

    public function index() {
        $news = $this->newsModel->getAll(10, 0);
        $this->loadView('news/index', ['news_list' => $news, 'page_title' => 'Tin tức']);
    }

    public function detail() {
        $id = $_GET['id'] ?? 0;
        $news = $this->newsModel->getById($id);
        if (!$news) $this->redirect('index.php?page=news_list');
        $this->loadView('news/detail', ['news' => $news, 'page_title' => $news['title']]);
    }
}
?>
