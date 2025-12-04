<style>
    /* Local styles for filter bar */
    .filter-bar {
        background: var(--bg-surface);
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    .filter-group { flex: 1; min-width: 200px; }
    .breadcrumb { margin-bottom: 20px; color: var(--text-secondary); font-size: 0.9rem; }
    .breadcrumb a { color: var(--primary-color); }
</style>

<div class="container">
    
    <div class="breadcrumb" style="margin-top: 20px;">
        <a href="index.php?page=home">Trang chủ</a> / <span>Sản phẩm</span>
    </div>

    <div style="margin-bottom: 20px;">
        <h2>Danh sách sản phẩm</h2>
        <span class="text-muted">Tìm thấy <strong><?php echo $totalProducts; ?></strong> sản phẩm</span>
    </div>

    <form method="GET" action="index.php" class="filter-bar">
        <input type="hidden" name="page" value="product_list">
        
        <div class="filter-group">
            <label style="font-size: 0.9rem; margin-bottom: 5px; display:block;">Từ khóa</label>
            <input type="text" name="keyword" class="form-control" 
                   placeholder="Tìm tên sản phẩm..." 
                   value="<?php echo htmlspecialchars($keyword); ?>">
        </div>
        
        <div class="filter-group">
            <label style="font-size: 0.9rem; margin-bottom: 5px; display:block;">Danh mục</label>
            <select name="category_id" class="form-select">
                <option value="">-- Tất cả --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                        <?php echo ($currentCategory == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">
                Tìm kiếm
            </button>
        </div>
    </form>

    <?php if (empty($products)): ?>
        <div class="text-center" style="padding: 50px; background: #fff; border-radius: 8px;">
            <h4>Không tìm thấy sản phẩm nào.</h4>
            <a href="index.php?page=product_list" class="btn btn-outline-secondary" style="margin-top:10px;">Xóa bộ lọc</a>
        </div>
    <?php else: ?>
        
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                
                <?php 
                    // Image Logic: URL vs Local File
                    $imgFromDb = $product['image_path'] ?? $product['image'] ?? ''; 
                    $displayImg = 'https://placehold.co/300x220?text=No+Image'; // Fallback

                    if (strpos($imgFromDb, 'http') === 0) {
                        $displayImg = $imgFromDb;
                    } elseif (!empty($imgFromDb) && file_exists('public/uploads/product_images/' . $imgFromDb)) {
                        $displayImg = 'public/uploads/product_images/' . $imgFromDb;
                    }
                ?>

                <div class="product-card">
                    
                    <?php if ($isAdmin): ?>
                        <button class="btn btn-danger delete-product" 
                                data-id="<?php echo $product['id']; ?>"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10; padding: 5px 10px; font-size: 0.8rem;">
                            Xóa
                        </button>
                    <?php endif; ?>

                    <div class="product-image-container">
                        <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $displayImg; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                    </div>

                    <div class="card-body">
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                            <?php echo htmlspecialchars($product['category_name'] ?? 'Khác'); ?>
                        </div>

                        <h3 class="card-title">
                            <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>

                        <div class="price">
                            <?php echo number_format($product['price'], 0, ',', '.'); ?> ₫
                        </div>

                        <div class="d-grid gap-2">
                            <?php if (($product['stock'] ?? 0) > 0): ?>
                                <button class="btn btn-primary w-100 add-to-cart" data-id="<?php echo $product['id']; ?>">
                                    Thêm vào giỏ
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary w-100" disabled>Hết hàng</button>
                            <?php endif; ?>
                            
                            <?php if ($isAdmin): ?>
                                <a href="index.php?page=admin_product_edit&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary w-100">
                                    Sửa
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php
                function getPageUrl($p) {
                    $params = $_GET;
                    $params['p'] = $p;
                    if (!isset($params['page'])) $params['page'] = 'product_list';
                    return 'index.php?' . http_build_query($params);
                }
            ?>
            <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?php echo getPageUrl($currentPage - 1); ?>">«</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo getPageUrl($i); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="<?php echo getPageUrl($currentPage + 1); ?>">»</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. ADD TO CART
    const cartButtons = document.querySelectorAll('.add-to-cart');
    cartButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const originalText = this.innerHTML;
            
            this.innerHTML = '...';
            this.disabled = true;

            // Ensure 'index.php?page=add_to_cart' maps to your CartController
            fetch('index.php?page=add_to_cart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${id}&quantity=1`
            })
            .then(res => res.json())
            .then(data => {
                this.innerHTML = originalText;
                this.disabled = false;
                alert(data.message || 'Đã thêm vào giỏ hàng'); 
            })
            .catch(err => {
                console.error(err);
                this.innerHTML = originalText;
                this.disabled = false;
                alert('Lỗi kết nối');
            });
        });
    });

    // 2. DELETE PRODUCT (ADMIN)
    const deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

            const id = this.dataset.id;
            
            fetch('index.php?page=admin_product_delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload(); 
                } else {
                    alert(data.message || 'Lỗi xóa sản phẩm');
                }
            });
        });
    });
});
</script>
