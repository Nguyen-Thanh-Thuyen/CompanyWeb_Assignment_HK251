<style>
    .cart-item-image { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
    .quantity-input { width: 80px; text-align: center; }
    .total-price { font-size: 1.8rem; color: #dc3545; font-weight: bold; }
</style>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h1 class="mb-4">Giỏ hàng của bạn</h1>

    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3">Giỏ hàng trống</h3>
            <a href="index.php?page=product_list" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="row mb-4 pb-3 border-bottom cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                                <div class="col-md-2">
                                    <?php 
                                        $img = $item['image'] ?? ''; 
                                        // Logic to handle external URL vs Local file
                                        $displayImg = 'https://placehold.co/80x80';
                                        if (strpos($img, 'http') === 0) $displayImg = $img;
                                        elseif (!empty($img)) $displayImg = 'public/uploads/product_images/' . $img;
                                    ?>
                                    <img src="<?php echo $displayImg; ?>" class="cart-item-image" alt="Img">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark"><?php echo htmlspecialchars($item['name']); ?></a></h6>
                                    <small class="text-muted"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary decrease-qty" type="button"><i class="bi bi-dash"></i></button>
                                        <input type="number" class="form-control quantity-input" value="<?php echo $item['quantity']; ?>" min="1" data-product-id="<?php echo $item['product_id']; ?>">
                                        <button class="btn btn-outline-secondary increase-qty" type="button"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="fw-bold text-danger mb-2 item-subtotal"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</p>
                                    <button class="btn btn-sm btn-outline-danger remove-item" data-product-id="<?php echo $item['product_id']; ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng cộng:</strong>
                            <strong class="total-price" id="total"><?php echo number_format($total, 0, ',', '.'); ?>₫</strong>
                        </div>

                        <form method="POST" action="index.php?page=checkout">
                            <div class="mb-3">
                                <label class="form-label">Ghi chú (tùy chọn):</label>
                                <textarea class="form-control" name="note" rows="3" placeholder="Ghi chú..."></textarea>
                            </div>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-credit-card"></i> Thanh toán
                                </button>
                            <?php else: ?>
                                <a href="index.php?page=login" class="btn btn-warning w-100 btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đặt hàng
                                </a>
                            <?php endif; ?>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
