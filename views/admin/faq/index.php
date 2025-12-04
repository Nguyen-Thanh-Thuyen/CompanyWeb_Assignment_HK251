<div class="page-header"><div class="container-xl"><h2 class="page-title">Thêm tin tức mới</h2></div></div>
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tóm tắt</label>
                <textarea class="form-control" name="summary" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung</label>
                <textarea class="form-control" name="content" rows="10" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Đăng bài</button>
            <a href="index.php?page=admin_news_list" class="btn btn-link">Hủy</a>
        </form>
      </div>
    </div>
  </div>
</div>
