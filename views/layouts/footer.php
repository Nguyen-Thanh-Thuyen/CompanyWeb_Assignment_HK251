<?php 
// views/layouts/footer.php
$settings = $settings ?? ['company_name' => 'E-Commerce MVC']; 
?>

<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">
                    <?php echo htmlspecialchars($settings['company_name']); ?>
                </h5>
                <p><?php echo htmlspecialchars($settings['intro_text'] ?? 'Chất lượng tạo nên khác biệt.'); ?></p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Sản phẩm</h5>
                <p><a href="index.php?page=product_list" class="text-white" style="text-decoration: none;">Máy tính</a></p>
                <p><a href="index.php?page=product_list" class="text-white" style="text-decoration: none;">Thiết bị di động</a></p>
                <p><a href="index.php?page=product_list" class="text-white" style="text-decoration: none;">Phụ kiện</a></p>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Liên kết</h5>
                <p><a href="index.php?page=home" class="text-white" style="text-decoration: none;">Trang chủ</a></p>
                <p><a href="index.php?page=cart" class="text-white" style="text-decoration: none;">Giỏ hàng</a></p>
                <p><a href="index.php?page=contact" class="text-white" style="text-decoration: none;">Hỗ trợ</a></p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Liên hệ</h5>
                <p>
                    <i class="bi bi-house-door-fill me-3"></i> 
                    <?php echo htmlspecialchars($settings['address'] ?? 'TPHCM, Việt Nam'); ?>
                </p>
                <p>
                    <i class="bi bi-envelope-fill me-3"></i> 
                    <?php echo htmlspecialchars($settings['email'] ?? 'info@ecommerce.com'); ?>
                </p>
                <p>
                    <i class="bi bi-telephone-fill me-3"></i> 
                    <?php echo htmlspecialchars($settings['phone_number'] ?? '+84 865 167 913'); ?>
                </p>
            </div>
        </div>

        <hr class="mb-4">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>Copyright &copy;<?php echo date('Y'); ?>. All Rights Reserved by 
                    <a href="index.php?page=home" style="text-decoration: none;">
                        <strong class="text-warning"><?php echo htmlspecialchars($settings['company_name']); ?></strong>
                    </a>
                </p>
            </div>
            
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item">
                            <a href="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>" class="btn btn-outline-light btn-floating m-1" target="_blank">
                                <i class="bi bi-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="<?php echo htmlspecialchars($settings['twitter_url'] ?? '#'); ?>" class="btn btn-outline-light btn-floating m-1" target="_blank">
                                <i class="bi bi-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>" class="btn btn-outline-light btn-floating m-1" target="_blank">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
