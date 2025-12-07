<!-- views/product/cart.php -->

<style>
    .cart-item-image { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
    .quantity-input { width: 60px; text-align: center; }
    .total-price { font-size: 1.5rem; color: #dc3545; font-weight: bold; }
    /* Hide spin buttons for number input */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5 bg-light rounded">
            <i class="bi bi-cart-x text-secondary" style="font-size: 4rem;"></i>
            <h3 class="mt-3">Giỏ hàng trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="index.php?page=product_list" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Left Column: Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 100px;" class="ps-3">Sản phẩm</th>
                                        <th></th>
                                        <th style="width: 120px;">Đơn giá</th>
                                        <th style="width: 140px;">Số lượng</th>
                                        <th style="width: 120px;" class="text-end">Thành tiền</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        
                                        <?php 
                                            // --- ROBUST DATA HANDLING ---
                                            // Ensure quantity is an integer
                                            $safeQty = is_array($item['quantity']) ? ($item['quantity']['quantity'] ?? 1) : $item['quantity'];
                                            $safeQty = intval($safeQty);
                                            $safePrice = floatval($item['price']);
                                            $lineTotal = $safePrice * $safeQty;
                                            
                                            // Ensure product_id is set. 
                                            // Controller should set 'product_id', but fallback to 'id' if needed.
                                            $productId = $item['product_id'] ?? $item['id'];

                                            // --- ROBUST IMAGE LOGIC ---
                                            $img = $item['image'] ?? $item['image_path'] ?? ''; 
                                            $displayImg = 'https://placehold.co/80x80?text=No+Image';

                                            if (!empty($img) && strpos($img, 'http') === 0) {
                                                $displayImg = $img;
                                            } elseif (!empty($img) && file_exists('public/uploads/product_images/' . $img)) {
                                                $displayImg = 'public/uploads/product_images/' . $img;
                                            } elseif (!empty($img) && file_exists('public/uploads/' . $img)) {
                                                $displayImg = 'public/uploads/' . $img;
                                            }
                                        ?>

                                        <tr class="cart-item" id="item-<?php echo $productId; ?>">
                                            <td class="ps-3">
                                                <img src="<?php echo htmlspecialchars($displayImg); ?>" class="cart-item-image border" alt="Img">
                                            </td>
                                            <td>
                                                <a href="index.php?page=product_detail&id=<?php echo $productId; ?>" class="text-decoration-none fw-bold text-dark">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </a>
                                                <div class="small text-muted">Mã: #<?php echo $productId; ?></div>
                                            </td>
                                            <td><?php echo number_format($safePrice, 0, ',', '.'); ?>₫</td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <!-- Decrease Button -->
                                                    <button class="btn btn-outline-secondary decrease-qty" type="button" data-id="<?php echo $productId; ?>">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    
                                                    <!-- Input Field -->
                                                    <input type="number" class="form-control quantity-input" 
                                                           value="<?php echo $safeQty; ?>" 
                                                           min="1" 
                                                           max="<?php echo $item['stock'] ?? 100; ?>"
                                                           data-id="<?php echo $productId; ?>">
                                                    
                                                    <!-- Increase Button -->
                                                    <button class="btn btn-outline-secondary increase-qty" type="button" data-id="<?php echo $productId; ?>">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-primary item-subtotal">
                                                <?php echo number_format($lineTotal, 0, ',', '.'); ?>₫
                                            </td>
                                            <td class="text-end pe-3">
                                                <!-- Delete Button -->
                                                <button class="btn btn-link text-danger p-0 remove-item" data-id="<?php echo $productId; ?>" title="Xóa">
                                                    <i class="bi bi-trash fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=product_list" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <!-- Right Column: Summary & Checkout -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm border-0 sticky-top" style="top: 80px; z-index: 1;">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-bold"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 mb-0">Tổng cộng:</span>
                            <span class="total-price text-danger"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>

                        <form method="POST" action="index.php?page=checkout">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">GHI CHÚ ĐƠN HÀNG</label>
                                <textarea class="form-control bg-light" name="note" rows="3" placeholder="Ví dụ: Giao hàng giờ hành chính..."></textarea>
                            </div>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                    TIẾN HÀNH THANH TOÁN <i class="bi bi-arrow-right"></i>
                                </button>
                            <?php else: ?>
                                <a href="index.php?page=login" class="btn btn-warning w-100 py-2 fw-bold text-dark">
                                    ĐĂNG NHẬP ĐỂ THANH TOÁN
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

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Helper: Update Cart Request
    function updateCartRequest(productId, quantity) {
        // Show a loading indicator on the input or overlay if desired
        // For now, just send the request
        fetch('index.php?page=update_cart', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Ideally, update the specific row's total and grand total via JS to avoid reload
                // But reloading is safer to ensure PHP re-calculates everything (discounts, taxes, etc.)
                location.reload(); 
            } else {
                Swal.fire('Thông báo', data.message, 'warning');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Lỗi', 'Lỗi kết nối server', 'error');
        });
    }

    // 1. REMOVE ITEM LOGIC
    // Use event delegation for better performance or just simple loop
    const removeButtons = document.querySelectorAll('.remove-item');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id; // Use data-id

            Swal.fire({
                title: 'Xóa sản phẩm?',
                text: "Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa bỏ',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?page=remove_from_cart', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `product_id=${productId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa!',
                                text: data.message,
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire('Lỗi', 'Lỗi kết nối server', 'error'));
                }
            });
        });
    });

    // 2. INCREASE QUANTITY
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            // Find the input in the same group (parent's parent or sibling search)
            // Structure: .input-group > btn, input, btn
            const input = this.parentElement.querySelector('.quantity-input');
            if(!input) return;

            const productId = input.dataset.id;
            const max = parseInt(input.max) || 100;
            let val = parseInt(input.value);

            if (val < max) {
                // Optimistic UI update (optional)
                // input.value = val + 1; 
                updateCartRequest(productId, val + 1);
            } else {
                Swal.fire('Thông báo', 'Đã đạt giới hạn số lượng trong kho', 'info');
            }
        });
    });

    // 3. DECREASE QUANTITY
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if(!input) return;

            const productId = input.dataset.id;
            let val = parseInt(input.value);
            
            if (val > 1) {
                updateCartRequest(productId, val - 1);
            } else {
                // If quantity is 1, ask to remove
                Swal.fire({
                    title: 'Xóa sản phẩm?',
                    text: "Giảm số lượng về 0 sẽ xóa sản phẩm khỏi giỏ hàng.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Call remove endpoint
                         fetch('index.php?page=remove_from_cart', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: `product_id=${productId}`
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });
    
    // 4. MANUAL INPUT CHANGE
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.id;
            const max = parseInt(this.max) || 100;
            let val = parseInt(this.value);
            
            if(isNaN(val) || val < 1) val = 1;
            if(val > max) val = max;
            
            // Update input value immediately to valid number
            this.value = val;

            updateCartRequest(productId, val);
        });
    });

});
</script>
