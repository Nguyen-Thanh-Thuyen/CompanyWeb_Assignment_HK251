<!-- views/admin/order/index.php -->

<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Admin Panel</div>
        <h2 class="page-title">Đơn hàng</h2>
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
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th class="w-1">ID</th>
              <th>Khách hàng</th>
              <th>Ngày đặt</th>
              <th>Phương thức</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="7" class="text-center py-4">Chưa có đơn hàng nào.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                  <td><span class="text-secondary">#<?php echo $order['id']; ?></span></td>
                  <td>
                    <div class="d-flex py-1 align-items-center">
                      <span class="avatar me-2" style="background-image: url(./static/avatars/000m.jpg)">
                          <?php echo strtoupper(substr($order['user_name'] ?? 'U', 0, 1)); ?>
                      </span>
                      <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo htmlspecialchars($order['user_name'] ?? 'Khách lẻ'); ?></div>
                        <div class="text-secondary"><a href="#" class="text-reset">User ID: <?php echo $order['user_id']; ?></a></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                  </td>
                  <td>
                    <span class="badge bg-secondary text-white">
                        <?php echo strtoupper($order['payment_method']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="text-danger font-weight-bold">
                        <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫
                    </div>
                  </td>
                  <td>
                    <?php 
                        $statusColor = match($order['status']) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            'processing' => 'info',
                            default => 'secondary'
                        };
                        
                        $statusLabel = match($order['status']) {
                            'completed' => 'Hoàn thành',
                            'pending' => 'Chờ xử lý',
                            'cancelled' => 'Đã hủy',
                            'processing' => 'Đang xử lý',
                            default => $order['status']
                        };
                    ?>
                    <span class="badge bg-<?php echo $statusColor; ?> me-1"></span> 
                    <?php echo $statusLabel; ?>
                  </td>
                  <td class="text-end">
                    <a href="index.php?page=admin_order_detail&id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                        Xem chi tiết
                    </a>
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
            <a class="page-link" href="index.php?page=admin_order_list&p=<?php echo $currentPage - 1; ?>">
              <i class="ti ti-chevron-left"></i>
            </a>
          </li>
          
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?page=admin_order_list&p=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=admin_order_list&p=<?php echo $currentPage + 1; ?>">
              <i class="ti ti-chevron-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
