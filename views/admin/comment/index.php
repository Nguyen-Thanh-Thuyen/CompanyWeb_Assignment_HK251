<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Quản lý Bình luận & Đánh giá</h2>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter">
          <thead>
            <tr>
              <th class="w-1">ID</th>
              <th>Người dùng</th>
              <th>Sản phẩm</th>
              <th>Đánh giá</th>
              <th>Nội dung</th>
              <th>Ngày</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($comments)): ?>
                <tr><td colspan="7" class="text-center py-4">Chưa có bình luận nào.</td></tr>
            <?php else: ?>
                <?php foreach ($comments as $item): ?>
                <tr>
                  <td><?php echo $item['id']; ?></td>
                  <td>
                    <div class="font-weight-medium"><?php echo htmlspecialchars($item['user_name']); ?></div>
                    <div class="text-muted small">ID: <?php echo $item['user_id']; ?></div>
                  </td>
                  <td>
                    <a href="index.php?page=product_detail&id=<?php echo $item['product_id']; ?>" target="_blank">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </a>
                  </td>
                  <td>
                    <span class="text-warning">
                        <?php for($i=1; $i<=5; $i++) echo ($i <= $item['rating']) ? '★' : '☆'; ?>
                    </span>
                  </td>
                  <td class="text-wrap" style="max-width: 300px;">
                    <?php echo htmlspecialchars($item['content']); ?>
                  </td>
                  <td><?php echo date('d/m/Y', strtotime($item['created_at'])); ?></td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $item['id']; ?>">
                        Xóa
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Trang <span><?php echo $currentPage; ?></span> / <span><?php echo $totalPages; ?></span>
        </p>
        <ul class="pagination m-0 ms-auto">
          <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_comment_list&p=<?php echo $currentPage - 1; ?>"><i class="ti ti-chevron-left"></i></a>
          </li>
          <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_comment_list&p=<?php echo $currentPage + 1; ?>"><i class="ti ti-chevron-right"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Xóa bình luận này?')) {
            fetch('index.php?page=admin_comment_delete', {
                method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${this.dataset.id}`
            }).then(() => location.reload());
        }
    });
});
</script>
