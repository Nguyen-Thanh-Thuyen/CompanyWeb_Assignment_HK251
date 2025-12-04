<!-- views/client/contact.php -->

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Trang chủ</a></li>
            <li class="breadcrumb-item active">Liên hệ</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-center"><i class="bi bi-envelope-paper-fill me-2"></i>Gửi liên hệ cho chúng tôi</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form id="contactForm" action="index.php?page=contact_submit" method="POST" onsubmit="return validateForm()">
                        
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nhập họ tên của bạn">
                            <div class="text-danger small mt-1" id="nameError" style="display:none;">
                                <i class="bi bi-exclamation-circle"></i> Vui lòng nhập họ tên.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="email@example.com">
                            <div class="text-danger small mt-1" id="emailError" style="display:none;">
                                <i class="bi bi-exclamation-circle"></i> Email không đúng định dạng.
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại liên hệ">
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label for="message" class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" class="form-control" placeholder="Nội dung cần hỗ trợ..."></textarea>
                            <div class="text-danger small mt-1" id="msgError" style="display:none;">
                                <i class="bi bi-exclamation-circle"></i> Vui lòng nhập nội dung tin nhắn.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill me-2"></i>Gửi tin nhắn
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        
        <!-- Contact Info Side (Optional) -->
        <div class="col-md-4 d-none d-md-block">
            <div class="card border-0 h-100 bg-light">
                <div class="card-body p-4">
                    <h5 class="mb-4 text-primary">Thông tin liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i> 
                            <strong>Địa chỉ:</strong><br>
                            123 Đường ABC, Quận 1, TP.HCM
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-telephone-fill text-primary me-2"></i> 
                            <strong>Hotline:</strong><br>
                            1900 123 456
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-envelope-fill text-primary me-2"></i> 
                            <strong>Email:</strong><br>
                            hotro@company.com
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        let isValid = true;
        
        // Get values
        const name = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();

        // Reset errors
        document.getElementById('nameError').style.display = 'none';
        document.getElementById('emailError').style.display = 'none';
        document.getElementById('msgError').style.display = 'none';
        
        // Remove invalid classes if using bootstrap validation visual cues
        document.getElementById('full_name').classList.remove('is-invalid');
        document.getElementById('email').classList.remove('is-invalid');
        document.getElementById('message').classList.remove('is-invalid');

        // Check Name
        if (name === "") {
            document.getElementById('nameError').style.display = 'block';
            document.getElementById('full_name').classList.add('is-invalid');
            isValid = false;
        }

        // Check Email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            document.getElementById('emailError').style.display = 'block';
            document.getElementById('email').classList.add('is-invalid');
            isValid = false;
        }

        // Check Message
        if (message === "") {
            document.getElementById('msgError').style.display = 'block';
            document.getElementById('message').classList.add('is-invalid');
            isValid = false;
        }

        return isValid;
    }
</script>
