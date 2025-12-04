<?php
/**
 * UserModel.php
 * Model xử lý bảng users
 * TODO: Phần Auth sẽ implement sau
 */

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * TODO: Đăng ký user mới
     */
    public function register($data) {
        // TODO: Implement register logic
        // - Validate username/email unique
        // - Hash password với password_hash()
        // - Insert vào database
        // - Return user_id hoặc false
    }

    /**
     * TODO: Đăng nhập
     */
    public function login($username, $password) {
        // TODO: Implement login logic
        // - Tìm user theo username hoặc email
        // - Verify password với password_verify()
        // - Return user data hoặc false
    }

    /**
     * Lấy user theo ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT id, username, email, full_name, phone, address, avatar, role, status, created_at 
                    FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UserModel::getById() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy user theo username
     */
    public function getByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UserModel::getByUsername() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy user theo email
     */
    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UserModel::getByEmail() Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * TODO: Cập nhật thông tin user
     */
    public function update($userId, $data) {
        // TODO: Implement update profile
        // - Validate data
        // - Update full_name, phone, address, etc
        // - Return true/false
    }

    /**
     * TODO: Đổi password
     */
    public function changePassword($userId, $oldPassword, $newPassword) {
        // TODO: Implement change password
        // - Verify old password
        // - Hash new password
        // - Update database
    }

    /**
     * TODO: Upload avatar
     */
    public function updateAvatar($userId, $avatarPath) {
        // TODO: Implement update avatar
        // - Validate file upload
        // - Move file to uploads/avatars/
        // - Update database
    }

    /**
     * ADMIN: Lấy tất cả users với phân trang
     */
    public function getAllForAdmin($keyword = '', $offset = 0, $limit = 20) {
        try {
            $sql = "SELECT id, username, email, full_name, role, status, created_at 
                    FROM {$this->table} WHERE 1=1";
            
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UserModel::getAllForAdmin() Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADMIN: Đếm tổng users
     */
    public function countAll($keyword = '') {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("UserModel::countAll() Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ADMIN: Ban/Unban user
     */
    public function updateStatus($userId, $status) {
        try {
            $validStatuses = ['active', 'banned'];
            
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$status, $userId]);
        } catch (PDOException $e) {
            error_log("UserModel::updateStatus() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Reset password user
     */
    public function resetPassword($userId, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            error_log("UserModel::resetPassword() Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ADMIN: Xóa user
     */
    public function delete($userId) {
        try {
            // Không cho xóa admin
            $user = $this->getById($userId);
            if ($user && $user['role'] === 'admin') {
                return false;
            }
            
            $sql = "DELETE FROM {$this->table} WHERE id = ? AND role != 'admin'";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("UserModel::delete() Error: " . $e->getMessage());
            return false;
        }
    }
}