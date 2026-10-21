<?php
require_once '../config.php';
include("xyly/xuly.php");

$p = new xuly();

$auto_user_code = 'TK-' . rand(100, 999);

// Xử lý PHP khi form được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Thêm người dùng':
            $user_code = $_REQUEST['user_code'];
            $full_name = $_REQUEST['fullname'];
            $email     = $_REQUEST['email'];
            $raw_pass  = $_REQUEST['password'];
            $phone     = $_REQUEST['phone'];
            $role      = $_REQUEST['role'];
            $status    = $_REQUEST['status'];

            // Kiểm tra các thông tin bắt buộc
            if ($user_code !== '' && $full_name !== '' && $email !== '' && $raw_pass !== '' && $role !== '') {

                // Kiểm tra xem email đã tồn tại chưa
                $check_exist = $p->xuatdulieu("SELECT id FROM users WHERE email = '$email' LIMIT 1");
                if (!empty($check_exist)) {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Email hoặc mã tài khoản đã tồn tại trong hệ thống.'
                                });
                            });
                        </script>";
                } else {
                    // Mã hóa mật khẩu bằng password_hash()
                    $hashed_password = password_hash($raw_pass, PASSWORD_DEFAULT);

                    // Xử lý  uploadfile
                    $avatarName = NULL;
                    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                        $tmp_name      = $_FILES['avatar']['tmp_name'];
                        $original_name = $_FILES['avatar']['name'];
                        $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                        $avatarName    = md5(time() . $original_name) . '.' . $fileExtension;
                        $folder        = '../uploads/avatar';

                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        if ($p->uploadfile($avatarName, $tmp_name, $folder) != 1) {
                            $avatarName = NULL;
                        }
                    }

                    $avatar_val = ($avatarName !== NULL) ? "'$avatarName'" : "NULL";
                    $phone_val  = (!empty($phone)) ? "'$phone'" : "NULL";

                    $sql = "INSERT INTO users (user_code, full_name, email, password, phone, avatar, role, status, created_at) 
                            VALUES ('$user_code', '$full_name', '$email', '$hashed_password', $phone_val, $avatar_val, '$role', '$status', NOW())";

                    if ($p->themxoasua($sql)) {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: 'Thêm tài khoản người dùng thành công!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = 'qlnguoidung.php';
                                });
                            });
                        </script>";
                    } else {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi hệ thống!',
                                    text: 'Không thể thêm tài khoản vào cơ sở dữ liệu.'
                                });
                            });
                        </script>";
                    }
                }
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thiếu thông tin!',
                            text: 'Vui lòng nhập đầy đủ các thông tin bắt buộc.'
                        });
                    });
                </script>";
            }
            break;
    }
}

$pageTitle = "Thêm người dùng";

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo mới người dùng</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/qlnguoidung.php">Danh Sách Người Dùng</a> / <span>Thêm Người Dùng</span>
            </div>
        </div>

        <form id="userForm" action="" method="POST" enctype="multipart/form-data">

            <div class="product-form-grid">
                <div class="product-field-group">
                    <label>Mã tài khoản</label>
                    <input type="text" name="user_code" class="product-input" value="<?php echo $auto_user_code; ?>" readonly style="background-color: #f1f5f9; cursor: not-allowed;" required>
                </div>

                <div class="product-field-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullname" class="product-input" placeholder="Nhập họ và tên..." required>
                </div>

                <div class="product-field-group">
                    <label>Email</label>
                    <input type="email" name="email" class="product-input" placeholder="Nhập địa chỉ email..." required>
                </div>

                <div class="product-field-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="product-input" placeholder="Nhập mật khẩu..." required>
                </div>

                <div class="product-field-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="product-input" placeholder="Nhập số điện thoại...">
                </div>

                <div class="product-field-group">
                    <label>Quyền hạn</label>
                    <select name="role" class="product-input" required>
                        <option value="">-- Chọn quyền --</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                        <option value="customer" selected>Khách hàng</option>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input">
                        <option value="active" selected>Hoạt động</option>
                        <option value="locked">Khóa tài khoản</option>
                    </select>
                </div>

                <div class="product-field-group span-4">
                    <label>Ảnh đại diện</label>
                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <label class="btn-file-upload" style="margin-bottom: 0;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh đại diện
                            <input type="file" name="avatar" style="display: none;" accept="image/*"
                                onchange="document.getElementById('hiententen').innerText = this.files.length > 0 ? this.files[0].name : ''">
                        </label>
                        <span id="hiententen" style="font-size: 14px; color: #0f172a; font-weight: 500; font-style: italic;"></span>
                    </div>
                </div>
            </div>

            <div class="product-form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-submit-save">Lưu lại</button>
                <a href="<?php echo BASE_URL; ?>/qlnguoidung.php" class="btn-submit-cancel nutHuy"
                    data-type="tài khoản"
                    data-redirect="<?php echo BASE_URL; ?>/qlnguoidung.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userForm = document.getElementById('userForm');
        if (userForm) {
            userForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Xác nhận thêm tài khoản?',
                    text: "Bạn có muốn thêm người dùng này vào hệ thống không?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, thêm ngay!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'nut';
                        hiddenInput.value = 'Thêm người dùng';
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        }
    });
</script>

<?php
include("includes/footer.php");
?>