<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - <?php echo $settings['company_name'] ?? 'Company'; ?></title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
        }
        .total-price {
            font-size: 1.8rem;
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h1 class="mb-4">Giỏ hàng của bạn</h1>

    <?php if (empty($cartItems)): ?>
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3">Giỏ hàng trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
            <a href="index.php?page=product_list" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="row mb-4 pb-3 border-bottom cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                                <!-- Product Image -->
                                <div class="col-md-2">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="public/uploads/product_images/<?php echo htmlspecialchars($item['image']); ?>" 
                                             class="cart-item-image" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/80x80?text=No+Image" 
                                             class="cart-item-image" 
                                             alt="No image">
                                    <?php endif; ?>
                                </div>

                                <!-- Product Info -->
                                <div class="col-md-4">
                                    <h6 class="mb-1">
                                        <a href="index.php?page=product_detail&id=<?php echo $item['product_id']; ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        Đơn giá: <?php echo number_format($item['price'], 0, ',', '.'); ?>₫
                                    </small>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary decrease-qty" type="button">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" 
                                               class="form-control quantity-input" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" 
                                               max="<?php echo $item['stock'] ?? 100; ?>"
                                               data-product-id="<?php echo $item['product_id']; ?>">
                                        <button class="btn btn-outline-secondary increase-qty" type="button">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Còn <?php echo $item['stock'] ?? 'N/A'; ?> sản phẩm</small>
                                </div>

                                <!-- Subtotal & Actions -->
                                <div class="col-md-3 text-end">
                                    <p class="fw-bold text-danger mb-2 item-subtotal">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫
                                    </p>
                                    <button class="btn btn-sm btn-outline-danger remove-item" 
                                            data-product-id="<?php echo $item['product_id']; ?>">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <a href="index.php?page=product_list" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Tóm tắt đơn hàng</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="subtotal"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng cộng:</strong>
                            <strong class="total-price" id="total"><?php echo number_format($total, 0, ',', '.'); ?>₫</strong>
                        </div>

                        <!-- Checkout Form -->
                        <form method="POST" action="index.php?page=checkout" id="checkoutForm">
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú đơn hàng (tùy chọn):</label>
                                <textarea class="form-control" 
                                          id="note" 
                                          name="note" 
                                          rows="3" 
                                          placeholder="Nhập ghi chú cho đơn hàng..."></textarea>
                            </div>

                            <!-- TODO: Check if user is logged in -->
                            <?php if (false): // Replace with isLoggedIn() ?>
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-check-circle"></i> Đặt hàng
                                </button>
                            <?php else: ?>
                                <a href="index.php?page=login" class="btn btn-warning w-100 btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đặt hàng
                                </a>
                                <small class="text-muted d-block mt-2 text-center">
                                    Bạn cần đăng nhập để có thể đặt hàng
                                </small>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Update quantity
function updateQuantity(productId, newQuantity) {
    fetch('index.php?page=update_cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update subtotal for this item
            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            const price = parseFloat(cartItem.querySelector('.text-muted').textContent.match(/[\d,]+/)[0].replace(/,/g, ''));
            const subtotal = price * newQuantity;
            cartItem.querySelector('.item-subtotal').textContent = subtotal.toLocaleString('vi-VN') + '₫';
            
            // Update total
            document.getElementById('total').textContent = data.total + '₫';
            document.getElementById('subtotal').textContent = data.total + '₫';
            
            // TODO: Update cart count in header
        } else {
            Swal.fire('Lỗi!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Lỗi!', 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    });
}

// Increase quantity
document.querySelectorAll('.increase-qty').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity-input');
        const max = parseInt(input.max);
        const newValue = parseInt(input.value) + 1;
        
        if (newValue <= max) {
            input.value = newValue;
            updateQuantity(input.dataset.productId, newValue);
        }
    });
});

// Decrease quantity
document.querySelectorAll('.decrease-qty').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity-input');
        const newValue = parseInt(input.value) - 1;
        
        if (newValue >= 1) {
            input.value = newValue;
            updateQuantity(input.dataset.productId, newValue);
        }
    });
});

// Manual input quantity
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const newValue = parseInt(this.value);
        const max = parseInt(this.max);
        
        if (newValue >= 1 && newValue <= max) {
            updateQuantity(this.dataset.productId, newValue);
        } else {
            this.value = 1;
            Swal.fire('Lỗi!', 'Số lượng không hợp lệ', 'error');
        }
    });
});

// Remove item
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Sản phẩm sẽ được xóa khỏi giỏ hàng",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('index.php?page=remove_from_cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Đã xóa!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Lỗi!', data.message, 'error');
                    }
                });
            }
        });
    });
});

// Checkout validation
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    // Add any additional validation here if needed
});
</script>

</body>
</html>