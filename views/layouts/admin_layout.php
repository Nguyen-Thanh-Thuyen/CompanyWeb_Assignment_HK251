<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo $page_title ?? 'Admin Dashboard'; ?> - Tabler</title>
    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body >
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/demo-theme.min.js"></script>
    <div class="page">
      <!-- Sidebar -->
      <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
            <a href="index.php?page=admin_dashboard">
              Admin Panel
            </a>
          </h1>
          <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=admin_dashboard">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-home"></i>
                  </span>
                  <span class="nav-link-title">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=admin_product_list">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-package"></i>
                  </span>
                  <span class="nav-link-title">Sản phẩm</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=admin_order_list">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-shopping-cart"></i>
                  </span>
                  <span class="nav-link-title">Đơn hàng</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=admin_news_list">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-news"></i>
                  </span>
                  <span class="nav-link-title">Tin tức</span>
                </a>
              </li>
                            <!-- NEW: Contact Link -->
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=admin_contacts">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-mail"></i>
                  </span>
                  <span class="nav-link-title">Liên hệ</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=home">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-arrow-back-up"></i>
                  </span>
                  <span class="nav-link-title">Về trang chủ</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </aside>

      <!-- Navbar -->
      <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
                </span>
                <div class="d-none d-xl-block ps-2">
                  <div><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></div>
                  <div class="mt-1 small text-secondary">Administrator</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="index.php?page=profile" class="dropdown-item">Hồ sơ</a>
                <a href="index.php?page=logout" class="dropdown-item">Đăng xuất</a>
              </div>
            </div>
          </div>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <div></div>
          </div>
        </div>
      </header>

      <div class="page-wrapper">
        <!-- Page Content -->
        <?php
            // Load the specific view passed from Controller
            if (isset($view) && file_exists(__DIR__ . '/../' . $view . '.php')) {
                require_once __DIR__ . '/../' . $view . '.php';
            }
    ?>
        
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; 2025
                    <a href="." class="link-secondary">Company Name</a>.
                    All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/libs/apexcharts/dist/apexcharts.min.js" defer></script>
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/demo.min.js" defer></script>
  </body>
</html>
