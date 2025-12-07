<?php
// controllers/BaseController.php

class BaseController {
    
    protected $db;
    protected $viewRoot = __DIR__ . '/../views/'; 

    public function __construct($db_connection = null) {
        $this->db = $db_connection;
        // Check for Remember Me cookie on every controller initialization
        $this->checkAutoLogin();
    }

    /**
     * Check if "remember_me" cookie exists and log user in automatically
     */
    protected function checkAutoLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Only check if user is NOT logged in and cookie exists
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            
            // We query directly here to ensure it runs before any specific model is loaded
            if ($this->db) {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = :token LIMIT 1");
                $stmt->execute([':token' => $token]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && ($user['status'] ?? 'active') === 'active') {
                    // Log user in
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                }
            }
        }
    }

    /**
     * Get Website Settings from Database
     * Merges defaults with DB values to prevent errors if DB is empty
     */
    protected function getSettings() {
        // Default configuration
        $settings = [
            'company_name' => 'E-Commerce MVC',
            'logo_path' => null,
            'intro_text' => 'Khám phá những sản phẩm tốt nhất tại cửa hàng của chúng tôi.',
            'phone_number' => '',
            'email' => '',
            'address' => '',
            'facebook_url' => '#',
            'twitter_url' => '#',
            'instagram_url' => '#'
        ];

        if ($this->db) {
            try {
                // Fetch the first row from settings table
                $stmt = $this->db->prepare("SELECT * FROM website_settings LIMIT 1");
                $stmt->execute();
                $dbSettings = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Override defaults with DB values if they exist
                if ($dbSettings) {
                    $settings = array_merge($settings, $dbSettings);
                }
            } catch (Exception $e) {
                // If error (e.g. table missing), stick to defaults
            }
        }
        return $settings;
    }
    
    /**
     * Load View for Client Side (Includes Header & Footer)
     */
    protected function loadView($viewPath, $data = []) {
        // Fetch settings for the Header/Footer
        $settings = $this->getSettings(); 
        $data['settings'] = $settings;

        // Calculate Cart Count
        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $val) {
                // Handle mixed array/int session data gracefully
                $qty = is_array($val) ? ($val['quantity'] ?? 1) : intval($val);
                $cartCount += $qty;
            }
        }
        $data['cartCount'] = $cartCount; 

        extract($data);
        
        // Load Header
        $headerPath = $this->viewRoot . 'layouts/header.php';
        if (file_exists($headerPath)) require_once $headerPath;
        
        // Load Main Content
        $viewFullPath = $this->viewRoot . $viewPath . '.php';
        if (file_exists($viewFullPath)) {
            require_once $viewFullPath;
        } else {
            die("Lỗi Nạp View: " . htmlspecialchars($viewFullPath));
        }

        // Load Footer
        $footerPath = $this->viewRoot . 'layouts/footer.php';
        if (file_exists($footerPath)) require_once $footerPath;
    }

    /**
     * Load View for Admin Side (Uses Admin Layout)
     */
    protected function loadAdminView($viewPath, $data = []) {
        extract($data);
        
        // Pass the view path to the layout so it knows what to include
        $view = $viewPath; 
        
        $layoutPath = $this->viewRoot . 'layouts/admin_layout.php';
        
        if (file_exists($layoutPath)) {
            require_once $layoutPath;
        } else {
            die("Lỗi Nạp Admin Layout: " . htmlspecialchars($layoutPath));
        }
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
?>
