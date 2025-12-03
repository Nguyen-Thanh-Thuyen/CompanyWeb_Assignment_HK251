<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Quản lý Liên hệ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
  </head>
  <body class="theme-light">
    <div class="page">
      <div class="page-wrapper">

        <div class="container-xl">

            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">Quản lý Liên hệ khách hàng</h2>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thông tin khách</th>
                                    <th>Nội dung tin nhắn</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($contacts) > 0): ?>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td>#<?php echo $contact['id']; ?></td>
                                            
                                            <td>
                                                <strong><?php echo htmlspecialchars($contact['full_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($contact['email']); ?></small><br>
                                                <small><?php echo htmlspecialchars($contact['phone']); ?></small>
                                            </td>

                                            <td style="max-width: 300px; white-space: normal;">
                                                <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                            </td>

                                            <td>
                                                <?php if ($contact['status'] == 'new'): ?>
                                                    <span class="badge bg-green text-green-fg">Mới</span>
                                                <?php elseif ($contact['status'] == 'read'): ?>
                                                    <span class="badge bg-secondary text-secondary-fg">Đã đọc</span>
                                                <?php else: ?>
                                                    <span class="badge bg-blue text-blue-fg">Đã trả lời</span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>

                                            <td>
                                                <?php if ($contact['status'] == 'new'): ?>
                                                    <a href="index.php?page=admin_contact_status&id=<?php echo $contact['id']; ?>&status=read" class="btn btn-sm btn-outline-primary">
                                                        Đã đọc
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?page=admin_contact_status&id=<?php echo $contact['id']; ?>&status=new" class="btn btn-sm btn-outline-secondary">
                                                        Chưa đọc
                                                    </a>
                                                <?php endif; ?>

                                                <a href="index.php?page=admin_contact_delete&id=<?php echo $contact['id']; ?>" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này không?');">
                                                    Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Chưa có liên hệ nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Navigation Buttons -->
            <div class="row mb-3 mt-3"> <div class="col-12 d-flex justify-content-between align-items-center">            
                    
                    <div class="btn-group">
                        <a href="index.php?page=admin_settings" class="btn btn-white">
                            Cấu hình Website
                        </a>
                        <a href="index.php?page=admin_contacts" class="btn btn-primary">
                            Quản lý Liên hệ
                        </a>                
                    </div>

                    <a href="index.php?page=home" class="btn btn-outline-secondary">
                        ← Trở về Trang chủ
                    </a>
                </div>
            </div>
            <!-- End Navigation Buttons -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
  </body>
</html>