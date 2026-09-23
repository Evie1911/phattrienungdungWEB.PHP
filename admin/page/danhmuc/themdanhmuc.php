<?php
require_once '../../config.php';

// Cấu hình tiêu đề trang
$pageTitle = "Thêm danh mục";

// Nhúng các thành phần giao diện chung
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <!-- Tiêu đề và đường dẫn -->
        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo mới danh mục</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/page/danhmuc/danhsachdanhmuc.php">Danh Sách Danh Mục</a> / <span>Thêm Danh Mục</span>
            </div>
        </div>

        <!-- Form chính -->
        <form action="#" method="POST" enctype="multipart/form-data">

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
                <button type="submit" class="btn-submit-save nutLuu"
                    data-type="danh mục"
                    data-redirect="<?php echo BASE_URL; ?>/page/danhmuc/danhsachdanhmuc.php">
                    Lưu lại
                </button>

                <!-- Nút hủy bỏ -->
                <a href=" index.php" class="btn-submit-cancel nutHuy"
                    data-type="danh mục"
                    data-redirect="<?php echo BASE_URL; ?>/page/danhmuc/danhsachdanhmuc.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<?php
include '../../includes/footer.php';
?>