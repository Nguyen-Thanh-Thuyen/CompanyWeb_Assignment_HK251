<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - <?php echo $settings['company_name']; ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background: #f4f6f8; }
        .container { max-width: 800px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 0.9em; display: none; }
        .nav { padding: 20px; text-align: center; background: #fff; border-bottom: 1px solid #ddd;}
        .nav a { margin: 0 15px; text-decoration: none; color: #333; font-weight: bold;}
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php?page=home">Trang chủ</a>
        <a href="index.php?page=contact">Liên hệ</a>
        <a href="index.php?page=admin_settings" style="color:red">[Admin]</a>
    </div>

    <div class="container">
        <h2>Gửi liên hệ cho chúng tôi</h2>
        
        <form id="contactForm" action="index.php?page=contact_submit" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Họ và tên (*)</label>
                <input type="text" name="full_name" id="full_name">
                <span class="error" id="nameError">Vui lòng nhập họ tên.</span>
            </div>

            <div class="form-group">
                <label>Email (*)</label>
                <input type="email" name="email" id="email">
                <span class="error" id="emailError">Email không đúng định dạng.</span>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div class="form-group">
                <label>Nội dung (*)</label>
                <textarea name="message" id="message" rows="5"></textarea>
                <span class="error" id="msgError">Vui lòng nhập nội dung tin nhắn.</span>
            </div>

            <button type="submit">Gửi tin nhắn</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;
            
            // Lấy giá trị
            const name = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            // Reset lỗi
            document.querySelectorAll('.error').forEach(e => e.style.display = 'none');

            // Kiểm tra Tên
            if (name === "") {
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            }

            // Kiểm tra Email (Regex đơn giản)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }

            // Kiểm tra nội dung
            if (message === "") {
                document.getElementById('msgError').style.display = 'block';
                isValid = false;
            }

            return isValid; // Nếu false thì form sẽ không gửi đi
        }
    </script>
</body>
</html>