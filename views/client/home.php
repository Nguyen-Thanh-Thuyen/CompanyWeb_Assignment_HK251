<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['company_name']; ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        header { background: #f8f9fa; padding: 20px; border-bottom: 1px solid #ddd; display: flex; align-items: center; justify-content: space-between; }
        .logo img { max-height: 60px; }
        .nav a { margin-left: 20px; text-decoration: none; color: #333; font-weight: bold; }
        .hero { text-align: center; padding: 100px 20px; background: #e9ecef; }
        .footer { background: #333; color: #fff; padding: 20px; text-align: center; margin-top: 50px;}
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <?php if (!empty($settings['logo_path'])): ?>
                <img src="<?php echo BASE_URL . '/public/uploads/' . $settings['logo_path']; ?>" alt="Logo">
            <?php else: ?>
                <h1><?php echo $settings['company_name']; ?></h1>
            <?php endif; ?>
        </div>
        
        <nav class="nav">
            <a href="index.php?page=home">Trang chủ</a>
            <a href="index.php?page=contact">Liên hệ</a>

        </nav>
    </header>

    <div class="hero">
        <h1>Chào mừng đến với <?php echo $settings['company_name']; ?></h1>
        <p class="lead"><?php echo $settings['intro_text']; ?></p>
        
        <br>
        <p><strong>Hotline:</strong> <?php echo $settings['phone_number']; ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo $settings['address']; ?></p>
    </div>

    <div class="footer">
        &copy; 2025 <?php echo $settings['company_name']; ?>. All rights reserved.
        <div style="margin-top: 10px; font-size: 12px;">
            <a href="index.php?page=admin_settings" style="color: #666; text-decoration: none;">
                Dành cho quản trị viên
            </a>
        </div>
    </div>

</body>
</html>