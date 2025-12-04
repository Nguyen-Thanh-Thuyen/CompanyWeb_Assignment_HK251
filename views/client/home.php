<div style="background-color: var(--primary-color); color: var(--text-light); padding: 80px 0; margin-bottom: 40px; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 20px;">
            Chào mừng đến với <?php echo htmlspecialchars($settings['company_name'] ?? 'Cửa hàng'); ?>
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">
            <?php echo htmlspecialchars($settings['intro_text'] ?? 'Khám phá những sản phẩm chất lượng tốt nhất với giá cả hợp lý.'); ?>
        </p>
        
        <a href="index.php?page=products" class="btn" style="background-color: var(--accent-color); color: white; padding: 12px 30px; font-size: 1.1rem;">
            Mua sắm ngay
        </a>
    </div>
</div>

<div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Sản phẩm nổi bật</h2>
        <a href="index.php?page=products" class="btn btn-outline-secondary">Xem tất cả</a>
    </div>

<div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                
                <?php 
                    // --- HYBRID IMAGE LOGIC (URL + Local) ---
                    $imgFromDb = $product['image_path'] ?? '';
                    $displayImg = ''; // Default

                    // Case 1: Is it an External URL? (Starts with http or https)
                    if (strpos($imgFromDb, 'http') === 0) {
                        $displayImg = $imgFromDb;
                    } 
                    // Case 2: Is it a Local File? (Check if file exists in public/uploads)
                    elseif (!empty($imgFromDb) && file_exists('public/uploads/' . $imgFromDb)) {
                        // We use the relative path here so it works on XAMPP
                        $displayImg = 'public/uploads/' . $imgFromDb;
                    } 
                    // Case 3: Fallback (No image found)
                    else {
                        $displayImg = 'https://placehold.co/300x220?text=No+Image';
                    }
                ?>

                <div class="product-card">
                    <div class="product-image-container">
                        <a href="index.php?page=product_detail&id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $displayImg; ?>" 
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
                            <a href="index.php?page=cart_add&id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">
                                Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center w-100" style="grid-column: 1 / -1; padding: 40px;">
                <p class="text-muted">Hiện chưa có sản phẩm nào nổi bật.</p>
            </div>
        <?php endif; ?>
    </div></div>

<div style="margin-bottom: 80px;"></div>
