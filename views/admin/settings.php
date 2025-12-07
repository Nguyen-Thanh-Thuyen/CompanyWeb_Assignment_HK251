<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Cấu hình Website</h2>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row row-cards">
      <div class="col-12">
        <form action="" method="POST" enctype="multipart/form-data" class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin chung</h4>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="<?php echo $settings['id']; ?>">
                <input type="hidden" name="current_logo" value="<?php echo $settings['logo_path']; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tên công ty / Doanh nghiệp</label>
                            <input type="text" class="form-control" name="company_name" value="<?php echo htmlspecialchars($settings['company_name']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email liên hệ</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($settings['email']); ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($settings['phone_number']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($settings['address']); ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Logo Website</label>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <?php if(!empty($settings['logo_path'])): ?>
                                        <span class="avatar avatar-xl" style="background-image: url('public/uploads/<?php echo $settings['logo_path']; ?>')"></span>
                                    <?php else: ?>
                                        <span class="avatar avatar-xl">N/A</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control" name="logo">
                                    <div class="form-text">Định dạng hỗ trợ: jpg, png, webp. Kích thước tối đa: 2MB.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Giới thiệu ngắn (Footer)</label>
                            <textarea class="form-control" name="intro_text" rows="3"><?php echo htmlspecialchars($settings['intro_text']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header border-top">
                <h4 class="card-title">Mạng xã hội</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-brand-facebook"></i></span>
                            <input type="text" class="form-control" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-brand-twitter"></i></span>
                            <input type="text" class="form-control" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-brand-instagram"></i></span>
                            <input type="text" class="form-control" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i> Lưu cấu hình
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
