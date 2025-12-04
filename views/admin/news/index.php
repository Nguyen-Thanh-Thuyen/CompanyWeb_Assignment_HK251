<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Tin tức / Blog</h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <a href="index.php?page=admin_news_create" class="btn btn-primary d-none d-sm-inline-block">
          <i class="ti ti-plus"></i> Thêm bài viết
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th class="w-1">ID</th>
              <th>Hình ảnh</th>
              <th>Tiêu đề</th>
              <th>Ngày tạo</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($news_list as $item): ?>
            <tr>
              <td><?php echo $item['id']; ?></td>
              <td>
                <?php if($item['image']): ?>
                    <img src="<?php echo $item['image']; ?>" class="avatar rounded" style="width:50px; height:50px; object-fit:cover;">
                <?php endif; ?>
              </td>
              <td>
                  <div class="font-weight-medium"><?php echo htmlspecialchars($item['title']); ?></div>
                  <div class="text-secondary small text-truncate" style="max-width:300px;"><?php echo htmlspecialchars($item['summary']); ?></div>
              </td>
              <td><?php echo date('d/m/Y', strtotime($item['created_at'])); ?></td>
              <td class="text-end">
                <a href="index.php?page=admin_news_edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $item['id']; ?>">Xóa</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Xóa bài viết này?')) {
            fetch('index.php?page=admin_news_delete', {
                method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${this.dataset.id}`
            }).then(() => location.reload());
        }
    });
});
</script>
