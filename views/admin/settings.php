<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Cấu hình Website - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
  </head>
  <body class="theme-light">
    <div class="page">
      <div class="page-wrapper">
        
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Quản lý Website
                </h2>
              </div>
            </div>
          </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cấu hình thông tin Website</h3>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    
                                    <input type="hidden" name="id" value="<?php echo $settings['id']; ?>">
                                    <input type="hidden" name="current_logo" value="<?php echo $settings['logo_path']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Tên công ty / Doanh nghiệp</label>
                                        <input type="text" class="form-control" name="company_name" value="<?php echo $settings['company_name']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Logo hiện tại</label>
                                        <br>
                                        <?php if(!empty($settings['logo_path'])): ?>
                                            <img src="<?php echo BASE_URL . '/public/uploads/' . $settings['logo_path']; ?>" width="150">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thay đổi Logo</label>
                                        <input type="file" class="form-control" name="logo">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo $settings['phone_number']; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" value="<?php echo $settings['address']; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Giới thiệu về công ty</label>
                                        <textarea class="form-control" name="intro_text" rows="5"><?php echo $settings['intro_text']; ?></textarea>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Navigation Buttons -->
                <div class="row mb-3 mt-3"> <div class="col-12 d-flex justify-content-between align-items-center">                        
                        <div class="btn-group">                            
                            <a href="index.php?page=admin_settings" class="btn btn-primary">
                                Cấu hình Website
                            </a>
                            <a href="index.php?page=admin_contacts" class="btn btn-white">
                                Quản lý Liên hệ
                            </a>                            
                        </div>
                        <a href="index.php?page=home" class="btn btn-outline-secondary">
                                ← Trở về Trang chủ
                        </a>                                    
                    </div>
                </div>
                <!-- end Navigation Buttons -->
            </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
  </body>
</html>