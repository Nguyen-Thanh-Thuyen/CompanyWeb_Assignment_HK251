<?php 
// views/layouts/header.php

// 1. Safe Defaults for Settings
$settings = $settings ?? ['company_name' => 'E-Commerce MVC']; 

// 2. Safe Defaults for Cart Count (Defensive Programming)
$cartCount = $cartCount ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ($settings['company_name'] . ' | Trang chủ')); ?></title>
    
    <!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="public/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>

<header class="header-bg sticky-top shadow-sm" style="background-color: #fff;">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="index.php?page=home">
                <strong class="text-primary"><?php echo htmlspecialchars($settings['company_name']); ?></strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=product_list">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=news_list">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=faq">FAQ</a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center gap-2">
                    
                    <!-- CART -->
                    <li class="nav-item">
                        <a class="btn btn-outline-primary position-relative" href="index.php?page=cart">
                            <i class="bi bi-cart"></i> Giỏ hàng
                            
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $cartCount; ?>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- USER DROPDOWN -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- Avatar Circle -->
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center shadow-sm" style="width: 32px; height: 32px; font-weight: bold; font-size: 14px;">
                                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                                </div>
                                <span class="fw-medium"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                                
                                <!-- Admin Link -->
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item text-danger fw-bold" href="index.php?page=admin_dashboard">
                                            <i class="bi bi-speedometer2 me-2"></i> Quản trị (Admin)
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>

                                <!-- Profile Links -->
                                <li>
                                    <a class="dropdown-item" href="index.php?page=profile">
                                        <i class="bi bi-person-badge me-2"></i> Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=my_orders">
                                        <i class="bi bi-box-seam me-2"></i> Đơn hàng của tôi
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <!-- Logout -->
                                <li>
                                    <a class="dropdown-item text-danger" href="index.php?page=logout">
                                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>
                        
                        <!-- GUEST -->
                        <li class="nav-item">
                            <a class="btn btn-primary text-white" href="index.php?page=login">
                                <i class="bi bi-person"></i> Đăng nhập
                            </a>
                        </li>

                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
