<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=product_list">Sản phẩm</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <?php 
                    $img = $product['image'] ?? '';
                    $displayImg = 'https://placehold.co/600x400';
                    if (strpos($img, 'http') === 0) $displayImg = $img;
                    elseif (!empty($img)) $displayImg = 'public/uploads/product_images/' . $img;
                ?>
                <img src="<?php echo $displayImg; ?>" class="card-img-top p-3" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="mb-3">
                <span class="text-warning">
                    <?php 
                        $stars = round($avgRating);
                        for($i=1; $i<=5; $i++) echo ($i <= $stars) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                    ?>
                </span>
                <span class="text-muted ms-2">(<?php echo $totalComments; ?> đánh giá)</span>
            </div>
            
            <h2 class="text-danger fw-bold mb-4"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</h2>
            
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <hr>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary btn-lg flex-grow-1 add-to-cart" data-id="<?php echo $product['id']; ?>">
                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Đánh giá & Bình luận</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="index.php?page=add_comment" method="POST" class="mb-5 p-4 bg-light rounded">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <h5 class="mb-3">Viết đánh giá của bạn</h5>
                            
                            <div class="mb-3">
                                <label class="form-label me-3">Đánh giá:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" value="5" checked> <label class="text-warning"><i class="bi bi-star-fill"></i> 5</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" value="4"> <label class="text-warning"><i class="bi bi-star-fill"></i> 4</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" value="3"> <label class="text-warning"><i class="bi bi-star-fill"></i> 3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" value="2"> <label class="text-warning"><i class="bi bi-star-fill"></i> 2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" value="1"> <label class="text-warning"><i class="bi bi-star-fill"></i> 1</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Vui lòng <a href="index.php?page=login" class="alert-link">đăng nhập</a> để viết bình luận.
                        </div>
                    <?php endif; ?>

                    <?php if (empty($comments)): ?>
                        <p class="text-muted text-center py-3">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($comment['user_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($comment['user_name']); ?></strong>
                                            <div class="text-warning small">
                                                <?php for($i=1; $i<=5; $i++) echo ($i <= $comment['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end">
                                        <div class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></div>
                                        
                                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                            <button class="btn btn-link text-danger p-0 small delete-comment-btn" 
                                                    data-id="<?php echo $comment['id']; ?>" 
                                                    style="text-decoration: none; font-size: 0.85rem;">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mb-0 text-secondary ps-5">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
        <?php foreach ($relatedProducts as $relProduct): ?>
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 product-card">
                    <a href="index.php?page=product_detail&id=<?php echo $relProduct['id']; ?>">
                        <?php 
                            $rImg = $relProduct['image'] ?? '';
                            $rDisplay = (strpos($rImg, 'http') === 0) ? $rImg : 'public/uploads/product_images/' . $rImg;
                            if(empty($rImg)) $rDisplay = 'https://placehold.co/300x220';
                        ?>
                        <img src="<?php echo $rDisplay; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($relProduct['name']); ?>">
                    </a>
                    <div class="card-body p-3">
                        <h6 class="card-title text-truncate">
                            <a href="index.php?page=product_detail&id=<?php echo $relProduct['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($relProduct['name']); ?>
                            </a>
                        </h6>
                        <p class="text-danger fw-bold mb-0"><?php echo number_format($relProduct['price'], 0, ',', '.'); ?>₫</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Add to Cart
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch('index.php?page=add_to_cart', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${id}&quantity=1`
        }).then(res => res.json()).then(data => {
            alert(data.message);
            location.reload();
        });
    });
});

// Admin Delete Comment
document.querySelectorAll('.delete-comment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
            const id = this.dataset.id;
            // Uses existing admin endpoint
            fetch('index.php?page=admin_comment_delete', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Đã xóa bình luận');
                    location.reload();
                } else {
                    alert(data.message || 'Lỗi xóa bình luận');
                }
            })
            .catch(err => alert('Lỗi kết nối server'));
        }
    });
});
</script>
