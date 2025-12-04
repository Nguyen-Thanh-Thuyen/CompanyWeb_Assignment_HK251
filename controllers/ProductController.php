<?php
// controllers/ProductController.php

require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/CategoryModel.php';
require_once 'BaseController.php'; 

class ProductController extends BaseController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        parent::__construct($db);

        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    // =========================================================================
    // CLIENT SIDE (Public)
    // =========================================================================

    /**
     * USER/GUEST: Product List Page
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 12; 

        $totalProducts = $this->productModel->count($keyword, $categoryId);
        $totalPages = ceil($totalProducts / $limit);

        if ($totalProducts > 0 && $page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->search($keyword, $categoryId, $offset, $limit);
        $categories = $this->categoryModel->getAll();
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

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

        $this->loadView('product/list', $data);
    }

    /**
     * USER/GUEST: Product Detail Page
     */
    public function detail() {
        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($productId <= 0) {
            $this->redirect('index.php?page=product_list');
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

        $this->loadView('product/detail', $data);
    }

    // =========================================================================
    // ADMIN SIDE (Tabler Layout)
    // =========================================================================

    /**
     * ADMIN: List Products
     */
    public function adminList() {
        $this->requireAdmin();

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getAllForAdmin($keyword, $offset, $limit);
        $totalProducts = $this->productModel->countAll($keyword);
        $totalPages = ceil($totalProducts / $limit);

        $data = [
            'page_title' => 'Quản lý sản phẩm',
            'products' => $products,
            'keyword' => $keyword,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];

        $this->loadAdminView('admin/product/index', $data);
    }

    /**
     * ADMIN: Create Product
     */
    public function create() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        $categories = $this->categoryModel->getAll();
        $this->loadAdminView('admin/product/create', [
            'page_title' => 'Thêm sản phẩm mới',
            'categories' => $categories
        ]);
    }

    private function handleCreate() {
        $errors = $this->validateProductData($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_create');
        }

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath === false) {
                $_SESSION['error'] = "Upload ảnh thất bại (Sai định dạng hoặc quá lớn)";
                $_SESSION['old'] = $_POST;
                $this->redirect('index.php?page=admin_product_create');
                return;
            }
        }

        $productData = [
            'category_id' => !empty($_POST['category_id']) ? intval($_POST['category_id']) : null,
            'name' => htmlspecialchars(trim($_POST['name'])),
            'description' => htmlspecialchars(trim($_POST['description'])),
            'price' => floatval($_POST['price']),
            'stock' => intval($_POST['stock']),
            'image' => $imagePath,
            'status' => $_POST['status'] ?? 'active'
        ];

        $productId = $this->productModel->create($productData);

        if ($productId) {
            $_SESSION['success'] = "Thêm sản phẩm thành công";
            $this->redirect('index.php?page=admin_product_list');
        } else {
            $_SESSION['error'] = "Lỗi CSDL: Không thể thêm sản phẩm";
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_create');
        }
    }

    /**
     * ADMIN: Edit Product
     */
    public function edit() {
        $this->requireAdmin();

        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($productId <= 0) $this->redirect('index.php?page=admin_product_list');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($productId);
            return;
        }

        $product = $this->productModel->getById($productId);
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            $this->redirect('index.php?page=admin_product_list');
        }

        $categories = $this->categoryModel->getAll();
        $this->loadAdminView('admin/product/edit', [
            'page_title' => 'Sửa sản phẩm: ' . $product['name'],
            'product' => $product,
            'categories' => $categories
        ]);
    }

    private function handleEdit($productId) {
        $errors = $this->validateProductData($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_edit&id=' . $productId);
        }

        $product = $this->productModel->getById($productId);
        $imagePath = $product['image']; 

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $this->handleImageUpload($_FILES['image']);
            if ($newImagePath !== false) {
                // Remove old image if it's local
                if ($product['image'] && file_exists('public/uploads/product_images/' . $product['image'])) {
                    unlink('public/uploads/product_images/' . $product['image']);
                }
                $imagePath = $newImagePath;
            }
        }

        $productData = [
            'category_id' => !empty($_POST['category_id']) ? intval($_POST['category_id']) : null,
            'name' => htmlspecialchars(trim($_POST['name'])),
            'description' => htmlspecialchars(trim($_POST['description'])),
            'price' => floatval($_POST['price']),
            'stock' => intval($_POST['stock']),
            'image' => $imagePath,
            'status' => $_POST['status'] ?? 'active'
        ];

        $result = $this->productModel->update($productId, $productData);

        if ($result) {
            $_SESSION['success'] = "Cập nhật thành công";
            $this->redirect('index.php?page=admin_product_list');
        } else {
            $_SESSION['error'] = "Cập nhật thất bại";
            $this->redirect('index.php?page=admin_product_edit&id=' . $productId);
        }
    }

    /**
     * ADMIN: Delete Product (AJAX)
     */
    public function delete() {
        // Since this is often an AJAX call, we return JSON
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
             echo json_encode(['success' => false, 'message' => 'Unauthorized']); 
             return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']); 
            return;
        }

        $productId = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        // Optional: Get product to delete image file first
        $product = $this->productModel->getById($productId);
        
        $result = $this->productModel->delete($productId);

        if ($result) {
            // Delete image file logic here if desired
            echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi xóa sản phẩm']);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Security Check for Admin
     */
    private function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?page=home');
        }
    }

    /**
     * Helper to load Tabler Admin Views
     */
    protected function loadAdminView($view, $data = []) {
        extract($data);
        // This file is the "Master Layout" for admin
        require_once ROOT_PATH . '/views/layouts/admin_layout.php';
    }

    /**
     * Validation Logic
     */
    private function validateProductData($data) {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "Tên sản phẩm không được để trống";
        if (empty($data['price']) || $data['price'] <= 0) $errors['price'] = "Giá sản phẩm phải lớn hơn 0";
        if (!isset($data['stock']) || $data['stock'] < 0) $errors['stock'] = "Số lượng tồn kho không hợp lệ";
        // category_id can be null/0 if "Uncategorized" is allowed
        return $errors;
    }

    /**
     * Image Upload Logic
     */
    private function handleImageUpload($file) {
        $uploadDir = 'public/uploads/product_images/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) return false;
        
        // 5MB Limit
        if ($file['size'] > 5 * 1024 * 1024) return false;

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_') . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        return false;
    }
}
