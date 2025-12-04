<!-- views/news/detail.php -->
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=news_list">Tin tức</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết bài viết</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Article Header -->
            <div class="mb-4 text-center">
                <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($news['title']); ?></h1>
                <div class="text-muted">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?>
                </div>
            </div>

            <!-- Featured Image -->
            <?php if (!empty($news['image'])): ?>
                <div class="mb-4 text-center">
                    <img src="<?php echo htmlspecialchars($news['image']); ?>" 
                         class="img-fluid rounded shadow-sm" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>"
                         style="max-height: 500px; object-fit: cover; width: 100%;">
                </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content fs-5 lh-lg text-break">
                <!-- Sử dụng nl2br để giữ xuống dòng nếu nội dung là text thuần -->
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>

            <!-- Footer Actions -->
            <hr class="my-5">
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php?page=news_list" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại tin tức
                </a>
                
                <!-- Share buttons (Optional demo) -->
                <div>
                    <span class="me-2 text-muted">Chia sẻ:</span>
                    <button class="btn btn-sm btn-light border"><i class="bi bi-facebook text-primary"></i></button>
                    <button class="btn btn-sm btn-light border"><i class="bi bi-twitter text-info"></i></button>
                    <button class="btn btn-sm btn-light border"><i class="bi bi-link-45deg"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm một chút CSS riêng cho bài viết nếu cần -->
<style>
    .article-content {
        color: #333;
        text-align: justify;
    }
</style>
