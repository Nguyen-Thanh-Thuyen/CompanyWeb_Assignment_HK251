<!-- views/admin/dashboard.php -->
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Overview</div>
        <h2 class="page-title">Dashboard</h2>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <!-- STATS CARDS -->
    <div class="row row-deck row-cards mb-4">
      
      <!-- Revenue -->
      <div class="col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="subheader">Doanh thu</div>
            </div>
            <div class="h1 mb-3"><?php echo number_format($totalRevenue ?? 0, 0, ',', '.'); ?>₫</div>
            <div class="d-flex mb-2">
              <span class="text-success">Đơn hoàn thành</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders -->
      <div class="col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="subheader">Đơn hàng</div>
            </div>
            <div class="h1 mb-3"><?php echo $totalOrders ?? 0; ?></div>
            <div class="d-flex mb-2">
              <span class="text-secondary">Tổng số đơn</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Users -->
      <div class="col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="subheader">Khách hàng</div>
            </div>
            <div class="h1 mb-3"><?php echo $totalUsers ?? 0; ?></div>
            <div class="d-flex mb-2">
              <span class="text-secondary">Tài khoản User</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Contacts -->
      <div class="col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="subheader">Liên hệ mới</div>
            </div>
            <div class="h1 mb-3"><?php echo $newContacts ?? 0; ?></div>
            <div class="d-flex mb-2">
              <a href="index.php?page=admin_contacts" class="text-primary">Xem danh sách <i class="ti ti-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Đơn hàng mới nhất</h3>
        </div>
        <div class="table-responsive">
          <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
              <tr>
                <th class="w-1">ID</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($recentOrders)): ?>
                  <?php foreach ($recentOrders as $order): ?>
                  <tr>
                    <td><span class="text-secondary">#<?php echo $order['id']; ?></span></td>
                    <td>
                        <div class="d-flex py-1 align-items-center">
                            <!-- Generate Avatar from Name Initials -->
                            <span class="avatar me-2 bg-blue-lt">
                                <?php echo strtoupper(substr($order['user_name'] ?? 'K', 0, 1)); ?>
                            </span>
                            <div class="flex-fill">
                                <div class="font-weight-medium"><?php echo htmlspecialchars($order['user_name'] ?? 'Khách lẻ'); ?></div>
                                <div class="text-secondary"><small>User ID: <?php echo $order['user_id']; ?></small></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
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
                            default => ucfirst($order['status'])
                        };
                      ?>
                      <span class="badge bg-<?php echo $statusColor; ?> me-1"></span> 
                      <?php echo $statusLabel; ?>
                    </td>
                    <td class="text-danger fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫</td>
                    <td class="text-end">
                        <a href="index.php?page=admin_order_detail&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-ghost-primary">
                            Chi tiết
                        </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr><td colspan="6" class="text-center p-3">Chưa có đơn hàng nào</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer text-end">
            <a href="index.php?page=admin_order_list" class="btn btn-primary">Xem tất cả đơn hàng</a>
        </div>
      </div>
    </div>

  </div>
</div>
