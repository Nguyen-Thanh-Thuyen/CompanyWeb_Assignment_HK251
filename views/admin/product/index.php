<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - Admin</title>
    
    <!-- Tabler CSS -->
    <link href="tabler-1.4.0/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="tabler-1.4.0/dist/css/tabler-icons.min.css" rel="stylesheet"/>
</head>
<body>
    <div class="page">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

        <div class="page-wrapper">
            <!-- Page Header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">Quản lý sản phẩm</h2>
                            <div class="text-muted mt-1">Tìm thấy <?php echo $totalProducts; ?> sản phẩm</div>
                        </div>
                        <div class="col-auto ms-auto">
                            <a href="index.php?page=admin_product_create" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Thêm sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Body -->
            <div class="page-body">
                <div class="container-xl">
                    <!-- Search & Filter -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="index.php" class="row g-3">
                                <input type="hidden" name="page" value="admin_product_list">
                                <div class="col-md-10">
                                    <input type="text" 
                                           name="keyword" 
                                           class="form-control" 
                                           placeholder="Tìm kiếm sản phẩm theo tên, mô tả..." 
                                           value="<?php echo htmlspecialchars($keyword); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg>
                                        Tìm kiếm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Tồn kho</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th class="w-1">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($products)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                Không tìm thấy sản phẩm nào
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td>#<?php echo $product['id']; ?></td>
                                                <td>
                                                    <?php if (!empty($product['image'])): ?>
                                                        <img src="public/uploads/product_images/<?php echo htmlspecialchars($product['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                             class="rounded" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="15" y1="8" x2="15.01" y2="8"></line><rect x="4" y="4" width="16" height="16" rx="3"></rect><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5"></path><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2"></path></svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-azure-lt">
                                                        <?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
                                                    </span>
                                                </td>
                                                <td class="text-danger fw-bold">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                                                </td>
                                                <td>
                                                    <?php if ($product['stock'] <= 0): ?>
                                                        <span class="badge bg-danger">Hết hàng</span>
                                                    <?php elseif ($product['stock'] < 10): ?>
                                                        <span class="badge bg-warning"><?php echo $product['stock']; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success"><?php echo $product['stock']; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($product['status'] === 'active'): ?>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Ẩn</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></small>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="index.php?page=admin_product_edit&id=<?php echo $product['id']; ?>" 
                                                           class="btn btn-sm btn-icon btn-primary" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Sửa">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path><path d="M16 5l3 3"></path></svg>
                                                        </a>
                                                        <button class="btn btn-sm btn-icon btn-danger delete-product" 
                                                                data-product-id="<?php echo $product['id']; ?>" 
                                                                data-bs-toggle="tooltip" 
                                                                title="Xóa">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="card-footer d-flex align-items-center">
                                <p class="m-0 text-muted">
                                    Hiển thị <?php echo count($products); ?> / <?php echo $totalProducts; ?> sản phẩm
                                </p>
                                <ul class="pagination m-0 ms-auto">
                                    <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" 
                                           href="index.php?page=admin_product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo ($currentPage - 1); ?>">
                                            Trước
                                        </a>
                                    </li>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <?php if ($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2): ?>
                                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                                <a class="page-link" 
                                                   href="index.php?page=admin_product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php elseif (abs($i - $currentPage) == 3): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" 
                                           href="index.php?page=admin_product_list&keyword=<?php echo urlencode($keyword); ?>&p=<?php echo ($currentPage + 1); ?>">
                                            Sau
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler JS -->
    <script src="tabler-1.4.0/dist/js/tabler.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Delete product
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: "Sản phẩm sẽ bị ẩn khỏi danh sách",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
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