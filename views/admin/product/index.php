<!-- views/admin/product/index.php -->

<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Admin Panel</div>
        <h2 class="page-title">Sản phẩm</h2>
      </div>
      <!-- Add New Button -->
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="index.php?page=admin_product_create" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus"></i> Thêm mới
          </a>
          <a href="index.php?page=admin_product_create" class="btn btn-primary d-sm-none btn-icon">
            <i class="ti ti-plus"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <div class="card">
      <div class="card-body border-bottom py-3">
        <div class="d-flex">
          <div class="text-secondary">
            Hiển thị 
            <div class="mx-2 d-inline-block">
              <input type="text" class="form-control form-control-sm" value="10" size="3" aria-label="Invoices count">
            </div>
            kết quả
          </div>
          <div class="ms-auto text-secondary">
            <!-- Search Form -->
            <form action="index.php" method="GET">
                <input type="hidden" name="page" value="admin_product_list">
                <div class="ms-2 d-inline-block">
                    <input type="text" name="keyword" class="form-control form-control-sm" 
                           value="<?php echo htmlspecialchars($keyword); ?>" 
                           aria-label="Search invoice" placeholder="Tìm kiếm...">
                </div>
            </form>
          </div>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th class="w-1">ID</th>
              <th>Hình ảnh</th>
              <th>Tên sản phẩm</th>
              <th>Giá</th>
              <th>Kho</th>
              <th>Trạng thái</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="7" class="text-center py-4">Không tìm thấy sản phẩm nào.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                  <td><span class="text-secondary"><?php echo $product['id']; ?></span></td>
                  <td>
                    <?php 
                        $img = $product['image'] ?? '';
                        $imgSrc = (strpos($img, 'http') === 0) ? $img : 'public/uploads/product_images/' . $img;
                        if (empty($img)) $imgSrc = 'https://via.placeholder.com/40';
                    ?>
                    <img src="<?php echo $imgSrc; ?>" alt="" class="avatar avatar-sm rounded">
                  </td>
                  <td>
                    <a href="#" class="text-reset" tabindex="-1"><?php echo htmlspecialchars($product['name']); ?></a>
                    <div class="small text-secondary mt-1">
                        <?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?>
                    </div>
                  </td>
                  <td>
                    <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                  </td>
                  <td>
                    <?php echo $product['stock']; ?>
                  </td>
                  <td>
                    <?php if (($product['stock'] ?? 0) > 0): ?>
                        <span class="badge bg-success me-1"></span> Còn hàng
                    <?php else: ?>
                        <span class="badge bg-danger me-1"></span> Hết hàng
                    <?php endif; ?>
                  </td>
                  <td class="text-end">
                    <span class="dropdown">
                      <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Hành động</button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="index.php?page=admin_product_edit&id=<?php echo $product['id']; ?>">
                          Sửa
                        </a>
                        <a class="dropdown-item text-danger delete-btn" href="#" data-id="<?php echo $product['id']; ?>">
                          Xóa
                        </a>
                      </div>
                    </span>
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
            <a class="page-link" href="index.php?page=admin_product_list&p=<?php echo $currentPage - 1; ?>&keyword=<?php echo $keyword; ?>">
              <i class="ti ti-chevron-left"></i>
            </a>
          </li>
          
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?page=admin_product_list&p=<?php echo $i; ?>&keyword=<?php echo $keyword; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_product_list&p=<?php echo $currentPage + 1; ?>&keyword=<?php echo $keyword; ?>">
              <i class="ti ti-chevron-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Simple Delete Script -->
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                const id = this.dataset.id;
                fetch('index.php?page=admin_product_delete', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) location.reload();
                    else alert(data.message);
                });
            }
        });
    });
</script>
