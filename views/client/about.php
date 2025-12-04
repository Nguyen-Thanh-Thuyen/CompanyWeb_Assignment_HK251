<!-- views/client/about.php -->

<!-- Hero Section -->
<div class="bg-light py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-primary mb-3">Về Chúng Tôi</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Chúng tôi không chỉ bán sản phẩm, chúng tôi mang đến giải pháp công nghệ và trải nghiệm mua sắm tuyệt vời nhất cho bạn.
        </p>
    </div>
</div>

<div class="container mb-5">
    <!-- Story Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" 
                 class="img-fluid rounded shadow" alt="Our Team">
        </div>
        <div class="col-md-6">
            <h2 class="fw-bold mb-3">Câu Chuyện Khởi Nghiệp</h2>
            <p class="text-secondary lh-lg">
                Được thành lập vào năm 2023, <?php echo htmlspecialchars($settings['company_name'] ?? 'Company'); ?> bắt đầu từ một cửa hàng nhỏ với niềm đam mê cháy bỏng về công nghệ. Chúng tôi nhận thấy rằng khách hàng không chỉ cần một thiết bị, họ cần một người bạn đồng hành tin cậy để hỗ trợ công việc và giải trí.
            </p>
            <p class="text-secondary lh-lg">
                Trải qua nhiều năm phát triển, chúng tôi tự hào là đơn vị cung cấp các sản phẩm chính hãng, chất lượng cao với dịch vụ hậu mãi tận tâm nhất. Sứ mệnh của chúng tôi là "Công nghệ vị nhân sinh" - đưa công nghệ đến gần hơn với mọi người.
            </p>
            <div class="mt-4">
                <div class="row text-center">
                    <div class="col-4">
                        <h3 class="fw-bold text-primary">5+</h3>
                        <small>Năm kinh nghiệm</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-primary">10k+</h3>
                        <small>Khách hàng</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-primary">100%</h3>
                        <small>Hài lòng</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="row g-4 py-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Giá Trị Cốt Lõi</h2>
            <p class="text-muted">Điều làm nên sự khác biệt của chúng tôi</p>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="text-primary mb-3">
                    <i class="bi bi-shield-check display-4"></i>
                </div>
                <h5 class="card-title fw-bold">Chất Lượng Đảm Bảo</h5>
                <p class="card-text text-muted">Cam kết 100% sản phẩm chính hãng, nguồn gốc xuất xứ rõ ràng và chế độ bảo hành uy tín.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="text-primary mb-3">
                    <i class="bi bi-heart-pulse display-4"></i>
                </div>
                <h5 class="card-title fw-bold">Tận Tâm Phục Vụ</h5>
                <p class="card-text text-muted">Đội ngũ tư vấn viên nhiệt tình, hỗ trợ kỹ thuật 24/7, luôn lắng nghe mọi phản hồi của khách hàng.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="text-primary mb-3">
                    <i class="bi bi-lightning-charge display-4"></i>
                </div>
                <h5 class="card-title fw-bold">Giao Hàng Thần Tốc</h5>
                <p class="card-text text-muted">Hệ thống kho bãi rộng khắp giúp việc giao hàng nhanh chóng, an toàn đến tận tay người tiêu dùng.</p>
            </div>
        </div>
    </div>

    <!-- Team Section (Optional) -->
    <div class="py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Đội Ngũ Lãnh Đạo</h2>
        </div>
        <div class="row justify-content-center">
            <!-- Member 1 -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-0 text-center">
                    <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=0D8ABC&color=fff&size=200" class="card-img-top rounded-circle mx-auto mt-3" style="width: 120px; height: 120px;" alt="CEO">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Nguyễn Văn A</h5>
                        <p class="text-muted small">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <!-- Member 2 -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-0 text-center">
                    <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=E91E63&color=fff&size=200" class="card-img-top rounded-circle mx-auto mt-3" style="width: 120px; height: 120px;" alt="CMO">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Trần Thị B</h5>
                        <p class="text-muted small">Marketing Director</p>
                    </div>
                </div>
            </div>
            <!-- Member 3 -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-0 text-center">
                    <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=4CAF50&color=fff&size=200" class="card-img-top rounded-circle mx-auto mt-3" style="width: 120px; height: 120px;" alt="CTO">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Lê Văn C</h5>
                        <p class="text-muted small">Head of Tech</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center py-5">
        <h3>Bạn đã sẵn sàng trải nghiệm?</h3>
        <p class="text-muted mb-4">Hãy ghé thăm cửa hàng của chúng tôi ngay hôm nay.</p>
        <a href="index.php?page=product_list" class="btn btn-primary btn-lg px-5">Mua sắm ngay</a>
    </div>
</div>
