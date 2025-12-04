<?php
// Định nghĩa đường dẫn vật lý trên ổ cứng (D:\XAMPP\htdocs\WEB)
define('ROOT_PATH', dirname(__DIR__)); 

// Định nghĩa URL gốc để gọi CSS/JS/Ảnh (http://localhost/WEB)
define('BASE_URL', 'http://localhost/BTL/CompanyWeb_Assignment_HK251');

// Thư mục chứa ảnh upload
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');

require_once 'database.php';

$db = new Database();
$pdo = $db->getConnection();
?>