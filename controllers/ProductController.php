<?php
// controllers/ProductController.php

require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/CategoryModel.php';
require_once 'BaseController.php'; 

class ProductController extends BaseController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        // 1. Initialize DB connection
        $database = new Database();
        $db = $database->getConnection();

        // 2. Pass DB to BaseController
        parent::__construct($db);

        // 3. Initialize Models
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    /**
     * USER/GUEST: Product List Page
     * Route: index.php?page=product_list
     */
    public function index() {
        // 1. Get Params
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 12; 

        // 2. Count Total
        $totalProducts = $this->productModel->count($keyword, $categoryId);
        $totalPages = ceil($totalProducts / $limit);

        if ($totalProducts > 0 && $page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $limit;

        // 3. Get Data
        $products = $this->productModel->search($keyword, $categoryId, $offset, $limit);
        $categories = $this->categoryModel->getAll();

        // 4. Check Admin Role
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

        // 5. Prepare Data
        $data = [
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'currentCategory' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'isAdmin' => $isAdmin, 
            'page_title' => 'Danh sách sản phẩm'
        ];

        // --- UPDATED PATH HERE ---
        // Points to views/product/list.php
        $this->loadView('product/list', $data);
    }

    /**
     * USER/GUEST: Product Detail Page
     * Route: index.php?page=product_detail&id=xxx
     */
    public function detail() {
        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($productId <= 0) {
            $this->redirect('index.php?page=product_list');
            return;
        }

        $product = $this->productModel->getById($productId);

        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại"; 
            $this->redirect('index.php?page=product_list');
            return;
        }

        $relatedProducts = $this->productModel->getRelated(
            $product['id'], 
            $product['category_id'], 
            4
        );

        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'page_title' => $product['name']
        ];

        // --- UPDATED PATH HERE ---
        // Points to views/product/detail.php
        $this->loadView('product/detail', $data);
    }

    // ... (Keep your Admin functions adminList, create, edit, delete as they were) ...
    // They correctly mapped to 'admin/product/index', 'admin/product/create', etc.

    /**
     * ADMIN: List Products
     */
    public function adminList() {
        // ... (auth check) ...
        // ... (logic) ...
        // $this->loadView('admin/product/index', $data); <--- This matches your tree
    }

    // Helper functions (validateProductData, handleImageUpload) remain the same
}
?>
