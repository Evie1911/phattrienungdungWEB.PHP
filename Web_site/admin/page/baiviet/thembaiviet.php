<?php
require_once '../../config.php';

// Cấu hình tiêu đề trang
$pageTitle = "Thêm bài viết mới";

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
                <h1>Tạo mới bài viết</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/page/baiviet/baiviet.php">Danh Sách Bài Viết</a> / <span>Thêm Bài Viết</span>
            </div>
        </div>

        <div class="quick-action-group">
            <button type="button" class="btn-quick-action nut-them"
                data-title="Thêm danh mục bài viết"
                data-label="Tên danh mục bài viết"
                data-success="Đã thêm danh mục bài viết thành công!">
                <i class="fa-solid fa-plus"></i> Thêm danh mục bài viết
            </button>
        </div>

        <!-- Form chính -->
        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="product-form-grid">
                <!-- Mã bài viết -->
                <div class="product-field-group">
                    <label>Mã bài viết</label>
                    <input type="text" name="post_code" class="product-input" placeholder="Nhập mã bài viết (VD: BV-106)..." required>
                </div>

                <!-- Tiêu đề bài viết -->
                <div class="product-field-group">
                    <label>Tiêu đề bài viết</label>
                    <input type="text" name="post_title" class="product-input" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                <!-- Danh mục bài viết -->
                <div class="product-field-group">
                    <label>Danh mục bài viết</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Tin công nghệ</option>
                        <option value="2">Đánh giá sản phẩm</option>
                        <option value="3">Hướng dẫn - Thủ thuật</option>
                        <option value="4">Khuyến mãi - Sự kiện</option>
                    </select>
                </div>

                <!-- Trạng thái bài viết -->
                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input" required>
                        <option value="published">Xuất bản</option>
                        <option value="draft">Bản nháp</option>
                    </select>
                </div>

                <!-- Tác giả -->
                <div class="product-field-group">
                    <label>Tác giả</label>
                    <input type="text" name="author" class="product-input" placeholder="Nhập tên tác giả..." value="Nguyễn Văn An">
                </div>

                <!-- Ngày đăng -->
                <div class="product-field-group">
                    <label>Ngày đăng</label>
                    <input type="datetime-local" name="published_at" class="product-input" value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>

                <!-- Ảnh đại diện bài viết -->
                <div class="product-field-group span-4">
                    <label>Ảnh đại diện (Thumbnail)</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh đại diện
                        <input type="file" name="post_image" style="display: none;" accept="image/*">
                    </label>
                </div>

                <!-- Tóm tắt ngắn -->
                <div class="product-field-group span-4">
                    <label>Tóm tắt ngắn</label>
                    <textarea name="excerpt" class="product-input" placeholder="Nhập tóm tắt ngắn về nội dung bài viết..." style="height: 80px;"></textarea>
                </div>

                <!-- Nội dung chi tiết bài viết -->
                <div class="product-field-group span-4">
                    <label>Nội dung chi tiết</label>
                    <textarea name="content" class="product-input" placeholder="Nhập nội dung chi tiết bài viết..." style="height: 200px;"></textarea>
                </div>
            </div>

            <!-- Nút hành động Lưu & Hủy -->
            <div class="product-form-actions">
                <button type="submit" class="btn-submit-save nutLuu"
                    data-type="bài viết"
                    data-redirect="<?php echo BASE_URL; ?>/page/baiviet/baiviet.php">
                    Lưu lại
                </button>

                <!-- Nút hủy bỏ -->
                <a href=" index.php" class="btn-submit-cancel nutHuy"
                    data-type="bài viết"
                    data-redirect="<?php echo BASE_URL; ?>/page/baiviet/baiviet.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<?php
include '../../includes/footer.php';
?>