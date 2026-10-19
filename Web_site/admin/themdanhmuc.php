<?php
require_once '../config.php';
include("xyly/xuly.php");

$p = new xuly();

// Xử lý PHP khi form danh mục được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Thêm danh mục':
            $category_code = $_REQUEST['category_code'];
            $category_name = $_REQUEST['category_name'];
            $description   = $_REQUEST['description'];

            //Điện Thoại & Phụ Kiện! thành dien-thoai-phu-kien
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $category_name), '-')) . '-' . rand(1000, 9999);
            if ($category_code !== '' && $category_name !== '') {

                // Xử lý upload ảnh sử dụng phương thức uploadfile
                $imageName = NULL;
                if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name      = $_FILES['category_image']['tmp_name'];
                    $original_name = $_FILES['category_image']['name'];
                    $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                    $imageName     = md5(time() . $original_name) . '.' . $fileExtension;
                    $folder        = '../uploads/anhdanhmuc';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    if ($p->uploadfile($imageName, $tmp_name, $folder) != 1) {
                        $imageName = NULL;
                    }
                }

                $image_val = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                // Câu lệnh INSERT vào bảng categories
                $sql = "INSERT INTO categories (category_code, name, slug, description, image, status) 
                        VALUES ('$category_code', '$category_name', '$slug', '$description', $image_val, 1)";

                // Thông báo kết quả bằng SweetAlert2 và chuyển hướng sạch
                if ($p->themxoasua($sql)) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Thêm danh mục thành công!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'danhsachdanhmuc.php';
                            });
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Không thành công khi thêm sản phẩm vào cơ sở dữ liệu.'
                            });
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thiếu thông tin!',
                            text: 'Vui lòng nhập đầy đủ thông tin bắt buộc.'
                        });
                    });
                </script>";
            }
            break;
    }
}

// Cấu hình tiêu đề trang
$pageTitle = "Thêm danh mục";

// Nhúng các thành phần giao diện chung
include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>


<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <!-- Tiêu đề và đường dẫn -->
        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo mới danh mục</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/danhsachdanhmuc.php">Danh Sách Danh Mục</a> / <span>Thêm Danh Mục</span>
            </div>
        </div>

        <!-- Form chính -->
        <form id="categoryForm" action="" method="POST" enctype="multipart/form-data">
            <div class="product-form-grid">
                <!-- Mã danh mục -->
                <div class="product-field-group">
                    <label>Mã danh mục</label>
                    <input type="text" name="category_code" class="product-input" placeholder="Ví dụ: DM-11..." required>
                </div>

                <!-- Tên danh mục -->
                <div class="product-field-group">
                    <label>Tên danh mục</label>
                    <input type="text" name="category_name" class="product-input" placeholder="Nhập tên danh mục..." required>
                </div>

                <!-- Ảnh danh mục -->
                <div class="product-field-group span-4">
                    <label>Hình ảnh danh mục</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh
                        <input type="file" name="category_image" style="display: none;" accept="image/*">
                    </label>
                </div>

                <!-- Mô tả danh mục -->
                <div class="product-field-group span-4">
                    <label>Mô tả danh mục</label>
                    <textarea name="description" class="product-input" placeholder="Nhập mô tả chi tiết danh mục..."></textarea>
                </div>
            </div>

            <!-- Nút hành động Lưu & Hủy -->
            <div class="product-form-actions">
                <button type="submit" class="btn-submit-save">Lưu lại</button>
                <a href="<?php echo BASE_URL; ?>/danhsachdanhmuc.php" class="btn-submit-cancel nutHuy"
                    data-type="danh mục"
                    data-redirect="<?php echo BASE_URL; ?>/danhsachdanhmuc.php">
                    Hủy bỏ
                </a>
            </div>
        </form>

    </div>
</main>
<!-- JavaScript xử lý hộp thoại xác nhận form danh mục -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Xác nhận thêm danh mục?',
                    text: "Bạn có muốn thêm danh mục này vào hệ thống không?",
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
                        hiddenInput.value = 'Thêm danh mục';
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