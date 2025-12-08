<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet"/>
    
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


        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">Quản lý sản phẩm</div>
                            <h2 class="page-title">Thêm sản phẩm mới</h2>
                        </div>
                        <div class="col-auto ms-auto">
                            <a href="index.php?page=admin_product_list" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-md-8">
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <div class="d-flex">
                                        <div><i class="ti ti-check me-2"></i></div>
                                        <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                                    </div>
                                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <div class="d-flex">
                                        <div><i class="ti ti-alert-circle me-2"></i></div>
                                        <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                    </div>
                                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="index.php?page=admin_product_create" enctype="multipart/form-data" id="productForm">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Thông tin sản phẩm</h3>
                                    </div>
                                    <div class="card-body">
                                        
                                        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <div class="d-flex">
                                                    <div><i class="ti ti-alert-circle me-2"></i></div>
                                                    <div>
                                                        <h4 class="alert-title">Vui lòng kiểm tra lại dữ liệu!</h4>
                                                        <ul class="mb-0 ps-3">
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

                                        <div class="mb-3">
                                            <label class="form-label required">Danh mục</label>
                                            <select class="form-select" name="category_id" required>
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php if (!empty($categories)): ?>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?php echo $category['id']; ?>"
                                                                <?php echo (isset($_SESSION['old']['category_id']) && $_SESSION['old']['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($category['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mô tả sản phẩm</label>
                                            <textarea class="form-control" 
                                                      name="description" 
                                                      rows="5" 
                                                      placeholder="Nhập mô tả chi tiết về sản phẩm"><?php echo htmlspecialchars($_SESSION['old']['description'] ?? ''); ?></textarea>
                                            <small class="form-hint">Mô tả chi tiết giúp khách hàng hiểu rõ hơn về sản phẩm</small>
                                        </div>

                                        <div class="row">
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
                                            
                                            <div>
                                                <img id="imagePreview" class="image-preview" alt="Preview">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer text-end">
                                        <button type="reset" class="btn btn-link">Xóa form</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-plus"></i> Thêm sản phẩm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Hướng dẫn</h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Lưu ý khi thêm sản phẩm</h4>
                                    <ul class="list-unstyled space-y-1">
                                        <li><i class="ti ti-check text-primary"></i> Tên sản phẩm nên ngắn gọn, dễ hiểu</li>
                                        <li><i class="ti ti-check text-primary"></i> Mô tả chi tiết giúp tăng khả năng bán hàng</li>
                                        <li><i class="ti ti-check text-primary"></i> Hình ảnh rõ ràng, chất lượng cao</li>
                                        <li><i class="ti ti-check text-primary"></i> Giá cả cạnh tranh với thị trường</li>
                                        <li><i class="ti ti-check text-primary"></i> Cập nhật tồn kho chính xác</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-status-top bg-blue"></div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <i class="ti ti-info-circle text-blue"></i> Thông tin
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

    <?php unset($_SESSION['old']); ?>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

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
        
        if (isNaN(price) || price <= 0) {
            e.preventDefault();
            alert('Giá sản phẩm phải lớn hơn 0');
            return false;
        }
        
        if (isNaN(stock) || stock < 0) {
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
