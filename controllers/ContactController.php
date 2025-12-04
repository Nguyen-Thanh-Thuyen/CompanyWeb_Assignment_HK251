<?php
// controllers/ContactController.php

require_once ROOT_PATH . '/models/ContactModel.php';
require_once 'BaseController.php'; 

class ContactController extends BaseController {
    private $contactModel;

    public function __construct() {
        // 1. Initialize DB connection
        $database = new Database();
        $db = $database->getConnection();

        // 2. Pass DB to BaseController
        parent::__construct($db);

        // 3. Initialize Models
        $this->contactModel = new ContactModel($this->db);
    }

    /**
     * Show Contact Form
     * Route: index.php?page=contact
     */
    public function index() {
        $data = [
            'page_title' => 'Liên hệ với chúng tôi'
        ];
        // Uses BaseController to load Header + Footer + Settings automatically
        $this->loadView('client/contact', $data);
    }

    /**
     * Handle Form Submission
     * Route: index.php?page=contact_submit
     */
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=contact');
        }

        // Get and Clean Input
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $errors = [];

        // Validation
        if (empty($full_name)) $errors[] = "Vui lòng nhập họ tên.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
        if (empty($message)) $errors[] = "Vui lòng nhập nội dung.";

        if (empty($errors)) {
            $data = [
                'full_name' => htmlspecialchars($full_name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone),
                'message' => htmlspecialchars($message)
            ];
            
            if ($this->contactModel->create($data)) {
                // Success: Alert and redirect
                echo "<script>alert('Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.'); window.location.href='index.php?page=contact';</script>";
            } else {
                // Database Error
                echo "<script>alert('Lỗi hệ thống! Vui lòng thử lại sau.'); window.history.back();</script>";
            }
        } else {
            // Validation Error
            $errorString = implode("\\n", $errors);
            echo "<script>alert('$errorString'); window.history.back();</script>";
        }
    }
}
?>
