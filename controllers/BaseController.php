<?php
// controllers/BaseController.php

/**
 * Lớp cơ sở (BaseController) cho tất cả các Controller khác.
 * Cung cấp các phương thức chung như kết nối DB, tải View và tính toán dữ liệu chung (Giỏ hàng).
 */
class BaseController {
    
    /**
     * @var PDO|null $db Biến kết nối cơ sở dữ liệu PDO.
     */
    protected $db;

    /**
     * @var string Đường dẫn gốc của View.
     */
    protected $viewRoot = __DIR__ . '/../views/'; 

    /**
     * Khởi tạo BaseController và truyền kết nối DB.
     * @param PDO|null $db_connection Kết nối PDO.
     */
    public function __construct($db_connection = null) {
        $this->db = $db_connection;
    }

    /**
     * Lấy cấu hình hệ thống (Settings) từ cơ sở dữ liệu hoặc mặc định.
     * @return array
     */
    protected function getSettings() {
        // Trả về mặc định an toàn
        return [
            'company_name' => 'E-Commerce MVC',
            'logo_path' => 'logo.png',
            'intro_text' => 'Khám phá những sản phẩm tốt nhất tại cửa hàng của chúng tôi.'
        ];
    }
    
    /**
     * Tải tệp View và bao gồm Header/Footer.
     * Tự động tính toán số lượng giỏ hàng để hiển thị trên badge.
     * * @param string $viewPath Đường dẫn tương đối đến tệp view (VD: 'product/list')
     * @param array $data Dữ liệu cần truyền vào View
     */
    protected function loadView($viewPath, $data = []) {
        // 1. Lấy thông tin cấu hình chung
        $settings = $this->getSettings(); 
        $data['settings'] = $settings;

        // 2. --- TÍNH TOÁN SỐ LƯỢNG GIỎ HÀNG (MỚI) ---
        // Logic này chạy mỗi khi view được load để cập nhật số trên Header
        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // Giả sử session cart lưu dạng: [product_id => quantity]
            foreach ($_SESSION['cart'] as $qty) {
                $cartCount += (int)$qty;
            }
        }
        // Truyền biến này vào $data để header.php có thể sử dụng
        $data['cartCount'] = $cartCount; 
        // ----------------------------------------------------

        // 3. Trích xuất mảng $data thành các biến riêng biệt ($settings, $cartCount, $products...)
        extract($data);
        
        // 4. Tải Header (views/layouts/header.php)
        $headerPath = $this->viewRoot . 'layouts/header.php';
        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Lỗi Nạp View: KHÔNG tìm thấy tệp header tại: " . htmlspecialchars($headerPath));
        }
        
        // 5. Tải View chính (views/product/list.php, v.v.)
        $viewFullPath = $this->viewRoot . $viewPath . '.php';
        if (file_exists($viewFullPath)) {
            require_once $viewFullPath;
        } else {
            die("Lỗi Nạp View: KHÔNG tìm thấy tệp view tại: " . htmlspecialchars($viewFullPath));
        }

        // 6. Tải Footer (views/layouts/footer.php)
        $footerPath = $this->viewRoot . 'layouts/footer.php';
        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Lỗi Nạp View: KHÔNG tìm thấy tệp footer tại: " . htmlspecialchars($footerPath));
        }
    }

    /**
     * Hàm tiện ích để chuyển hướng trang (Redirect)
     * @param string $url URL đích (VD: 'index.php?page=home')
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
?>
