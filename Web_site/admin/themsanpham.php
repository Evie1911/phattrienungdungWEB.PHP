<?php
require_once '../config.php';
include("xyly/xuly.php");

$db = new xuly();

// Xử lý PHP khi form được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Thêm sản phẩm':
            $sku            = $_REQUEST['product_code'];
            $name           = $_REQUEST['product_name'];
            $category_id    = $_REQUEST['category_id'];
            $brand_id       = $_REQUEST['brand_id'];
            $supplier_id    = $_REQUEST['supplier_id'];
            $stock          = $_REQUEST['quantity'];
            $price          = $_REQUEST['price'];
            $sale_price     = $_REQUEST['discount_price'];
            $condition_text = $_REQUEST['condition_text'];
            $description    = $_REQUEST['description'];

            $clean_stock = intval($stock); //so luong kho lam sach thanh so nguyen

            // Kiểm tra thông tin bắt buộc
            if ($sku != '' && $name != '' && $category_id != '' && $brand_id != '' && $price != '' && $stock !== '') {

                // Xử lý upload ảnh sử dụng phương thức uploadfile
                $imageName = NULL;
                if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name      = $_FILES['product_image']['tmp_name'];
                    $original_name = $_FILES['product_image']['name'];
                    $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                    $imageName     = md5(time() . $original_name) . '.' . $fileExtension;
                    $folder        = '../uploads/imagesanpham';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    if ($db->uploadfile($imageName, $tmp_name, $folder) != 1) {
                        $imageName = NULL;
                    }
                }

                // Xử lý giá trị có thể NULL
                $supplier_val   = (!empty($supplier_id)) ? "'$supplier_id'" : "NULL";
                $sale_price_val = (!empty($sale_price)) ? "'$sale_price'" : "NULL";
                $image_val      = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                $clean_price    = floatval(str_replace(['.', ','], '', $price));
                $clean_sale     = !empty($sale_price) ? floatval(str_replace(['.', ','], '', $sale_price)) : 0;

                // Câu lệnh INSERT
                $sql = "INSERT INTO products (sku, category_id, brand_id, supplier_id, name, description, price, sale_price, stock, condition_text, image, created_at, updated_at) 
                        VALUES ('$sku', '$category_id', '$brand_id', $supplier_val, '$name', '$description', '$clean_price', " . ($clean_sale ? "'$clean_sale'" : "NULL") . ", 
                        '$clean_stock', '$condition_text', $image_val, NOW(), NOW())";

                // Thông báo kết quả bằng SweetAlert2
                if ($db->themxoasua($sql)) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Thêm sản phẩm thành công!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'danhsachsanpham.php';
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

// Lấy dữ liệu cho các thẻ Select
$categories = $db->xuatdulieu("SELECT id, name FROM categories WHERE status = 1");
$brands     = $db->xuatdulieu("SELECT id, name FROM brands");
$suppliers  = $db->xuatdulieu("SELECT id, name FROM suppliers");

$pageTitle = "Thêm sản phẩm";
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
?>
<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo mới sản phẩm</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/danhsachsanpham.php">Danh Sách Sản Phẩm</a> / <span>Thêm Sản Phẩm</span>
            </div>
        </div>

        <!-- Các nút bổ sung nhanh -->
        <div class="quick-action-group">
            <button type="button" class="btn-quick-action nut-them"
                data-action="supplier"
                data-title="Thêm nhà cung cấp mới"
                data-label="Tên nhà cung cấp"
                data-success="Đã thêm nhà cung cấp thành công!">
                <i class="fa-solid fa-plus"></i> Thêm nhà cung cấp
            </button>

            <button type="button" class="btn-quick-action nut-them"
                data-action="category"
                data-title="Thêm danh mục mới"
                data-label="Tên danh mục"
                data-success="Đã thêm danh mục thành công!">
                <i class="fa-solid fa-plus"></i> Thêm danh mục
            </button>

            <button type="button" class="btn-quick-action nut-them"
                data-action="brand"
                data-title="Thêm thương hiệu mới"
                data-label="Tên thương hiệu"
                data-success="Đã thêm thương hiệu thành công!">
                <i class="fa-solid fa-plus"></i> Thêm thương hiệu
            </button>
        </div>
        <!-- Form chính -->
        <form id="productForm" action="" method="POST" enctype="multipart/form-data">
            <div class="product-form-grid">
                <div class="product-field-group">
                    <label>Mã sản phẩm (SKU)</label>
                    <input type="text" name="product_code" class="product-input" placeholder="Nhập mã SKU..." required>
                </div>
                <div class="product-field-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="product_name" class="product-input" placeholder="Nhập tên sản phẩm..." required>
                </div>
                <div class="product-field-group">
                    <label>Số lượng kho</label>
                    <input type="text" name="quantity" class="product-input" placeholder="Số lượng ....." required>
                </div>
                <div class="product-field-group">
                    <label>Tình trạng</label>
                    <select name="condition_text" class="product-input" required>
                        <option value="Mới 100%">Mới 100%</option>
                        <option value="Hàng trưng bày">Hàng trưng bày</option>
                        <option value="Đã qua sử dụng">Đã qua sử dụng (Like new)</option>
                    </select>
                </div>
                <div class="product-field-group">
                    <label>Danh mục</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="product-field-group">
                    <label>Thương hiệu</label>
                    <select name="brand_id" class="product-input" required>
                        <option value="">-- Chọn thương hiệu --</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['id']; ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="product-field-group">
                    <label>Nhà cung cấp</label>
                    <select name="supplier_id" class="product-input">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?php echo $sup['id']; ?>"><?php echo htmlspecialchars($sup['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="product-field-group">
                    <label>Giá bán</label>
                    <input type="text" name="price" class="product-input" placeholder="0 đ" required>
                </div>
                <div class="product-field-group">
                    <label>Giá giảm</label>
                    <input type="text" name="discount_price" class="product-input" placeholder="0 đ">
                </div>
                <div class="product-field-group span-4">
                    <label>Ảnh sản phẩm</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh
                        <input type="file" name="product_image" style="display: none;" accept="image/*">
                    </label>
                </div>
                <div class="product-field-group span-4">
                    <label>Mô tả sản phẩm</label>
                    <textarea name="description" class="product-input" placeholder="Nhập mô tả chi tiết sản phẩm..."></textarea>
                </div>
            </div>

            <div class="product-form-actions">
                <button type="submit" class="btn-submit-save">Lưu lại</button>
                <a href=" index.php" class="btn-submit-cancel nutHuy"
                    data-type="sản phẩm"
                    data-redirect="<?php echo BASE_URL; ?>/danhsachsanpham.php">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</main>

<!-- Đoạn JavaScript xác nhận trước khi submit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Xử lý submit form thêm sản phẩm có hỏi xác nhận
        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Xác nhận thêm sản phẩm?',
                    text: " Bạn có muốn thêm sản phẩm này vào hệ thống không?",
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
                        hiddenInput.value = 'Thêm sản phẩm';
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        }

        // 2. Xử lý các nút thêm nhanh (Nhà cung cấp, Danh mục, Thương hiệu/Tình trạng) bằng AJAX
        document.querySelectorAll(".nut-them").forEach((button) => {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                const modalTitle = this.dataset.title;
                const inputLabel = this.dataset.label;
                const successMsg = this.dataset.success;
                const actionType = this.dataset.action; // supplier, category, brand

                Swal.fire({
                    title: modalTitle,
                    input: "text",
                    inputLabel: inputLabel,
                    inputPlaceholder: `Nhập ${inputLabel.toLowerCase()}...`,
                    showCancelButton: true,
                    confirmButtonText: "Lưu lại",
                    cancelButtonText: "Hủy bỏ",
                    confirmButtonColor: "#2ecc71",
                    cancelButtonColor: "#f19066",
                    inputValidator: (value) => {
                        if (!value || !value.trim()) {
                            return `Vui lòng nhập ${inputLabel.toLowerCase()}!`;
                        }
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        const giaTriMoi = result.value.trim();

                        // Gửi dữ liệu ngầm bằng Fetch API tới ajax_them_nhanh.php
                        fetch('ajax_them_nhanh.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=${actionType}&value=${encodeURIComponent(giaTriMoi)}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire("Thành công!", successMsg, "success");

                                    // Tự động thêm option mới vào thẻ select tương ứng và chọn sẵn
                                    let selectElement = null;
                                    if (actionType === 'supplier') {
                                        selectElement = document.querySelector('select[name="supplier_id"]');
                                    } else if (actionType === 'category') {
                                        selectElement = document.querySelector('select[name="category_id"]');
                                    } else if (actionType === 'brand') {
                                        selectElement = document.querySelector('select[name="brand_id"]');
                                    }

                                    if (selectElement) {
                                        const newOption = document.createElement('option');
                                        newOption.value = data.id;
                                        newOption.textContent = data.name;
                                        newOption.selected = true;
                                        selectElement.appendChild(newOption);
                                    }
                                } else {
                                    Swal.fire("Lỗi!", data.message, "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Lỗi!", "Có lỗi kết nối đến máy chủ.", "error");
                            });
                    }
                });
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>