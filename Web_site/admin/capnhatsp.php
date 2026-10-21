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

                    // Câu lệnh UPDATE
                    $sql = "UPDATE products SET sku = '$sku', category_id = '$category_id', brand_id = '$brand_id', supplier_id = $supplier_val, 
                    name = '$name', description = '$description', price = '$clean_price', sale_price = " . ($sale_price_val ? "'$sale_price_val'" : "NULL") . ", stock 
                    = '$clean_stock', condition_text = '$condition_text', image = $image_val, updated_at = NOW() WHERE id = '$myid' LIMIT 1";

                    if ($db->themxoasua($sql)) {

                        // CẬP NHẬT MÀU SẮC (Giữ cũ, Cập nhật tên nếu sửa, Thêm mới nếu có, Xóa nếu bị bớt)
                        $submitted_color_ids = $_POST['color_ids'] ?? [];
                        $existing_colors = $db->xuatdulieu("SELECT id FROM product_colors WHERE product_id = '$myid'");
                        foreach ($existing_colors as $ec) {
                            if (!in_array($ec['id'], $submitted_color_ids)) {
                                $db->themxoasua("DELETE FROM product_colors WHERE id = '{$ec['id']}'");
                            }
                        }
                        if (isset($_POST['product_colors']) && is_array($_POST['product_colors'])) {
                            for ($i = 0; $i < count($_POST['product_colors']); $i++) {
                                $c_id = $_POST['color_ids'][$i] ?? 0;
                                $c_name = trim($_POST['product_colors'][$i]);
                                if (!empty($c_name)) {
                                    if ($c_id > 0) {
                                        $db->themxoasua("UPDATE product_colors SET color_name = '$c_name' WHERE id = '$c_id'");
                                    } else {
                                        $db->themxoasua("INSERT INTO product_colors (product_id, color_name) VALUES ('$myid', '$c_name')");
                                    }
                                }
                            }
                        }

                        // CẬP NHẬT DUNG LƯỢNG
                        $submitted_storage_ids = $_POST['storage_ids'] ?? [];
                        $existing_storages = $db->xuatdulieu("SELECT id FROM product_storages WHERE product_id = '$myid'");
                        foreach ($existing_storages as $es) {
                            if (!in_array($es['id'], $submitted_storage_ids)) {
                                $db->themxoasua("DELETE FROM product_storages WHERE id = '{$es['id']}'");
                            }
                        }
                        if (isset($_POST['storage_names']) && is_array($_POST['storage_names'])) {
                            for ($i = 0; $i < count($_POST['storage_names']); $i++) {
                                $st_id = $_POST['storage_ids'][$i] ?? 0;
                                $st_name = trim($_POST['storage_names'][$i]);
                                $st_price = floatval(str_replace(['.', ','], '', $_POST['storage_prices'][$i] ?? 0));
                                if (!empty($st_name)) {
                                    if ($st_id > 0) {
                                        $db->themxoasua("UPDATE product_storages SET storage = '$st_name', extra_price = '$st_price' WHERE id = '$st_id'");
                                    } else {
                                        $db->themxoasua("INSERT INTO product_storages (product_id, storage, extra_price) VALUES ('$myid', '$st_name', '$st_price')");
                                    }
                                }
                            }
                        }

                        // CẬP NHẬT THÔNG SỐ KỸ THUẬT
                        $submitted_spec_ids = $_POST['spec_ids'] ?? [];
                        $existing_specs = $db->xuatdulieu("SELECT id FROM product_specs WHERE product_id = '$myid'");
                        foreach ($existing_specs as $esp) {
                            if (!in_array($esp['id'], $submitted_spec_ids)) {
                                $db->themxoasua("DELETE FROM product_specs WHERE id = '{$esp['id']}'");
                            }
                        }
                        if (isset($_POST['spec_names']) && is_array($_POST['spec_names'])) {
                            for ($i = 0; $i < count($_POST['spec_names']); $i++) {
                                $sp_id = $_POST['spec_ids'][$i] ?? 0;
                                $sp_name = trim($_POST['spec_names'][$i]);
                                $sp_value = trim($_POST['spec_values'][$i] ?? '');
                                if (!empty($sp_name) && !empty($sp_value)) {
                                    if ($sp_id > 0) {
                                        $db->themxoasua("UPDATE product_specs SET spec_name = '$sp_name', spec_value = '$sp_value', sort_order = '$i' WHERE id = '$sp_id'");
                                    } else {
                                        $db->themxoasua("INSERT INTO product_specs (product_id, spec_name, spec_value, sort_order) VALUES ('$myid', '$sp_name', '$sp_value', '$i')");
                                    }
                                }
                            }
                        }

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

    $colors_list   = $db->xuatdulieu("SELECT * FROM product_colors WHERE product_id = '$layid'");
    $storages_list = $db->xuatdulieu("SELECT * FROM product_storages WHERE product_id = '$layid'");
    $specs_list    = $db->xuatdulieu("SELECT * FROM product_specs WHERE product_id = '$layid' ORDER BY sort_order ASC");
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
    $colors_list   = [];
    $storages_list = [];
    $specs_list    = [];
}

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

                <!-- MÀU SẮC -->
                <div class="product-field-group span-4" style="border-top: 1px dashed #cbd5e1; padding-top: 15px; margin-top: 10px;">
                    <label style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 10px;">1. Màu sắc sản phẩm</label>
                    <div id="color-container">
                        <?php if (!empty($colors_list)): ?>
                            <?php foreach ($colors_list as $col): ?>
                                <div class="input-group-row" style="display: flex; gap: 10px; margin-bottom: 8px;">
                                    <input type="hidden" name="color_ids[]" value="<?php echo $col['id']; ?>">
                                    <input type="text" name="product_colors[]" class="product-input" value="<?php echo htmlspecialchars($col['color_name']); ?>" placeholder="Ví dụ: Titan Tự Nhiên">
                                    <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-color-btn" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500;"><i class="fa-solid fa-plus"></i> Thêm màu sắc</button>
                </div>

                <!-- DUNG LƯỢNG -->
                <div class="product-field-group span-4" style="border-top: 1px dashed #cbd5e1; padding-top: 15px; margin-top: 10px;">
                    <label style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 10px;">2. Phiên bản / Dung lượng & Giá chênh lệch</label>
                    <div id="storage-container">
                        <?php if (!empty($storages_list)): ?>
                            <?php foreach ($storages_list as $st): ?>
                                <div class="input-group-row" style="display: flex; gap: 10px; margin-bottom: 8px;">
                                    <input type="hidden" name="storage_ids[]" value="<?php echo $st['id']; ?>">
                                    <input type="text" name="storage_names[]" class="product-input" value="<?php echo htmlspecialchars($st['storage']); ?>" placeholder="Dung lượng (VD: 256GB)" style="flex: 2;">
                                    <input type="text" name="storage_prices[]" class="product-input" value="<?php echo $st['extra_price']; ?>" placeholder="Giá chênh lệch (VD: 5000000)" style="flex: 2;">
                                    <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-storage-btn" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500;"><i class="fa-solid fa-plus"></i> Thêm dung lượng</button>
                </div>

                <!-- THÔNG SỐ KỸ THUẬT -->
                <div class="product-field-group span-4" style="border-top: 1px dashed #cbd5e1; padding-top: 15px; margin-top: 10px;">
                    <label style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 10px;">3. Thông số kỹ thuật chi tiết</label>
                    <div id="spec-container">
                        <?php if (!empty($specs_list)): ?>
                            <?php foreach ($specs_list as $sp): ?>
                                <div class="input-group-row" style="display: flex; gap: 10px; margin-bottom: 8px;">
                                    <input type="hidden" name="spec_ids[]" value="<?php echo $sp['id']; ?>">
                                    <input type="text" name="spec_names[]" class="product-input" value="<?php echo htmlspecialchars($sp['spec_name']); ?>" placeholder="Tên thông số (VD: Màn hình)" style="flex: 2;">
                                    <input type="text" name="spec_values[]" class="product-input" value="<?php echo htmlspecialchars($sp['spec_value']); ?>" placeholder="Giá trị (VD: 6.9 inch)" style="flex: 3;">
                                    <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-spec-btn" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500;"><i class="fa-solid fa-plus"></i> Thêm thông số</button>
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

        // Thêm dòng mới (id="" để hệ thống hiểu đây là dòng mới cần INSERT)
        document.getElementById('add-color-btn')?.addEventListener('click', function() {
            const container = document.getElementById('color-container');
            const row = document.createElement('div');
            row.className = 'input-group-row';
            row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 8px;';
            row.innerHTML = `<input type="hidden" name="color_ids[]" value="">
                             <input type="text" name="product_colors[]" class="product-input" placeholder="Ví dụ: Titan Đen">
                             <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(row);
        });

        document.getElementById('add-storage-btn')?.addEventListener('click', function() {
            const container = document.getElementById('storage-container');
            const row = document.createElement('div');
            row.className = 'input-group-row';
            row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 8px;';
            row.innerHTML = `<input type="hidden" name="storage_ids[]" value="">
                             <input type="text" name="storage_names[]" class="product-input" placeholder="Dung lượng (VD: 512GB)" style="flex: 2;">
                             <input type="text" name="storage_prices[]" class="product-input" placeholder="Giá chênh lệch (VD: 3000000)" style="flex: 2;">
                             <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(row);
        });

        document.getElementById('add-spec-btn')?.addEventListener('click', function() {
            const container = document.getElementById('spec-container');
            const row = document.createElement('div');
            row.className = 'input-group-row';
            row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 8px;';
            row.innerHTML = `<input type="hidden" name="spec_ids[]" value="">
                             <input type="text" name="spec_names[]" class="product-input" placeholder="Tên thông số" style="flex: 2;">
                             <input type="text" name="spec_values[]" class="product-input" placeholder="Giá trị" style="flex: 3;">
                             <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()" style="padding: 0 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(row);
        });
    });
</script>

<?php
include("includes/footer.php");
?>