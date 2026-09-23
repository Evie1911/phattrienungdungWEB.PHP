<?php
require_once '../../config.php';

// Cấu hình tiêu đề trang
$pageTitle = "Cập nhật bài viết";

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
                <h1>Cập nhật bài viết: BV-101</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/page/baiviet/baiviet.php">Danh Sách Bài Viết</a> / <span>Cập Nhật Bài Viết</span>
            </div>
        </div>

        <!-- Các nút bổ sung nhanh -->
        <div class="quick-action-group">
            <button type="button" class="btn-quick-action">
                <i class="fa-solid fa-plus"></i> Thêm danh mục bài viết
            </button>
        </div>

        <!-- Form chính -->
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="product-form-grid">
                <!-- Mã bài viết -->
                <div class="product-field-group">
                    <label>Mã bài viết</label>
                    <input type="text" name="post_code" class="product-input" value="BV-101" placeholder="Nhập mã bài viết..." required>
                </div>

                <!-- Tiêu đề bài viết -->
                <div class="product-field-group">
                    <label>Tiêu đề bài viết</label>
                    <input type="text" name="post_title" class="product-input" value="Đánh giá chi tiết iPhone 15 Pro Max: Đỉnh cao công nghệ Apple" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                <!-- Danh mục bài viết -->
                <div class="product-field-group">
                    <label>Danh mục bài viết</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Tin công nghệ</option>
                        <option value="2" selected>Đánh giá sản phẩm</option>
                        <option value="3">Hướng dẫn - Thủ thuật</option>
                        <option value="4">Khuyến mãi - Sự kiện</option>
                    </select>
                </div>

                <!-- Trạng thái bài viết -->
                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input" required>
                        <option value="published" selected>Xuất bản</option>
                        <option value="draft">Bản nháp</option>
                    </select>
                </div>

                <!-- Hiển thị / Ẩn bài viết -->
                <div class="product-field-group">
                    <label>Hiển thị / Ẩn</label>
                    <select name="visibility" class="product-input" required>
                        <option value="public" selected>Hiển thị (Công khai)</option>
                        <option value="hidden">Ẩn (Không hiển thị)</option>
                    </select>
                </div>

                <!-- Tác giả -->
                <div class="product-field-group">
                    <label>Tác giả</label>
                    <input type="text" name="author" class="product-input" value="Nguyễn Văn An" placeholder="Nhập tên tác giả...">
                </div>

                <!-- Ngày đăng -->
                <div class="product-field-group">
                    <label>Ngày đăng</label>
                    <input type="datetime-local" name="published_at" class="product-input" value="2026-06-10T08:30">
                </div>

                <!-- Ảnh đại diện bài viết -->
                <div class="product-field-group span-4">
                    <label>Ảnh đại diện (Thumbnail)</label>
                    <div>
                        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=100&auto=format&fit=crop&q=80" style="width: 100px; height: 50px;" alt="Adapter" class="product-img">
                    </div>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Thay đổi ảnh đại diện
                        <input type="file" name="post_image" style="display: none;" accept="image/*">
                    </label>
                </div>

                <!-- Tóm tắt ngắn -->
                <div class="product-field-group span-4">
                    <label>Tóm tắt ngắn</label>
                    <textarea name="excerpt" class="product-input" placeholder="Nhập tóm tắt ngắn về nội dung bài viết..." style="height: 80px;">Đánh giá tổng quan về thiết kế, hiệu năng khủng với chip A17 Pro và camera tiềm vọng trên iPhone 15 Pro Max.</textarea>
                </div>

                <!-- Nội dung chi tiết bài viết -->
                <div class="product-field-group span-4">
                    <label>Nội dung chi tiết</label>
                    <textarea name="content" class="product-input" placeholder="Nhập nội dung chi tiết bài viết..." style="height: 200px;">Nội dung chi tiết bài viết đánh giá iPhone 15 Pro Max được cập nhật ở đây...</textarea>
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