<div class="container my-5">
    <h2 class="mb-4">Tin tức & Sự kiện</h2>
    <div class="row g-4">
        <?php foreach ($news_list as $news): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <?php if($news['image']): ?>
                    <img src="<?php echo $news['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                    <p class="card-text text-muted small"><?php echo htmlspecialchars($news['summary']); ?></p>
                    <a href="index.php?page=news_detail&id=<?php echo $news['id']; ?>" class="btn btn-outline-primary btn-sm">Xem thêm</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
