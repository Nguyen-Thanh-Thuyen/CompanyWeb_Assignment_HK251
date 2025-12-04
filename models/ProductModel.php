<?php
/**
 * ProductModel.php
 * Model xử lý CRUD cho bảng products
 */

class ProductModel {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy danh sách sản phẩm với phân trang và tìm kiếm
     * @param string $keyword - Từ khóa tìm kiếm
     * @param int $offset - Vị trí bắt đầu
     * @param int $limit - Số lượng record
     * @return array
     */
    public function search($keyword = '', $offset = 0, $limit = 12) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.status = 'active'";
            
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProductModel::search() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm tổng số sản phẩm (cho pagination)
     */
    public function count($keyword = '') {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = 'active'";
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProductModel::count() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy chi tiết 1 sản phẩm
     */
    public function getById($id) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProductModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy sản phẩm liên quan (cùng category)
     */
    public function getRelated($productId, $categoryId, $limit = 4) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE category_id = ? 
                    AND id != ? 
                    AND status = 'active' 
                    ORDER BY RAND() 
                    LIMIT ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$categoryId, $productId, $limit]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProductModel::getRelated() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADMIN: Tạo sản phẩm mới
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (category_id, name, description, price, stock, image, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['image'] ?? null,
                $data['status'] ?? 'active'
            ]);
            
            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("ProductModel::create() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Cập nhật sản phẩm
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET 
                    category_id = ?, 
                    name = ?, 
                    description = ?, 
                    price = ?, 
                    stock = ?, 
                    status = ?";
            
            $params = [
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['status'] ?? 'active'
            ];
            
            // Nếu có update ảnh
            if (isset($data['image']) && !empty($data['image'])) {
                $sql .= ", image = ?";
                $params[] = $data['image'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("ProductModel::update() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Xóa sản phẩm (soft delete)
     */
    public function delete($id) {
        try {
            $sql = "UPDATE {$this->table} SET status = 'inactive' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("ProductModel::delete() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Lấy tất cả sản phẩm (kể cả inactive) cho admin
     */
    public function getAllForAdmin($keyword = '', $offset = 0, $limit = 20) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $sql .= " ORDER BY p.id DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProductModel::getAllForAdmin() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADMIN: Đếm tổng sản phẩm (kể cả inactive)
     */
    public function countAll($keyword = '') {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProductModel::countAll() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Kiểm tra stock trước khi add to cart
     */
    public function checkStock($productId, $quantity) {
        try {
            $sql = "SELECT stock FROM {$this->table} WHERE id = ? AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$productId]);
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $product && $product['stock'] >= $quantity;
        } catch (PDOException $e) {
            error_log("ProductModel::checkStock() Error: " . $e->getMessage());
            return false;
        }
    }
}