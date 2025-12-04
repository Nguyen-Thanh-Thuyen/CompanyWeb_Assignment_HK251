<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/FaqModel.php';

class FaqController extends BaseController {
    private $faqModel;

    public function __construct() {
        $database = new Database();
        $this->faqModel = new FaqModel($database->getConnection());
    }

    public function index() {
        $faqs = $this->faqModel->getAll();
        $this->loadView('faq/index', ['faqs' => $faqs, 'page_title' => 'Câu hỏi thường gặp']);
    }
}
?>
