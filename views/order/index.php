<div class="container my-5">
    <h2 class="mb-4">Lịch sử đơn hàng của tôi</h2>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Bạn chưa có đơn hàng nào. <a href="index.php?page=product_list">Mua sắm ngay</a></div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td class="fw-bold text-danger"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <?php 
                                        $statusClass = match($order['status']) {
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            'processing' => 'info',
                                            default => 'warning'
                                        };
                                        $statusLabel = match($order['status']) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                            default => ucfirst($order['status'])
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                </td>
                                <td>
                                    <a href="index.php?page=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

