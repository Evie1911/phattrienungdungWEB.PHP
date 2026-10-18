<?php
require_once '../../config.php';

// Cấu hình tiêu đề trang
$pageTitle = "Thêm sản phẩm";

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
                <h1>Tạo mới sản phẩm</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/page/sanpham/danhsachsanpham.php">Danh Sách Sản Phẩm</a> / <span>Thêm Sản Phẩm</span>
            </div>
        </div>

        <!-- Các nút bổ sung nhanh -->
        <div class="quick-action-group">
            <button type="button" class="btn-quick-action nut-them"
                data-title="Thêm nhà cung cấp mới"
                data-label="Tên nhà cung cấp"
                data-success="Đã thêm nhà cung cấp thành công!">
                <i class="fa-solid fa-plus"></i> Thêm nhà cung cấp
            </button>

            <button type="button" class="btn-quick-action nut-them"
                data-title="Thêm danh mục mới"
                data-label="Tên danh mục"
                data-success="Đã thêm danh mục thành công!">
                <i class="fa-solid fa-plus"></i> Thêm danh mục
            </button>

            <button type="button" class="btn-quick-action nut-them"
                data-title="Thêm tình trạng mới"
                data-label="Tên tình trạng"
                data-success="Đã thêm tình trạng thành công!">
                <i class="fa-solid fa-plus"></i> Thêm tình trạng
            </button>
        </div>

        <!-- Form chính -->
        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="product-form-grid">
                <!-- Mã sản phẩm -->
                <div class="product-field-group">
                    <label>Mã sản phẩm</label>
                    <input type="text" name="product_code" class="product-input" placeholder="Nhập mã sản phẩm..." required>
                </div>

                <!-- Tên sản phẩm -->
                <div class="product-field-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="product_name" class="product-input" placeholder="Nhập tên sản phẩm..." required>
                </div>

                <!-- Số lượng -->
                <div class="product-field-group">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" class="product-input" value="0" min="0">
                </div>

                <!-- Tình trạng -->
                <div class="product-field-group">
                    <label>Tình trạng</label>
                    <select name="status_id" class="product-input">
                        <option value="">-- Chọn tình trạng --</option>
                        <option value="1">Mới 100%</option>
                        <option value="2">Hàng trưng bày</option>
                        <option value="3">Đã qua sử dụng</option>
                    </select>
                </div>

                <!-- Danh mục -->
                <div class="product-field-group">
                    <label>Danh mục</label>
                    <select name="category_id" class="product-input">
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Điện thoại di động</option>
                        <option value="2">Máy tính bảng</option>
                        <option value="3">Phụ kiện điện thoại</option>
                    </select>
                </div>

                <!-- Nhà cung cấp -->
                <div class="product-field-group">
                    <label>Nhà cung cấp</label>
                    <select name="supplier_id" class="product-input">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <option value="1">Apple Authorized</option>
                        <option value="2">Samsung Vina</option>
                        <option value="3">Xiaomi Global</option>
                    </select>
                </div>

                <!-- Giá bán -->
                <div class="product-field-group">
                    <label>Giá bán</label>
                    <input type="text" name="price" class="product-input" placeholder="0 đ">
                </div>

                <!-- Giá giảm -->
                <div class="product-field-group">
                    <label>Giá giảm</label>
                    <input type="text" name="discount_price" class="product-input" placeholder="0 đ">
                </div>

                <!-- Ảnh sản phẩm -->
                <div class="product-field-group span-4">
                    <label>Ảnh sản phẩm</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh
                        <input type="file" name="product_image" style="display: none;" accept="image/*">
                    </label>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="product-field-group span-4">
                    <label>Mô tả sản phẩm</label>
                    <textarea name="description" class="product-input" placeholder="Nhập mô tả chi tiết sản phẩm..."></textarea>
                </div>
            </div>

            <!-- Nút hành động Lưu & Hủy -->
            <div class="product-form-actions">
                <!-- Nút lưu sản phẩm -->
                <button type="submit" class="btn-submit-save nutLuu"
                    data-type="sản phẩm"
                    data-redirect="<?php echo BASE_URL; ?>/page/sanpham/danhsachsanpham.php">
                    Lưu lại
                </button>

                <!-- Nút hủy bỏ -->
                <a href=" index.php" class="btn-submit-cancel nutHuy"
                    data-type="sản phẩm"
                    data-redirect="<?php echo BASE_URL; ?>/page/sanpham/danhsachsanpham.php"">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<?php
include '../../includes/footer.php';
?>