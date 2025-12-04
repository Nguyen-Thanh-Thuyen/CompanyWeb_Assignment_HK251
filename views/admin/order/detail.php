<!-- views/admin/order/detail.php -->

<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Admin Panel / Đơn hàng</div>
        <h2 class="page-title">Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="index.php?page=admin_order_list" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
          </a>
          <a href="#" class="btn btn-primary" onclick="window.print();">
            <i class="ti ti-printer"></i> In hóa đơn
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row row-cards">
      
      <!-- Left Column: Order Items -->
      <div class="col-lg-8">
        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
          </div>
          <div class="table-responsive">
            <table class="table table-vcenter card-table">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Đơn giá</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                  <td>
                    <div class="d-flex py-1 align-items-center">
                        <?php 
                            $img = $item['image'] ?? '';
                            $imgSrc = (strpos($img, 'http') === 0) ? $img : 'public/uploads/product_images/' . $img;
                            if(empty($img)) $imgSrc = 'https://via.placeholder.com/40';
                        ?>
                        <span class="avatar me-2" style="background-image: url(<?php echo $imgSrc; ?>)"></span>
                        <div class="flex-fill">
                            <div class="font-weight-medium"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="text-secondary"><small>ID: <?php echo $item['product_id']; ?></small></div>
                        </div>
                    </div>
                  </td>
                  <td class="text-center"><?php echo $item['quantity']; ?></td>
                  <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</td>
                  <td class="text-end font-weight-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                    <td colspan="3" class="text-end font-weight-bold text-uppercase">Tổng tiền:</td>
                    <td class="text-end font-weight-bold text-danger h3 mb-0"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Right Column: Customer Info & Status Actions -->
      <div class="col-lg-4">
        
        <!-- Status Update Card -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white">Cập nhật trạng thái</h3>
            </div>
            <div class="card-body">
                <form action="index.php?page=admin_order_update_status" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái hiện tại</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Chờ xử lý (Pending)</option>
                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Đang xử lý (Processing)</option>
                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành (Completed)</option>
                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Đã hủy (Cancelled)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy"></i> Lưu thay đổi
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Thông tin khách hàng</h3>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-5">Họ tên:</dt>
              <dd class="col-7"><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></dd>
              
              <dt class="col-5">Email:</dt>
              <dd class="col-7"><?php echo htmlspecialchars($order['user_email'] ?? 'N/A'); ?></dd>
              
              <dt class="col-5">Ngày đặt:</dt>
              <dd class="col-7"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></dd>
              
              <dt class="col-5">Thanh toán:</dt>
              <dd class="col-7"><span class="badge bg-secondary"><?php echo strtoupper($order['payment_method']); ?></span></dd>
            </dl>
            
            <hr class="my-3">
            
            <label class="form-label fw-bold">Ghi chú của khách:</label>
            <div class="form-control-plaintext fst-italic text-secondary">
                <?php echo !empty($order['note']) ? nl2br(htmlspecialchars($order['note'])) : 'Không có ghi chú.'; ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
