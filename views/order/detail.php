<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=my_orders">Lịch sử đơn hàng</a></li>
            <li class="breadcrumb-item active">Chi tiết #<?php echo $order['id']; ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
        <div>
            <span class="badge bg-secondary fs-6"><?php echo strtoupper($order['status']); ?></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle"></i> Thông tin đơn hàng
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                    <p class="mb-2"><strong>Phương thức TT:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
                    <hr>
                    <p class="mb-1"><strong>Ghi chú:</strong></p>
                    <p class="text-muted fst-italic bg-light p-2 rounded">
                        <?php echo htmlspecialchars($order['note'] ?: 'Không có ghi chú'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-bag"></i> Danh sách sản phẩm
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php if(!empty($item['image'])): ?>
                                                    <img src="<?php echo strpos($item['image'], 'http') === 0 ? $item['image'] : 'public/uploads/product_images/' . $item['image']; ?>" 
                                                         alt="img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                <?php endif; ?>
                                                <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</td>
                                        <td class="text-end pe-4 fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3">Tổng cộng:</td>
                                    <td class="text-end pe-4 fw-bold text-danger fs-5 py-3">
                                        <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 text-end">
                <a href="index.php?page=my_orders" class="btn btn-outline-secondary">Quay lại danh sách</a>
            </div>
        </div>
    </div>
</div>
