<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col"><h2 class="page-title">Quản lý FAQ</h2></div>
      <div class="col-auto ms-auto">
        <a href="index.php?page=admin_faq_create" class="btn btn-primary"><i class="ti ti-plus"></i> Thêm câu hỏi</a>
      </div>
    </div>
  </div>
</div>
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter">
          <thead><tr><th>Câu hỏi</th><th>Câu trả lời</th><th class="w-1"></th></tr></thead>
          <tbody>
            <?php foreach ($faqs as $faq): ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($faq['question']); ?></strong></td>
              <td class="text-secondary text-truncate" style="max-width: 400px;">
                <?php echo htmlspecialchars($faq['answer']); ?>
              </td>
              <td>
                <div class="btn-list flex-nowrap">
                    <a href="index.php?page=admin_faq_edit&id=<?php echo $faq['id']; ?>" class="btn btn-white btn-sm">Sửa</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $faq['id']; ?>">Xóa</button>
                </div>
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
        if(confirm('Xóa FAQ này?')) {
            fetch('index.php?page=admin_faq_delete', {
                method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${this.dataset.id}`
            }).then(() => location.reload());
        }
    });
});
</script>
