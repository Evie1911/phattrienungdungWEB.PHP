<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập & Quên mật khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dangnhap.css">

</head>

<body>

    <!-- Radio Controls ẩn để chuyển trạng thái giao diện-->
    <input type="radio" name="auth_state" id="state-login" class="auth-state-radio" checked>
    <input type="radio" name="auth_state" id="state-forgot" class="auth-state-radio">
    <input type="radio" name="auth_state" id="state-otp" class="auth-state-radio">

    <div class="bg-diagonal"></div>
    <main class="login-container">
        <div class="login-body">
            <div class="illustration-col">
                <img
                    src="https://admin.thuevpsgiare.vn/wp-content/uploads/2025/02/Ung-dung-thuc-te-cua-Clone.png"
                    alt="Hệ thống POS"
                    class="pos-image">
            </div>
            <!-- Cột Form xử lý -->
            <div class="form-col">
                <!-- 1. GIAO DIỆN ĐĂNG NHẬP -->
                <div id="loginSection" class="auth-form">
                    <h1 class="form-title">ĐĂNG NHẬP HỆ THỐNG</h1>
                    <form id="loginForm" action="<?php echo BASE_URL; ?>/page/thongke/thongke.php" method="POST">
                        <div class="input-group">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                placeholder="Tài khoản quản trị"
                                required
                                autocomplete="username">
                        </div>
                        <div class="input-group">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Mật khẩu"
                                required
                                autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn-submit">
                            Đăng nhập
                        </button>
                        <div class="forgot-password-wrap">
                            <label for="state-forgot" id="forgotPasswordLink" style="cursor: pointer;">Bạn quên mật khẩu?</label>
                        </div>
                    </form>
                </div>

                <!-- 2. GIAO DIỆN QUÊN MẬT KHẨU - NHẬP EMAIL -->
                <div id="forgotSection" class="auth-form">
                    <h1 class="form-title">QUÊN MẬT KHẨU</h1>
                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">Vui lòng nhập Email để nhận mã xác nhận khôi phục mật khẩu.</p>
                    <form id="forgotForm">
                        <div class="input-group">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input
                                type="email"
                                name="email"
                                placeholder="Nhập địa chỉ Email"
                                required>
                        </div>
                        <label for="state-otp" class="btn-submit btn-label">
                            Gửi mã xác nhận
                        </label>
                        <div style="text-align: center;">
                            <label for="state-login" class="back-to-login">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại Đăng nhập
                            </label>
                        </div>
                    </form>
                </div>

                <!-- 3. GIAO DIỆN NHẬP MÃ XÁC NHẬN (OTP) -->
                <div id="otpSection" class="auth-form">
                    <h1 class="form-title">XÁC NHẬN MÃ OTP</h1>
                    <p style="font-size: 14px; color: #6b7280;">Nhập mã xác nhận gồm 4 chữ số đã được gửi tới Email của bạn.</p>
                    <form id="verifyForm">
                        <div class="code-inputs">
                            <input type="text" maxlength="1" required>
                            <input type="text" maxlength="1" required>
                            <input type="text" maxlength="1" required>
                            <input type="text" maxlength="1" required>
                        </div>
                        <label for="state-login" class="btn-submit btn-label">
                            Xác nhận
                        </label>
                        <div style="text-align: center;">
                            <label for="state-login" class="back-to-login">
                                <i class="fa-solid fa-arrow-left"></i> Hủy & Quay lại
                            </label>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <footer class="login-footer">
            <p>Phần mềm quản lý bán hàng &copy; <?php echo date('Y'); ?></p>
        </footer>
    </main>

</body>

</html>