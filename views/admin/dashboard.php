<!-- STATS CARDS -->
    <div class="row row-deck row-cards mb-4">
      
      <!-- Revenue -->
      <div class="col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="subheader">Doanh thu</div>
            </div>
            <div class="h1 mb-3"><?php echo number_format($totalRevenue, 0, ',', '.'); ?>₫</div>
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
            <div class="h1 mb-3"><?php echo $totalOrders; ?></div>
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
            <div class="h1 mb-3"><?php echo $totalUsers; ?></div>
            <div class="d-flex mb-2">
              <span class="text-secondary">Tài khoản User</span>
            </div>
          </div>
        </div>
      </div>

      <!-- NEW: CONTACTS CARD -->
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
