<!-- views/client/home.php -->

<div style="background-color: var(--primary-color); color: var(--text-light); padding: 80px 0; margin-bottom: 40px; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 20px;">
            Chào mừng đến với <?php echo htmlspecialchars($settings['company_name'] ?? 'Cửa hàng'); ?>
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">
            <?php echo htmlspecialchars($settings['intro_text'] ?? 'Khám phá những sản phẩm chất lượng tốt nhất với giá cả hợp lý.'); ?>
        </p>
        
        <a href="index.php?page=product_list" class="btn" style="background-color: var(--accent-color); color: white; padding: 12px 30px; font-size: 1.1rem;">
            Mua sắm ngay
        </a>
    </div>
</div>

<div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Sản phẩm nổi bật</h2>
        <a href="index.php?page=product_list" class="btn btn-outline-secondary">Xem tất cả</a>
    </div>

    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                
                <?php 
                    // --- IMAGE LOGIC (FIXED) ---
                    // 1. Get image data (Supports both 'image' and 'image_path' column names)
                    $imgFromDb = $product['image'] ?? $product['image_path'] ?? ''; 
                    
                    $displayImg = 'https://placehold.co/300x220?text=No+Image'; // Default Fallback

                    // Case 1: Is it an External URL? (Starts with http or https)
                    if (!empty($imgFromDb) && strpos($imgFromDb, 'http') === 0) {
                        $displayImg = $imgFromDb;
                    } 
                    // Case 2: Is it a Local File? (Check correct folder: public/uploads/product_images/)
                    elseif (!empty($imgFromDb) && file_exists('public/uploads/product_images/' . $imgFromDb)) {
                        $displayImg = 'public/uploads/product_images/' . $imgFromDb;
                    }
                    // Case 3: Legacy Path Support (Check generic uploads folder)
                    elseif (!empty($imgFromDb) && file_exists('public/uploads/' . $imgFromDb)) {
                        $displayImg = 'public/uploads/' . $imgFromDb;
                    }
                ?>

                <div class="product-card">
                    <div class="product-image-container">
                        <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                            <img src="<?php echo htmlspecialchars($displayImg); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                        <div class="price">
                            <?php echo number_format($product['price'], 0, ',', '.'); ?> ₫
                        </div>
                        <div class="d-grid gap-2">
                            <!-- Updated to use class-based listener instead of direct link -->
                            <button class="btn btn-primary w-100 add-to-cart" 
                                    data-id="<?php echo $product['id']; ?>">
                                Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center w-100" style="grid-column: 1 / -1; padding: 40px;">
                <p class="text-muted">Hiện chưa có sản phẩm nào nổi bật.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<div style="margin-bottom: 80px;"></div>

<!-- Quick Add-to-Cart Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            
            // Visual Feedback
            const originalText = this.innerText;
            this.innerText = '...';
            this.disabled = true;

            fetch('index.php?page=add_to_cart', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `product_id=${id}&quantity=1`
            })
            .then(res => res.json())
            .then(data => {
                this.innerText = originalText;
                this.disabled = false;
                
                if(data.success) {
                    // Optional: You can use SweetAlert here if available
                    alert('Đã thêm sản phẩm vào giỏ hàng!');
                    location.reload(); // Reload to update header cart count
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                this.innerText = originalText;
                this.disabled = false;
                alert('Lỗi kết nối server');
            });
        });
    });
});
</script>
