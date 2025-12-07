<?php

/**
 * ProductModel.php
 * Model xử lý CRUD cho bảng products
 */

class ProductModel
{
    private $conn;
    private $table = 'products';

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    // --- USER/GUEST METHODS ---
    
    public function getProducts($limit = 8)
    {
        try {
            $query = "SELECT 
                        p.id, p.name, p.price, p.image, p.stock, c.name as category_name, p.slug
                      FROM 
                        " . $this->table . " p
                      LEFT JOIN
                        categories c ON p.category_id = c.id
                      WHERE 
                        p.deleted_at IS NULL AND p.is_active = 1
                      ORDER BY 
                        p.created_at DESC 
                      LIMIT 
                        :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("ProductModel::getProducts() Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function search($keyword = '', $categoryId = 0, $offset = 0, $limit = 12)
    {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.deleted_at IS NULL AND p.is_active = 1";

            $binds = [];
            $paramIndex = 1;

            if (!empty($keyword)) {
                $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $binds[] = $searchTerm;
                $binds[] = $searchTerm;
            }
            
            if ($categoryId > 0) {
                $sql .= " AND p.category_id = ?";
                $binds[] = $categoryId;
            }

            $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?"; 
            
            $stmt = $this->conn->prepare($sql);

            $currentIndex = 1;
            foreach ($binds as $value) {
                $stmt->bindValue($currentIndex++, $value);
            }
            
            $stmt->bindValue($currentIndex++, $limit, PDO::PARAM_INT);
            $stmt->bindValue($currentIndex++, $offset, PDO::PARAM_INT);
            
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProductModel::search() Error: " . $e->getMessage());
            return [];
        }
    }

    public function count($keyword = '', $categoryId = 0)
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL AND is_active = 1";
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if ($categoryId > 0) {
                $sql .= " AND category_id = ?";
                $params[] = $categoryId;
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProductModel::count() Error: " . $e->getMessage());
            return 0;
        }
    }

    public function getById($id)
    {
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

    public function getRelated($productId, $categoryId, $limit = 4)
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE category_id = ? 
                    AND id != ? 
                    AND deleted_at IS NULL AND is_active = 1 
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

    // --- ADMIN CRUD METHODS ---

    public function create($data)
    {
        try {
            // 1. Tự động tạo slug từ tên sản phẩm
            $slug = $this->generateUniqueSlug($data['name']);

            // 2. Thêm slug vào câu lệnh INSERT
            $sql = "INSERT INTO {$this->table} 
                    (category_id, name, slug, description, price, stock, image, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                $data['category_id'],
                $data['name'],
                $slug, // Giá trị slug
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['image'] ?? null,
                $data['is_active'] ?? 1
            ]);

            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("ProductModel::create() Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            // Tự động cập nhật slug nếu tên thay đổi (hoặc giữ nguyên nếu muốn)
            $slug = $this->generateUniqueSlug($data['name'], $id);

            $sql = "UPDATE {$this->table} SET 
                    category_id = ?, 
                    name = ?, 
                    slug = ?,
                    description = ?, 
                    price = ?, 
                    stock = ?, 
                    is_active = ?";

            $params = [
                $data['category_id'],
                $data['name'],
                $slug,
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['is_active'] ?? 1
            ];

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

    public function delete($id)
    {
        try {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("ProductModel::delete() Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllForAdmin($keyword = '', $offset = 0, $limit = 20)
    {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE 1=1"; // Lấy cả sản phẩm đã xóa (nếu admin cần), hoặc thêm deleted_at IS NULL tùy logic

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

    public function countAll($keyword = '')
    {
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

    public function checkStock($productId, $quantity)
    {
        try {
            $sql = "SELECT stock FROM {$this->table} WHERE id = ? AND deleted_at IS NULL AND is_active = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$productId]);

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            return $product && $product['stock'] >= $quantity;
        } catch (PDOException $e) {
            error_log("ProductModel::checkStock() Error: " . $e->getMessage());
            return false;
        }
    }

    // --- HELPER: TẠO SLUG & KIỂM TRA TRÙNG LẶP ---
    private function generateUniqueSlug($name, $excludeId = null) {
        $slug = $this->createSlug($name);
        $originalSlug = $slug;
        $count = 1;

        // Kiểm tra xem slug đã tồn tại chưa
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ?";
        $params = [$slug];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}
?>
