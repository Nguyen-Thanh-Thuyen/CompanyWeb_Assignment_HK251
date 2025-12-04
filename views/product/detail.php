<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - <?php echo $settings['company_name'] ?? 'Company'; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(substr($product['description'], 0, 150)); ?>">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .product-image-main {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .price-display {
            font-size: 2rem;
            color: #dc3545;
            font-weight: bold;
        }
        .stock-info {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .quantity-input {
            max-width: 120px;
        }
        .related-product-img {
            height: 150px;
            object-fit: cover;
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
            <li class="breadcrumb-item"><a href="index.php?page=product_list">Sản phẩm</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5">
            <?php if (!empty($product['image'])): ?>
                <img src="public/uploads/product_images/<?php echo htmlspecialchars($product['image']); ?>" 
                     class="product-image-main" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
            <?php else: ?>
                <img src="https://via.placeholder.com/400x400?text=No+Image" 
                     class="product-image-main" 
                     alt="No image">
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <!-- Category -->
            <p class="text-muted mb-2">
                <i class="bi bi-tag"></i> 
                <?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?>
            </p>

            <!-- Product Name -->
            <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>

            <!-- Price -->
            <div class="price-display mb-3">
                <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
            </div>

            <!-- Stock Info -->
            <?php if ($product['stock'] <= 0): ?>
                <div class="stock-info bg-danger text-white">
                    <i class="bi bi-x-circle"></i> <strong>Hết hàng</strong>
                </div>
            <?php elseif ($product['stock'] < 10): ?>
                <div class="stock-info bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Chỉ còn <strong><?php echo $product['stock']; ?></strong> sản phẩm
                </div>
            <?php else: ?>
                <div class="stock-info bg-success text-white">
                    <i class="bi bi-check-circle"></i> Còn hàng
                </div>
            <?php endif; ?>

            <!-- Add to Cart Form -->
            <?php if ($product['stock'] > 0): ?>
                <form id="addToCartForm" class="mb-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label class="col-form-label">Số lượng:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" 
                                   name="quantity" 
                                   class="form-control quantity-input" 
                                   value="1" 
                                   min="1" 
                                   max="<?php echo $product['stock']; ?>"
                                   required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg mb-4" disabled>
                    <i class="bi bi-x-circle"></i> Hết hàng
                </button>
            <?php endif; ?>

            <!-- Product Description -->
            <div class="mt-4">
                <h5>Mô tả sản phẩm</h5>
                <p class="text-muted">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>
            </div>

            <!-- Product Specs -->
            <div class="mt-4">
                <h5>Thông tin chi tiết</h5>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Mã sản phẩm:</strong></td>
                            <td>#<?php echo $product['id']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Danh mục:</strong></td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tình trạng:</strong></td>
                            <td>
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="badge bg-success">Còn hàng</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-5">
            <h3 class="mb-4">Sản phẩm liên quan</h3>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100">
                            <a href="index.php?page=product_detail&id=<?php echo $relatedProduct['id']; ?>">
                                <?php if (!empty($relatedProduct['image'])): ?>
                                    <img src="public/uploads/product_images/<?php echo htmlspecialchars($relatedProduct['image']); ?>" 
                                         class="card-img-top related-product-img" 
                                         alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x150?text=No+Image" 
                                         class="card-img-top related-product-img" 
                                         alt="No image">
                                <?php endif; ?>
                            </a>
                            
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="index.php?page=product_detail&id=<?php echo $relatedProduct['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($relatedProduct['name']); ?>
                                    </a>
                                </h6>
                                <p class="text-danger fw-bold mb-2">
                                    <?php echo number_format($relatedProduct['price'], 0, ',', '.'); ?>₫
                                </p>
                                <?php if ($relatedProduct['stock'] > 0): ?>
                                    <button class="btn btn-sm btn-outline-primary add-to-cart-related" 
                                            data-product-id="<?php echo $relatedProduct['id']; ?>">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Hết hàng</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
// Add to cart - Main product
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('index.php?page=add_to_cart', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: data.message,
                showConfirmButton: true,
                confirmButtonText: 'Tiếp tục mua hàng',
                showCancelButton: true,
                cancelButtonText: 'Xem giỏ hàng'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'index.php?page=cart';
                }
            });
            
            // TODO: Update cart count in header
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Có lỗi xảy ra. Vui lòng thử lại.'
        });
    });
});

// Add to cart - Related products
document.querySelectorAll('.add-to-cart-related').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        fetch('index.php?page=add_to_cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: data.message
                });
            }
        });
    });
});
</script>

</body>
</html>