<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="rounded-circle bg-white text-primary d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 30px; font-weight: bold;">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                    <h3 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h3>
                    <span class="badge bg-light text-primary mt-2">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </div>
                
                <div class="card-body p-4">
                    <h5 class="mb-4 text-muted border-bottom pb-2">Thông tin tài khoản</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-secondary">Họ và tên:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-secondary">Email:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-secondary">Ngày tham gia:</div>
                        <div class="col-sm-8">
                            <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="index.php?page=my_orders" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam"></i> Xem đơn hàng của tôi
                        </a>
                        <a href="index.php?page=logout" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
