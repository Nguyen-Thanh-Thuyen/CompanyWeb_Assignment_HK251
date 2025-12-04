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
    
    /**
     * Lấy danh sách sản phẩm, giới hạn số lượng (Dùng cho Homepage).
     * KHẮC PHỤC: Sử dụng 'p.image' thay vì 'p.image_path'
     * KHẮC PHỤC: Sử dụng điều kiện Soft Delete (p.deleted_at IS NULL) hoặc p.is_active = 1
     * @param int $limit Số lượng sản phẩm muốn lấy.
     * @return array Danh sách sản phẩm
     */
    public function getProducts($limit = 8)
    {
        try {
            $query = "SELECT 
                        p.id, p.name, p.price, p.image, p.stock, c.name as category_name
                      FROM 
                        " . $this->table . " p
                      LEFT JOIN
                        categories c ON p.category_id = c.id
                      WHERE 
                        p.deleted_at IS NULL AND p.is_active = TRUE -- Chỉ lấy sản phẩm chưa xóa và đang hoạt động
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
    
    /**
     * Lấy danh sách sản phẩm với phân trang, tìm kiếm VÀ LỌC DANH MỤC.
     * ĐỒNG BỘ: Thêm $categoryId vào chữ ký phương thức
     * KHẮC PHỤC: Sử dụng điều kiện Soft Delete (p.deleted_at IS NULL)
     */
/**
 * Lấy danh sách sản phẩm với phân trang, tìm kiếm VÀ LỌC DANH MỤC.
 * SỬA LỖI: Chuyển sang bindValue/bindParam thủ công để đảm bảo LIMIT/OFFSET là INT.
 */
public function search($keyword = '', $categoryId = 0, $offset = 0, $limit = 12)
{
    try {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.deleted_at IS NULL AND p.is_active = TRUE";

        $binds = [];
        $paramIndex = 1;
        $bindMap = []; // Map để lưu vị trí bind

        if (!empty($keyword)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%{$keyword}%";
            $binds[$paramIndex++] = $searchTerm;
            $binds[$paramIndex++] = $searchTerm;
        }
        
        // Lọc theo Danh mục
        if ($categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $binds[$paramIndex++] = $categoryId;
        }

        // Thêm LIMIT và OFFSET placeholders (sẽ là hai dấu ? cuối cùng)
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?"; 
        
        $stmt = $this->conn->prepare($sql);

        // Bind các tham số WHERE/LỌC
        $currentIndex = 1;
        foreach ($binds as $value) {
            $stmt->bindValue($currentIndex++, $value);
        }
        
        // Bind LIMIT và OFFSET với kiểu INT
        $stmt->bindValue($currentIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($currentIndex++, $offset, PDO::PARAM_INT);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("ProductModel::search() Error: " . $e->getMessage());
        return [];
    }
}

    /**
     * Đếm tổng số sản phẩm (cho pagination)
     * ĐỒNG BỘ: Thêm $categoryId vào chữ ký phương thức
     * KHẮC PHỤC: Sử dụng điều kiện Soft Delete (deleted_at IS NULL)
     */
    public function count($keyword = '', $categoryId = 0) // <--- CẬP NHẬT
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL AND is_active = TRUE"; // <--- Lọc trạng thái
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Lọc theo Danh mục
            if ($categoryId > 0) { // <--- THÊM LOGIC LỌC CATEGORY
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

    /**
     * Lấy chi tiết 1 sản phẩm
     */
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

    /**
     * Lấy sản phẩm liên quan (cùng category)
     * KHẮC PHỤC: Sử dụng điều kiện Soft Delete (deleted_at IS NULL)
     */
    public function getRelated($productId, $categoryId, $limit = 4)
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE category_id = ? 
                    AND id != ? 
                    AND deleted_at IS NULL AND is_active = TRUE 
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

    /**
     * ADMIN: Tạo sản phẩm mới
     * ĐỒNG BỘ: Sử dụng 'is_active' thay vì 'status'
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (category_id, name, description, price, stock, image, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)"; // <--- CẬP NHẬT cột 'is_active'

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['image'] ?? null,
                $data['is_active'] ?? TRUE // <--- CẬP NHẬT biến 'is_active' (TRUE/FALSE)
            ]);

            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("ProductModel::create() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Cập nhật sản phẩm
     * ĐỒNG BỘ: Sử dụng 'is_active' thay vì 'status'
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET 
                    category_id = ?, 
                    name = ?, 
                    description = ?, 
                    price = ?, 
                    stock = ?, 
                    is_active = ?"; // <--- CẬP NHẬT cột 'is_active'

            $params = [
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['is_active'] ?? TRUE // <--- CẬP NHẬT biến 'is_active'
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
     * KHẮC PHỤC: Sử dụng cột 'deleted_at' thay vì 'status' = 'inactive'
     */
    public function delete($id)
    {
        try {
            // Soft delete: set deleted_at to current timestamp
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?"; // <--- CẬP NHẬT LOGIC SOFT DELETE
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("ProductModel::delete() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Lấy tất cả sản phẩm (kể cả đã xóa) cho admin
     */
    public function getAllForAdmin($keyword = '', $offset = 0, $limit = 20)
    {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE 1=1";

            $params = [];
            // ... (Logic tìm kiếm giữ nguyên) ...
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
     * ADMIN: Đếm tổng sản phẩm (kể cả đã xóa)
     */
    public function countAll($keyword = '')
    {
        // ... (Logic đếm giữ nguyên) ...
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
     * KHẮC PHỤC: Sử dụng điều kiện Soft Delete (deleted_at IS NULL)
     */
    public function checkStock($productId, $quantity)
    {
        try {
            $sql = "SELECT stock FROM {$this->table} WHERE id = ? AND deleted_at IS NULL AND is_active = TRUE"; // <--- Lọc trạng thái
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
