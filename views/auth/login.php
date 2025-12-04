<!-- views/auth/login.php -->
<style>
    .login-container {
        min-height: 80vh; /* Fill screen mostly */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-body);
    }
    .login-card {
        background: white;
        padding: 40px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }
    .login-card h2 {
        color: var(--primary-color);
        margin-bottom: 20px;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <h2>Đăng Nhập</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=login_submit" method="POST">
            <div style="margin-bottom: 15px; text-align: left;">
                <label class="form-label" style="font-weight: 500;">Email:</label>
                <input type="email" name="email" class="form-control" required placeholder="admin@gmail.com">
            </div>

            <div style="margin-bottom: 25px; text-align: left;">
                <label class="form-label" style="font-weight: 500;">Mật khẩu:</label>
                <input type="password" name="password" class="form-control" required placeholder="******">
            </div>

            <button type="submit" class="btn btn-primary w-100" style="width: 100%; padding: 12px;">
                Đăng Nhập
            </button>
        </form>

<div style="margin-top: 20px; font-size: 0.9rem;">
    Chưa có tài khoản? <a href="index.php?page=register" style="color: var(--accent-color);">Đăng ký ngay</a>
</div>
    </div>
</div>
