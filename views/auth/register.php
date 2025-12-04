<style>
    .login-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-body);
        padding: 20px;
    }
    .login-card {
        background: white;
        padding: 40px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 450px; /* Slightly wider than login */
        text-align: center;
    }
    .login-card h2 {
        color: var(--primary-color);
        margin-bottom: 20px;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <h2>Đăng Ký Tài Khoản</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-start">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=register_submit" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Họ và Tên</label>
                <input type="text" name="name" class="form-control" required 
                       value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>"
                       placeholder="Nguyễn Văn A">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" required 
                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                       placeholder="email@example.com">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="******">
            </div>

            <div class="mb-4 text-start">
                <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="******">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                Đăng Ký
            </button>
        </form>

        <div class="mt-4" style="font-size: 0.9rem;">
            Đã có tài khoản? <a href="index.php?page=login" style="color: var(--accent-color); font-weight: bold;">Đăng nhập ngay</a>
        </div>
    </div>
</div>
