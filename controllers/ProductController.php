<?php
/**
 * ProductController.php
 * Controller xử lý các tác vụ liên quan đến sản phẩm
 */

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
    }

    /**
     * USER/GUEST: Hiển thị danh sách sản phẩm với search & pagination
     * Route: index.php?page=product_list&keyword=xxx&p=1
     */
    public function list() {
        // Lấy params từ URL
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 12; // 12 sản phẩm/trang
        $offset = ($currentPage - 1) * $limit;

        // Lấy dữ liệu
        $products = $this->productModel->search($keyword, $offset, $limit);
        $totalProducts = $this->productModel->count($keyword);
        $totalPages = ceil($totalProducts / $limit);

        // Lấy categories cho filter (nếu cần)
        $categories = $this->categoryModel->getAll();

        // TODO: Check nếu user là admin thì show thêm Edit/Delete buttons
        // $isAdmin = isAdmin();

        // Load view
        $data = [
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];

        $this->loadView('product/list', $data);
    }

    /**
     * USER/GUEST: Hiển thị chi tiết sản phẩm
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

        // Lấy sản phẩm liên quan
        $relatedProducts = $this->productModel->getRelated(
            $product['id'], 
            $product['category_id'], 
            4
        );

        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];

        $this->loadView('product/detail', $data);
    }

    /**
     * ADMIN: Danh sách sản phẩm (admin panel)
     * Route: index.php?page=admin_product_list&keyword=xxx&p=1
     */
    public function adminList() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $limit = 20;
        $offset = ($currentPage - 1) * $limit;

        $products = $this->productModel->getAllForAdmin($keyword, $offset, $limit);
        $totalProducts = $this->productModel->countAll($keyword);
        $totalPages = ceil($totalProducts / $limit);

        $data = [
            'products' => $products,
            'keyword' => $keyword,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];

        $this->loadView('admin/product/index', $data);
    }

    /**
     * ADMIN: Hiển thị form tạo sản phẩm
     * Route: index.php?page=admin_product_create
     */
    public function create() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        $categories = $this->categoryModel->getAll();

        $data = [
            'categories' => $categories
        ];

        $this->loadView('admin/product/create', $data);
    }

    /**
     * ADMIN: Xử lý tạo sản phẩm
     */
    private function handleCreate() {
        // Validate input
        $errors = $this->validateProductData($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_create');
            return;
        }

        // Handle file upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            
            if ($imagePath === false) {
                $_SESSION['error'] = "Upload ảnh thất bại";
                $_SESSION['old'] = $_POST;
                $this->redirect('index.php?page=admin_product_create');
                return;
            }
        }

        // Tạo sản phẩm
        $productData = [
            'category_id' => intval($_POST['category_id']),
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
            $_SESSION['error'] = "Thêm sản phẩm thất bại";
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_create');
        }
    }

    /**
     * ADMIN: Hiển thị form sửa sản phẩm
     * Route: index.php?page=admin_product_edit&id=xxx
     */
    public function edit() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     $this->redirect('index.php?page=home');
        //     return;
        // }

        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($productId <= 0) {
            $this->redirect('index.php?page=admin_product_list');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($productId);
            return;
        }

        $product = $this->productModel->getById($productId);

        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            $this->redirect('index.php?page=admin_product_list');
            return;
        }

        $categories = $this->categoryModel->getAll();

        $data = [
            'product' => $product,
            'categories' => $categories
        ];

        $this->loadView('admin/product/edit', $data);
    }

    /**
     * ADMIN: Xử lý cập nhật sản phẩm
     */
    private function handleEdit($productId) {
        // Validate input
        $errors = $this->validateProductData($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_edit&id=' . $productId);
            return;
        }

        $product = $this->productModel->getById($productId);

        // Handle file upload
        $imagePath = $product['image']; // Giữ ảnh cũ
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $this->handleImageUpload($_FILES['image']);
            
            if ($newImagePath !== false) {
                // Xóa ảnh cũ nếu có
                if ($product['image'] && file_exists('public/uploads/product_images/' . $product['image'])) {
                    unlink('public/uploads/product_images/' . $product['image']);
                }
                $imagePath = $newImagePath;
            }
        }

        // Update sản phẩm
        $productData = [
            'category_id' => intval($_POST['category_id']),
            'name' => htmlspecialchars(trim($_POST['name'])),
            'description' => htmlspecialchars(trim($_POST['description'])),
            'price' => floatval($_POST['price']),
            'stock' => intval($_POST['stock']),
            'image' => $imagePath,
            'status' => $_POST['status'] ?? 'active'
        ];

        $result = $this->productModel->update($productId, $productData);

        if ($result) {
            $_SESSION['success'] = "Cập nhật sản phẩm thành công";
            $this->redirect('index.php?page=admin_product_list');
        } else {
            $_SESSION['error'] = "Cập nhật sản phẩm thất bại";
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php?page=admin_product_edit&id=' . $productId);
        }
    }

    /**
     * ADMIN: Xóa sản phẩm (soft delete)
     * Route: index.php?page=admin_product_delete&id=xxx
     */
    public function delete() {
        // TODO: Check admin permission
        // if (!isAdmin()) {
        //     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        //     return;
        // }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=admin_product_list');
            return;
        }

        $productId = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $result = $this->productModel->delete($productId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa sản phẩm thất bại']);
        }
    }

    /**
     * Validate dữ liệu sản phẩm
     */
    private function validateProductData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Tên sản phẩm không được để trống";
        }

        if (empty($data['price']) || $data['price'] <= 0) {
            $errors['price'] = "Giá sản phẩm phải lớn hơn 0";
        }

        if (!isset($data['stock']) || $data['stock'] < 0) {
            $errors['stock'] = "Số lượng tồn kho không hợp lệ";
        }

        if (empty($data['category_id'])) {
            $errors['category_id'] = "Vui lòng chọn danh mục";
        }

        return $errors;
    }

    /**
     * Xử lý upload ảnh sản phẩm
     */
    private function handleImageUpload($file) {
        $uploadDir = 'public/uploads/product_images/';
        
        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        // Validate file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return false;
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Move file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }

        return false;
    }

    /**
     * Helper: Load view
     */
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    /**
     * Helper: Redirect
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}