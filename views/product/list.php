<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm - <?php echo $settings['company_name'] ?? 'Company'; ?></title>
    <meta name="description" content="Xem danh sách sản phẩm của chúng tôi">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }
        .price {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
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
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="mb-0">Danh sách sản phẩm</h1>
            <p class="text-muted">Tìm thấy <?php echo $totalProducts; ?> sản phẩm</p>
        </div>
        <div class="col-md-6">
            <!-- Search Form -->
            <form method="GET" action="index.php" class="d-flex">
                <input type="hidden" name="page" value="product_list">
                <input type="text" name="keyword" class="form-control me-2" 
                       placeholder="Tìm kiếm sản phẩm..." 
                       value="<?php echo htmlspecialchars($keyword); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Không tìm thấy sản phẩm nào.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card product-card">
                        <!-- Stock Badge -->
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="badge bg-danger stock-badge">Hết hàng</span>
                        <?php elseif ($product['stock'] < 10): ?>
                            <span class="badge bg-warning stock-badge">Còn <?php echo $product['stock']; ?></span>
                        <?php endif; ?>

                        <!-- Product Image -->
                        <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                            <?php if (!empty($product['image'])): ?>
                                <img src="public/uploads/product_images/<?php echo htmlspecialchars($product['image']); ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x200?text=No+Image" 
                                     class="card-img-top product-image" 
                                     alt="No image">
                            <?php endif; ?>
                        </a>

                        <div class="card-body">
                            <!-- Category -->
                            <small class="text-muted">
                                <?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?>
                            </small>

                            <!-- Product Name -->
                            <h5 class="card-title mt-2">
                                <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>

                            <!-- Price -->
                            <p class="price mb-2">
                                <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                            </p>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <?php if ($product['stock'] > 0): ?>
                                    <button class="btn btn-primary btn-sm add-to-cart" 
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-x-circle"></i> Hết hàng
                                    </button>
                                <?php endif; ?>

                                <!-- TODO: Admin buttons -->
                                <?php if (false): // Replace with isAdmin() ?>
                                    <div class="btn-group btn-group-sm mt-2" role="group">
                                        <a href="index.php?page=admin_product_edit&id=<?php echo $product['id']; ?>" 
                                           class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <button class="btn btn-outline-danger delete-product" 
                                                data-product-id="<?php echo $product['id']; ?>">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-5">
                <ul class="pagination justify-content-center">
                    <!-- Previous -->
                    <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" 
                           href="index.php?page=product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo ($currentPage - 1); ?>">
                            Trước
                        </a>
                    </li>

                    <!-- Pages -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" 
                                   href="index.php?page=product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php elseif (abs($i - $currentPage) == 3): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" 
                           href="index.php?page=product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo ($currentPage + 1); ?>">
                            Sau
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
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
                
                // TODO: Update cart count in header
                // updateCartCount(data.cartCount);
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
});

// Delete product (admin only)
document.querySelectorAll('.delete-product').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Sản phẩm sẽ bị ẩn khỏi danh sách",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('index.php?page=admin_product_delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}`
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
</script>

</body>
</html>