<?php
require_once '../config.php';
include("xyly/xuly.php");

$p = new xuly();

$auto_code = 'DM-' . rand(100, 999);
//Xử lý  cập nhật được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Cập nhật danh mục':
            $myid          = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
            $category_code = $_REQUEST['category_code'];
            $category_name = $_REQUEST['category_name'];
            $description   = $_REQUEST['description'];

            $clean_code = trim($category_code);
            $clean_name = trim($category_name);

            if ($myid > 0) {
                if ($clean_code !== '' && $clean_name !== '') {

                    // Lấy ảnh cũ từ cơ sở dữ liệu để phòng khi không upload ảnh mới
                    $old_img_res = $p->xuatdulieu("SELECT image FROM categories WHERE id='$myid' LIMIT 1");
                    $imageName = !empty($old_img_res) ? $old_img_res[0]['image'] : NULL;

                    // Xử lý upload ảnh mới (nếu có chọn)
                    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
                        $tmp_name      = $_FILES['category_image']['tmp_name'];
                        $original_name = $_FILES['category_image']['name'];
                        $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                        $newImageName  = md5(time() . $original_name) . '.' . $fileExtension;
                        $folder        = '../uploads/anhdanhmuc';

                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        if ($p->uploadfile($newImageName, $tmp_name, $folder) == 1) {
                            $imageName = $newImageName;
                        }
                    }

                    $image_val = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                    $sql = "UPDATE categories SET category_code='$clean_code', name='$clean_name', 
                    description='$description', image=$image_val WHERE id='$myid' LIMIT 1";

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
            }
            break;
    }
}

// Lấy thông tin danh mục hiện tại theo ID trên URL để đổ ra form
if (isset($_REQUEST['id'])) {
    $layid = intval($_REQUEST['id']);
    $danhmuc_info = $p->xuatdulieu("SELECT * FROM categories WHERE id='$layid' LIMIT 1");
    if (!empty($danhmuc_info)) {
        $lay_code = $danhmuc_info[0]['category_code'];
        $lay_name = $danhmuc_info[0]['name'];
        $lay_desc = $danhmuc_info[0]['description'];
        $lay_img  = $danhmuc_info[0]['image'];
    } else {
        $layid = 0;
        $lay_code = '';
        $lay_name = '';
        $lay_desc = '';
        $lay_img = '';
    }
} else {
    $layid = 0;
    $lay_code = '';
    $lay_name = '';
    $lay_desc = '';
    $lay_img = '';
}

$pageTitle = "Cập nhật danh mục";

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Cập nhật danh mục</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/admin/danhsachdanhmuc.php">Danh Sách Danh Mục</a> / <span>Cập Nhật Danh Mục</span>
            </div>
        </div>

        <form id="updateCategoryForm" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="txtid" value="<?php echo $layid; ?>">

            <div class="product-form-grid">

                <div class="product-field-group">
                    <label>Mã danh mục</label>
                    <input type="text" name="category_code" class="product-input" value="<?php echo htmlspecialchars($lay_code); ?>" readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                </div>

                <div class="product-field-group">
                    <label>Tên danh mục</label>
                    <input type="text" name="category_name" class="product-input" value="<?php echo htmlspecialchars($lay_name); ?>" placeholder="Nhập tên danh mục..." required>
                </div>

                <div class="product-field-group span-4">
                    <label>Hình ảnh danh mục</label>
                    <div style="margin-bottom: 10px;">
                        <?php if (!empty($lay_img)): ?>
                            <img src="../uploads/anhdanhmuc/<?php echo htmlspecialchars($lay_img); ?>" alt="Category Image" class="product-img" style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <?php else: ?>
                            <span style="color: #64748b; font-style: italic;">Chưa có hình ảnh</span>
                        <?php endif; ?>
                    </div>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Thay đổi ảnh mới
                        <input type="file" name="category_image" style="display: none;" accept="image/*"
                            onchange="document.getElementById('hiententen').innerText = this.files.length > 0 ? this.files[0].name : ''">
                    </label>
                    <span id="hiententen" style="font-size: 14px; color: #0f172a; font-weight: 500; font-style: italic;"></span>
                </div>

                <div class="product-field-group span-4">
                    <label>Mô tả danh mục</label>
                    <textarea name="description" class="product-input" placeholder="Nhập mô tả chi tiết danh mục..."><?php echo htmlspecialchars($lay_desc); ?></textarea>
                </div>
            </div>

            <div class="product-form-actions">
                <button type="submit" class="btn-submit-save">
                    Lưu lại
                </button>

                <a href="<?php echo BASE_URL; ?>/admin/danhsachdanhmuc.php" class="btn-submit-cancel nutHuy"
                    data-type="danh mục"
                    data-redirect="<?php echo BASE_URL; ?>/admin/danhsachdanhmuc.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateForm = document.getElementById('updateCategoryForm');
        if (updateForm) {
            updateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Xác nhận cập nhật?',
                    text: "Bạn có muốn lưu các thay đổi cho danh mục này không?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, lưu lại!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'nut';
                        hiddenInput.value = 'Cập nhật danh mục';
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