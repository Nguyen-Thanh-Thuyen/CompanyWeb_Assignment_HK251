<?php
require_once 'BaseController.php';
require_once ROOT_PATH . '/models/OrderModel.php';
require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/UserModel.php';
require_once ROOT_PATH . '/models/ContactModel.php'; // <--- ADD THIS

class AdminDashboardController extends BaseController {
    private $orderModel;
    private $productModel;
    private $userModel;
    private $contactModel; // <--- ADD THIS

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);

        $this->orderModel = new OrderModel($db);
        $this->productModel = new ProductModel($db);
        $this->userModel = new UserModel($db);
        $this->contactModel = new ContactModel($db); // <--- ADD THIS
    }

    public function index() {
        // 1. Security Check
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?page=home');
        }

        // 2. Fetch Stats
        $totalOrders = $this->orderModel->countAll();
        $totalProducts = $this->productModel->countAll('');
        $revenue = $this->orderModel->sumRevenue();
        $totalUsers = $this->userModel->countUsers();
        
        // Count New Contacts
        $newContacts = $this->contactModel->countNew(); // <--- ADD THIS

        $recentOrders = $this->orderModel->getAllOrders(5, 0); 

        $data = [
            'page_title' => 'Dashboard',
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalRevenue' => $revenue,
            'totalUsers' => $totalUsers,
            'newContacts' => $newContacts, // <--- ADD THIS
            'recentOrders' => $recentOrders
        ];

        $this->loadAdminView('admin/dashboard', $data);
    }

    protected function loadAdminView($view, $data = []) {
        extract($data);
        require_once ROOT_PATH . '/views/layouts/admin_layout.php';
    }
}
?>
