<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm - Admin</title>
    
    <!-- Tabler CSS -->
    <link href="tabler-1.4.0/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="tabler-1.4.0/dist/css/tabler-icons.min.css" rel="stylesheet"/>
    
    <style>
        .image-preview {
            max-width: 300px;
            max-height: 300px;
            margin-top: 10px;
            display: none;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
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
                            <div class="page-pretitle">Quản lý sản phẩm</div>
                            <h2 class="page-title">Thêm sản phẩm mới</h2>
                        </div>
                        <div class="col-auto ms-auto">
                            <a href="index.php?page=admin_product_list" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                                Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" action="index.php?page=admin_product_create" enctype="multipart/form-data" id="productForm">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Thông tin sản phẩm</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Display errors -->
                                        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <div class="d-flex">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="alert-title">Có lỗi xảy ra!</h4>
                                                        <ul class="mb-0">
                                                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                                                <li><?php echo htmlspecialchars($error); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                            </div>
                                            <?php unset($_SESSION['errors']); ?>
                                        <?php endif; ?>

                                        <!-- Product Name -->
                                        <div class="mb-3">
                                            <label class="form-label required">Tên sản phẩm</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="name" 
                                                   placeholder="Nhập tên sản phẩm" 
                                                   value="<?php echo htmlspecialchars($_SESSION['old']['name'] ?? ''); ?>"
                                                   required>
                                            <small class="form-hint">Tên sản phẩm hiển thị cho khách hàng</small>
                                        </div>

                                        <!-- Category -->
                                        <div class="mb-3">
                                            <label class="form-label required">Danh mục</label>
                                            <select class="form-select" name="category_id" required>
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo (isset($_SESSION['old']['category_id']) && $_SESSION['old']['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả sản phẩm</label>
                                            <textarea class="form-control" 
                                                      name="description" 
                                                      rows="5" 
                                                      placeholder="Nhập mô tả chi tiết về sản phẩm"><?php echo htmlspecialchars($_SESSION['old']['description'] ?? ''); ?></textarea>
                                            <small class="form-hint">Mô tả chi tiết giúp khách hàng hiểu rõ hơn về sản phẩm</small>
                                        </div>

                                        <div class="row">
                                            <!-- Price -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Giá bán (₫)</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="price" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       step="1000"
                                                       value="<?php echo htmlspecialchars($_SESSION['old']['price'] ?? ''); ?>"
                                                       required>
                                            </div>

                                            <!-- Stock -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Số lượng tồn kho</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="stock" 
                                                       placeholder="0" 
                                                       min="0"
                                                       value="<?php echo htmlspecialchars($_SESSION['old']['stock'] ?? '0'); ?>"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái</label>
                                            <div>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="status" 
                                                           value="active"
                                                           <?php echo (!isset($_SESSION['old']['status']) || $_SESSION['old']['status'] == 'active') ? 'checked' : ''; ?>>
                                                    <span class="form-check-label">Hoạt động</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="status" 
                                                           value="inactive"
                                                           <?php echo (isset($_SESSION['old']['status']) && $_SESSION['old']['status'] == 'inactive') ? 'checked' : ''; ?>>
                                                    <span class="form-check-label">Ẩn</span>
                                                </label>
                                            </div>
                                            <small class="form-hint">Sản phẩm "Ẩn" sẽ không hiển thị trên trang chủ</small>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="mb-3">
                                            <label class="form-label">Hình ảnh sản phẩm</label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   name="image" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                                   id="imageInput">
                                            <small class="form-hint">
                                                Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB
                                            </small>
                                            
                                            <!-- Image Preview -->
                                            <div>
                                                <img id="imagePreview" class="image-preview" alt="Preview">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer text-end">
                                        <button type="reset" class="btn btn-link">Xóa form</button>
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            Thêm sản phẩm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Sidebar Help -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Hướng dẫn</h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Lưu ý khi thêm sản phẩm</h4>
                                    <ul class="list-unstyled space-y-1">
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 11 12 14 20 6"></polyline><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>
                                            Tên sản phẩm nên ngắn gọn, dễ hiểu
                                        </li>
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 11 12 14 20 6"></polyline><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>
                                            Mô tả chi tiết giúp tăng khả năng bán hàng
                                        </li>
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 11 12 14 20 6"></polyline><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>
                                            Hình ảnh rõ ràng, chất lượng cao
                                        </li>
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 11 12 14 20 6"></polyline><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>
                                            Giá cả cạnh tranh với thị trường
                                        </li>
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 11 12 14 20 6"></polyline><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>
                                            Cập nhật tồn kho chính xác
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-status-top bg-blue"></div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="8" x2="12.01" y2="8"></line><polyline points="11 12 12 12 12 16 13 16"></polyline></svg>
                                        Thông tin
                                    </h4>
                                    <p class="text-muted">Sau khi thêm sản phẩm thành công, bạn có thể chỉnh sửa thông tin bất kỳ lúc nào.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear old session data -->
    <?php unset($_SESSION['old']); ?>

    <!-- Tabler JS -->
    <script src="tabler-1.4.0/dist/js/tabler.min.js"></script>

    <script>
    // Image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước file quá lớn! Vui lòng chọn file nhỏ hơn 2MB');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Định dạng file không hợp lệ! Chỉ chấp nhận JPG, PNG, GIF');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Form validation
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const price = parseFloat(document.querySelector('input[name="price"]').value);
        const stock = parseInt(document.querySelector('input[name="stock"]').value);
        
        if (price <= 0) {
            e.preventDefault();
            alert('Giá sản phẩm phải lớn hơn 0');
            return false;
        }
        
        if (stock < 0) {
            e.preventDefault();
            alert('Số lượng tồn kho không thể âm');
            return false;
        }
    });

    // Reset form handler
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('imagePreview').style.display = 'none';
    });
    </script>
</body>
</html>