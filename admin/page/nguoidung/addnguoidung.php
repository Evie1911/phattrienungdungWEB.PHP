<?php
require_once '../../config.php';

// Cấu hình tiêu đề trang
$pageTitle = "Thêm người dùng";

// Nhúng các thành phần giao diện chung
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo mới người dùng</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/page/nguoidung/qlnguoidung.php">Danh Sách Người Dùng</a> / <span>Thêm Người Dùng</span>
            </div>
        </div>

        <!-- Form chính -->
        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="product-form-grid">
                <div class="product-field-group">
                    <label>Mã tài khoản</label>
                    <input type="text" name="user_code" class="product-input" placeholder="Ví dụ: TK-01..." required>
                </div>

                <!-- Họ và tên -->
                <div class="product-field-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullname" class="product-input" placeholder="Nhập họ và tên..." required>
                </div>

                <!-- Email -->
                <div class="product-field-group">
                    <label>Email</label>
                    <input type="email" name="email" class="product-input" placeholder="Nhập địa chỉ email..." required>
                </div>

                <!-- Mật khẩu -->
                <div class="product-field-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="product-input" placeholder="Nhập mật khẩu..." required>
                </div>

                <!-- Số điện thoại -->
                <div class="product-field-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="product-input" placeholder="Nhập số điện thoại...">
                </div>

                <!-- Quyền (Role) -->
                <div class="product-field-group">
                    <label>Quyền hạn</label>
                    <select name="role" class="product-input">
                        <option value="">-- Chọn quyền --</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                        <option value="customer">Khách hàng</option>
                    </select>
                </div>

                <!-- Trạng thái tài khoản -->
                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input">
                        <option value="active" selected>Hoạt động</option>
                        <option value="locked">Khóa tài khoản</option>
                    </select>
                </div>

                <!-- Ảnh đại diện -->
                <div class="product-field-group span-4">
                    <label>Ảnh đại diện</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh đại diện
                        <input type="file" name="avatar" style="display: none;" accept="image/*">
                    </label>
                </div>
            </div>

            <!-- Nút hành động Lưu & Hủy -->
            <div class="product-form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-submit-save nutLuu"
                    data-type="tài khoản"
                    data-redirect="<?php echo BASE_URL; ?>/page/nguoidung/qlnguoidung.php">
                    Lưu lại
                </button>

                <!-- Nút hủy bỏ -->
                <a href=" index.php" class="btn-submit-cancel nutHuy"
                    data-type="tài khoản"
                    data-redirect="<?php echo BASE_URL; ?>/page/nguoidung/qlnguoidung.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<?php
include '../../includes/footer.php';
?>