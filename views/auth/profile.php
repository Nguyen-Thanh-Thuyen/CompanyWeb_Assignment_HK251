<!-- views/auth/profile.php -->
<div class="container my-5">
    
    <!-- Notifications -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Left Column: Avatar & Sidebar -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="mb-4 position-relative d-inline-block mx-auto">

<?php 
                        // 1. Get the avatar filename from database (e.g., "user_7_123.jpg")
                        $avatarFile = $user['avatar'] ?? null;

                        // 2. Define Project Root
                        // Current file is in views/auth, so go up 2 levels to reach root
                        $projectRoot = dirname(__DIR__, 2);

                        $hasAvatar = false;
                        $avatarSrc = ''; 

                        if ($avatarFile) {
                            // Define the folder relative to index.php
                            $folder = 'uploads/avatars/';

                            // Construct physical path for file_exists check
                            // e.g. D:/xampp/htdocs/Project/uploads/avatars/user_7.jpg
                            $physicalPath = $projectRoot . '/' . $folder . $avatarFile;

                            // Check if file exists physically
                            if (file_exists($physicalPath)) {
                                $hasAvatar = true;
                                // SRC for the <img> tag (relative to public web root/index.php)
                                $avatarSrc = $folder . $avatarFile;
                            }
                        }
                    ?>

                    <?php if ($hasAvatar): ?>
                        <img src="<?php echo htmlspecialchars($avatarSrc); ?>"
                             class="rounded-circle border shadow-sm"
                             alt="Avatar"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto"
                             style="width: 150px; height: 150px; font-size: 64px;">
                            <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle border shadow-sm" 
                            data-bs-toggle="modal" data-bs-target="#avatarModal" 
                            title="Đổi ảnh đại diện">
                        <i class="bi bi-camera-fill text-dark"></i>
                    </button>                </div>
                
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                <p class="text-muted mb-4"><?php echo htmlspecialchars($user['email']); ?></p>

                <div class="d-grid gap-2">
                    <a href="index.php?page=my_orders" class="btn btn-outline-primary">
                        <i class="bi bi-box-seam me-2"></i> Đơn hàng của tôi
                    </a>
                    <a href="index.php?page=logout" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Forms -->
        <div class="col-lg-8">

            <!-- 1. Edit Info Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-person-lines-fill me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=update_profile" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control bg-light"
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled readonly>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Email đăng nhập không thể thay đổi.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control"
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="09xxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                <input type="text" name="address" class="form-control"
                                       value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" 
                                       placeholder="Số nhà, đường, phường/xã...">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. Change Password Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=change_password" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="old_password" class="form-control" required 
                                   placeholder="Nhập mật khẩu cũ để xác nhận">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" required minlength="6"
                                       placeholder="Ít nhất 6 ký tự">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                                <input type="password" name="confirm_password" class="form-control" required minlength="6"
                                       placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning text-dark px-4">
                                <i class="bi bi-key me-2"></i>Đổi mật khẩu
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="index.php?page=upload_avatar" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật ảnh đại diện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn ảnh (JPG, PNG, GIF)</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*" required>
                        <div class="form-text text-muted">Dung lượng tối đa 2MB.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>

            </div>
        </form>
    </div>
</div>

