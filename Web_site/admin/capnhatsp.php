<?php
require_once '../config.php';
include("xyly/xuly.php");

$db = new xuly();

// Xử lý khi form cập nhật được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Cập nhật sản phẩm':
            $myid           = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
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

            $clean_stock = intval($stock);

            if ($myid > 0) {
                if ($sku != '' && $name != '' && $category_id != '' && $brand_id != '' && $price != '' && $stock !== '') {

                    // Lấy ảnh cũ từ cơ sở dữ liệu phòng khi không chọn ảnh mới
                    $old_img_res = $db->xuatdulieu("SELECT image FROM products WHERE id='$myid' LIMIT 1");
                    $imageName = !empty($old_img_res) ? $old_img_res[0]['image'] : NULL;

                    // Xử lý upload ảnh mới (nếu có chọn)
                    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                        $tmp_name      = $_FILES['product_image']['tmp_name'];
                        $original_name = $_FILES['product_image']['name'];
                        $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                        $newImageName  = md5(time() . $original_name) . '.' . $fileExtension;
                        $folder        = '../uploads/imagesanpham';

                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        if ($db->uploadfile($newImageName, $tmp_name, $folder) == 1) {
                            $imageName = $newImageName;
                        }
                    }

                    $supplier_val   = (!empty($supplier_id)) ? "'$supplier_id'" : "NULL";
                    $sale_price_val = (!empty($sale_price)) ? floatval(str_replace(['.', ','], '', $sale_price)) : 0;
                    $image_val      = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                    $clean_price    = floatval(str_replace(['.', ','], '', $price));

                    // Câu lệnh UPDATE chính xác theo cấu trúc bảng products
                    $sql = "UPDATE products SET sku = '$sku', category_id = '$category_id', brand_id = '$brand_id', supplier_id = $supplier_val, 
                    name = '$name', description = '$description', price = '$clean_price', sale_price = " . ($sale_price_val ? "'$sale_price_val'" : "NULL") . ", stock 
                    = '$clean_stock', condition_text = '$condition_text', image = $image_val, updated_at = NOW() WHERE id = '$myid' LIMIT 1";

                    if ($db->themxoasua($sql)) {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: 'Cập nhật sản phẩm thành công!',
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
                                    text: 'Không thể cập nhật sản phẩm vào cơ sở dữ liệu.'
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
                                text: 'Vui lòng nhập đầy đủ các thông tin bắt buộc.'
                            });
                        });
                    </script>";
                }
            }
            break;
    }
}

// Lấy ID sản phẩm từ URL
$layid = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$product_info = $db->xuatdulieu("SELECT * FROM products WHERE id = '$layid' LIMIT 1");

if (!empty($product_info)) {
    $row            = $product_info[0];
    $sku            = $row['sku'];
    $product_name   = $row['name'];
    $category_id    = $row['category_id'];
    $brand_id       = $row['brand_id'];
    $supplier_id    = $row['supplier_id'];
    $stock          = $row['stock'];
    $price          = $row['price'];
    $sale_price     = $row['sale_price'];
    $condition_text = $row['condition_text'];
    $description    = $row['description'];
    $image          = $row['image'];
} else {
    $sku            = '';
    $product_name   = '';
    $category_id    = '';
    $brand_id       = '';
    $supplier_id    = '';
    $stock          = 0;
    $price          = '';
    $sale_price     = '';
    $condition_text = '';
    $description    = '';
    $image          = '';
}

// Lấy dữ liệu cho các thẻ Select
$categories = $db->xuatdulieu("SELECT id, name FROM categories WHERE status = 1");
$brands     = $db->xuatdulieu("SELECT id, name FROM brands");
$suppliers  = $db->xuatdulieu("SELECT id, name FROM suppliers");

$pageTitle = "Cập nhật sản phẩm";

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 25px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Cập nhật sản phẩm: <?php echo htmlspecialchars($sku); ?></h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/danhsachsanpham.php">Danh Sách Sản Phẩm</a> / <span>Cập Nhật Sản Phẩm</span>
            </div>
        </div>


        <form id="updateProductForm" action="" method="POST" enctype="multipart/form-data">
            <!-- ID ẩn truyền vào -->
            <input type="hidden" name="txtid" value="<?php echo $layid; ?>">

            <div class="product-form-grid">

                <div class="product-field-group">
                    <label>Mã sản phẩm (SKU)</label>
                    <input type="text" name="product_code" class="product-input" value="<?php echo htmlspecialchars($sku); ?>" readonly style="background-color: #f1f5f9; cursor: not-allowed;" required>
                </div>

                <div class="product-field-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="product_name" class="product-input" value="<?php echo htmlspecialchars($product_name); ?>" placeholder="Nhập tên sản phẩm..." required>
                </div>

                <div class="product-field-group">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" class="product-input" value="<?php echo intval($stock); ?>" min="0" required>
                </div>

                <div class="product-field-group">
                    <label>Tình trạng</label>
                    <select name="condition_text" class="product-input" required>
                        <option value="">-- Chọn tình trạng --</option>
                        <option value="Mới 100%" <?php echo ($condition_text === 'Mới 100%') ? 'selected' : ''; ?>>Mới 100%</option>
                        <option value="Hàng trưng bày" <?php echo ($condition_text === 'Hàng trưng bày') ? 'selected' : ''; ?>>Hàng trưng bày</option>
                        <option value="Đã qua sử dụng" <?php echo ($condition_text === 'Đã qua sử dụng') ? 'selected' : ''; ?>>Đã qua sử dụng (Like new)</option>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Danh mục</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Thương hiệu</label>
                    <select name="brand_id" class="product-input" required>
                        <option value="">-- Chọn thương hiệu --</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['id']; ?>" <?php echo ($brand_id == $brand['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Nhà cung cấp</label>
                    <select name="supplier_id" class="product-input">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?php echo $sup['id']; ?>" <?php echo ($supplier_id == $sup['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sup['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Giá bán</label>
                    <input type="text" name="price" class="product-input" value="<?php echo $price; ?>" placeholder="0 đ" required>
                </div>

                <div class="product-field-group">
                    <label>Giá giảm</label>
                    <input type="text" name="discount_price" class="product-input" value="<?php echo $sale_price; ?>" placeholder="0 đ">
                </div>

                <div class="product-field-group span-4">
                    <label>Ảnh sản phẩm</label>
                    <div style="margin-bottom: 10px;">
                        <?php if (!empty($image)): ?>
                            <img src="../uploads/imagesanpham/<?php echo htmlspecialchars($image); ?>" alt="Product Image" class="product-img" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <?php else: ?>
                            <span style="color: #64748b; font-style: italic;">Chưa có ảnh sản phẩm</span>
                        <?php endif; ?>
                    </div>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Thay đổi ảnh sản phẩm
                        <input type="file" name="product_image" style="display: none;" accept="image/*"
                            onchange="document.getElementById('hiententen').innerText = this.files.length > 0 ? this.files[0].name : ''">
                    </label>
                    <span id="hiententen" style="font-size: 14px; color: #0f172a; font-weight: 500; font-style: italic;"></span>
                </div>

                <div class="product-field-group span-4">
                    <label>Mô tả sản phẩm</label>
                    <textarea name="description" class="product-input" placeholder="Nhập mô tả chi tiết sản phẩm..."><?php echo htmlspecialchars($description); ?></textarea>
                </div>
            </div>

            <div class="product-form-actions" style="margin-top: 20px;">
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-submit-save">
                        Lưu lại
                    </button>

                    <a href="<?php echo BASE_URL; ?>/danhsachsanpham.php" class="btn-submit-cancel nutHuy"
                        data-type="sản phẩm"
                        data-redirect="<?php echo BASE_URL; ?>/danhsachsanpham.php">
                        Hủy bỏ
                    </a>
                </div>
            </div>

        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý submit form cập nhật sản phẩm bằng SweetAlert2
        const updateForm = document.getElementById('updateProductForm');
        if (updateForm) {
            updateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Xác nhận cập nhật?',
                    text: "Bạn có muốn lưu các thay đổi cho sản phẩm này không?",
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
                        hiddenInput.value = 'Cập nhật sản phẩm';
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