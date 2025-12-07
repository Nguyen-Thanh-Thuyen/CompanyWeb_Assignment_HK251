<!-- views/admin/user/index.php -->
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Quản lý tài khoản</h2>
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
              <th>Họ tên</th>
              <th>Email</th>
              <th>Vai trò</th>
              <th>Trạng thái</th>
              <th>Ngày tạo</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><span class="text-secondary"><?php echo $user['id']; ?></span></td>
              <td>
                <div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(./static/avatars/000m.jpg)">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                </div>
              </td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td>
                <?php if ($user['role'] === 'admin'): ?>
                    <span class="badge bg-purple text-purple-fg">Admin</span>
                <?php else: ?>
                    <span class="badge bg-blue text-blue-fg">User</span>
                <?php endif; ?>
              </td>
              <td>
                <label class="form-check form-switch m-0">
                    <input class="form-check-input status-toggle" type="checkbox" 
                           data-id="<?php echo $user['id']; ?>"
                           <?php echo ($user['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
                </label>
              </td>
              <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
              <td>
                <button class="btn btn-sm btn-warning reset-pass-btn" data-id="<?php echo $user['id']; ?>">
                    <i class="ti ti-key"></i> Reset Pass
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">Trang <?php echo $currentPage; ?> / <?php echo $totalPages; ?></p>
        <ul class="pagination m-0 ms-auto">
          <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_user_list&p=<?php echo $currentPage - 1; ?>"><i class="ti ti-chevron-left"></i></a>
          </li>
          <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_user_list&p=<?php echo $currentPage + 1; ?>"><i class="ti ti-chevron-right"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle Status
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const status = this.checked ? 'active' : 'disabled';
        
        fetch('index.php?page=admin_user_status', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}&status=${status}`
        })
        .then(res => res.json())
        .then(data => {
            if(!data.success) {
                alert(data.message || 'Lỗi cập nhật');
                this.checked = !this.checked; // Revert switch
            }
        });
    });
});

// Reset Password
document.querySelectorAll('.reset-pass-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Đặt lại mật khẩu cho user này thành "123456"?')) {
            fetch('index.php?page=admin_user_reset_password', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${this.dataset.id}`
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            });
        }
    });
});
</script>
