<!-- views/admin/contact/index.php -->
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Admin Panel</div>
        <h2 class="page-title">Liên hệ từ khách hàng</h2>
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
              <th>Người gửi</th>
              <th>Thông tin</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Ngày gửi</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($contacts)): ?>
                <tr><td colspan="7" class="text-center py-4">Chưa có liên hệ nào.</td></tr>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                <tr>
                  <td><span class="text-secondary"><?php echo $contact['id']; ?></span></td>
                  <td>
                    <div class="font-weight-medium"><?php echo htmlspecialchars($contact['full_name']); ?></div>
                  </td>
                  <td>
                    <div><i class="ti ti-mail small me-1"></i> <?php echo htmlspecialchars($contact['email']); ?></div>
                    <div class="text-secondary"><i class="ti ti-phone small me-1"></i> <?php echo htmlspecialchars($contact['phone']); ?></div>
                  </td>
                  <td class="text-wrap" style="max-width: 300px;">
                    <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                  </td>
                  <td>
                    <select class="form-select form-select-sm status-select" 
                            data-id="<?php echo $contact['id']; ?>"
                            style="width: 120px;">
                        <option value="new" <?php echo $contact['status'] == 'new' ? 'selected' : ''; ?>>Mới</option>
                        <option value="read" <?php echo $contact['status'] == 'read' ? 'selected' : ''; ?>>Đã xem</option>
                        <option value="replied" <?php echo $contact['status'] == 'replied' ? 'selected' : ''; ?>>Đã trả lời</option>
                    </select>
                  </td>
                  <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-danger btn-icon delete-btn" data-id="<?php echo $contact['id']; ?>">
                        <i class="ti ti-trash"></i>
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
            <a class="page-link" href="index.php?page=admin_contacts&p=<?php echo $currentPage - 1; ?>"><i class="ti ti-chevron-left"></i></a>
          </li>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?page=admin_contacts&p=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_contacts&p=<?php echo $currentPage + 1; ?>"><i class="ti ti-chevron-right"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
// Handle Status Change
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const id = this.dataset.id;
        const status = this.value;
        fetch('index.php?page=admin_contact_status', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}&status=${status}`
        }).then(res => res.json())
          .then(data => {
              if(!data.success) alert('Lỗi cập nhật trạng thái');
          });
    });
});

// Handle Delete
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc chắn muốn xóa liên hệ này?')) {
            const id = this.dataset.id;
            fetch('index.php?page=admin_contact_delete', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            }).then(res => res.json())
              .then(data => {
                  if(data.success) location.reload();
                  else alert('Lỗi xóa liên hệ');
              });
        }
    });
});
</script>
