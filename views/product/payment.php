
<div class="container my-5">
    <h2 class="mb-4 text-center">Xác nhận thanh toán 
</h2>

    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Thông tin nhận hàng
                </div>
                <div class="card-body">
                    <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($note); ?></p>
                    
                    <hr>
                    <h5>Phương thức thanh toán</h5>
                    <form action="index.php?page=process_payment" method="POST">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                            <label class="form-check-label">Thanh toán khi nhận hàng (COD) 

[Image of cash on delivery icon]
</label>
                        <button type="submit" class="btn btn-success w-100 mt-3 btn-lg">
                            Xác nhận đặt hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Sản phẩm
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($cartItems as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php echo htmlspecialchars($item['name']); ?>
                                <small class="text-muted d-block">x <?php echo $item['quantity']; ?></small>
                            </div>
                            <span><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <strong>Tổng tiền</strong>
                        <strong class="text-danger"><?php echo number_format($total, 0, ',', '.'); ?>₫</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
