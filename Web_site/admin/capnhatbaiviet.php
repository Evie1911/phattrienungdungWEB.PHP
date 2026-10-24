<?php
require_once '../config.php';
include("xyly/xuly.php");

$db = new xuly();

$msg = "";
$msgType = "";

// Xử lý khi form cập nhật được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Cập nhật bài viết':
            $myid         = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
            $article_code = $_REQUEST['article_code'];
            $title        = $_REQUEST['title'];
            $category_id  = $_REQUEST['category_id'];
            $author       = $_REQUEST['author'];
            $summary      = $_REQUEST['summary'];
            $content      = $_REQUEST['content'];
            $status       = $_REQUEST['status'];
            $is_visible   = isset($_REQUEST['is_visible']) ? intval($_REQUEST['is_visible']) : 1;
            $published_at = $_REQUEST['published_at'];

            if ($myid > 0) {
                if ($article_code != '' && $title != '' && $category_id != '' && $author != '' && $status != '' && $content != '') {

                    // Lấy ảnh cũ từ CSDL phòng khi không chọn ảnh mới
                    $old_img_res = $db->xuatdulieu("SELECT thumbnail FROM articles WHERE id='$myid' LIMIT 1");
                    $imageName = !empty($old_img_res) ? $old_img_res[0]['thumbnail'] : NULL;

                    // Xử lý upload ảnh mới (nếu có chọn)
                    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                        $tmp_name      = $_FILES['thumbnail']['tmp_name'];
                        $original_name = $_FILES['thumbnail']['name'];
                        $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                        $newImageName  = md5(time() . $original_name) . '.' . $fileExtension;
                        $folder        = '../uploads/baiviet';

                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        if ($db->uploadfile($newImageName, $tmp_name, $folder) == 1) {
                            $imageName = $newImageName;
                        }
                    }

                    $image_val = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                    // Câu lệnh UPDATE 
                    $sql = "UPDATE articles SET 
                            article_code = '$article_code', 
                            category_id = '$category_id', 
                            title = '$title', 
                            summary = '$summary', 
                            content = '$content', 
                            thumbnail = $image_val, 
                            author = '$author', 
                            status = '$status', 
                            is_visible = '$is_visible', 
                            published_at = '$published_at' 
                            WHERE id = '$myid' LIMIT 1";

                    if ($db->themxoasua($sql)) {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: 'Cập nhật bài viết thành công!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = 'baiviet.php';
                                });
                            });
                        </script>";
                    } else {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Không thể cập nhật bài viết vào cơ sở dữ liệu.'
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

// Lấy thông tin bài viết theo ID truyền qua URL
$layid = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$article_info = $db->xuatdulieu("SELECT * FROM articles WHERE id = '$layid' LIMIT 1");

if (!empty($article_info)) {
    $row          = $article_info[0];
    $article_code = $row['article_code'];
    $title        = $row['title'];
    $category_id  = $row['category_id'];
    $summary      = $row['summary'];
    $content      = $row['content'];
    $thumbnail    = $row['thumbnail'];
    $author       = $row['author'];
    $status = isset($row['status']) ? trim($row['status']) : '';
    $is_visible   = $row['is_visible'];
    $published_at = !empty($row['published_at']) ? date('Y-m-d\TH:i', strtotime($row['published_at'])) : date('Y-m-d\TH:i');
} else {
    // Giá trị mặc định nếu không tìm thấy ID
    $article_code = '';
    $title        = '';
    $category_id  = '';
    $summary      = '';
    $content      = '';
    $thumbnail    = '';
    $author       = '';
    $status       = '';
    $is_visible   = 1;
    $published_at = date('Y-m-d\TH:i');
}

// Lấy danh sách danh mục bài viết cho thẻ select
$categories = $db->xuatdulieu("SELECT id, name FROM news_categories");

$pageTitle = "Cập nhật bài viết";

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">
        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Cập nhật bài viết: <?php echo htmlspecialchars($article_code); ?></h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/admin/baiviet.php">Quản Lý Bài Viết</a> / <span>Cập Nhật Bài Viết</span>
            </div>
        </div>

        <div class="quick-action-group">
            <button type="button" class="btn-quick-action nut-them"
                data-action="news_category"
                data-title="Thêm danh mục bài viết mới"
                data-label="Tên danh mục bài viết"
                data-success="Đã thêm danh mục bài viết thành công!">
                <i class="fa-solid fa-plus"></i> Thêm danh mục bài viết
            </button>
        </div>

        <form id="updateArticleForm" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="txtid" value="<?php echo $layid; ?>">

            <div class="product-form-grid">
                <div class="product-field-group">
                    <label>Mã bài viết</label>
                    <input type="text" name="article_code" class="product-input" value="<?php echo htmlspecialchars($article_code); ?>" readonly style="background-color: #f1f5f9; cursor: not-allowed;" required>
                </div>

                <div class="product-field-group">
                    <label>Tiêu đề bài viết</label>
                    <input type="text" name="title" class="product-input" value="<?php echo htmlspecialchars($title); ?>" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                <div class="product-field-group">
                    <label>Danh mục bài viết</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input" required>
                        <option value="Xuat ban" <?php echo ($status === 'Xuat ban') ? 'selected' : ''; ?>>Xuất bản</option>
                        <option value="Ban nhap" <?php echo ($status === 'Ban nhap') ? 'selected' : ''; ?>>Bản nháp</option>
                        <option value="Cho duyet" <?php echo ($status === 'Cho duyet') ? 'selected' : ''; ?>>Chờ duyệt</option>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Hiển thị / Ẩn</label>
                    <select name="is_visible" class="product-input" required>
                        <option value="1" <?php echo ($is_visible == 1) ? 'selected' : ''; ?>>Hiển thị (Công khai)</option>
                        <option value="0" <?php echo ($is_visible == 0) ? 'selected' : ''; ?>>Ẩn (Không hiển thị)</option>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Tác giả</label>
                    <input type="text" name="author" class="product-input" value="<?php echo htmlspecialchars($author); ?>" placeholder="Nhập tên tác giả..." required>
                </div>

                <div class="product-field-group">
                    <label>Ngày đăng</label>
                    <input type="datetime-local" name="published_at" class="product-input" value="<?php echo $published_at; ?>">
                </div>

                <div class="product-field-group span-4">
                    <label>Ảnh đại diện (Thumbnail)</label>
                    <div style="margin-bottom: 10px;">
                        <?php if (!empty($thumbnail)): ?>
                            <img src="../uploads/baiviet/<?php echo htmlspecialchars($thumbnail); ?>" alt="Thumbnail" class="product-img" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <?php else: ?>
                            <span style="color: #64748b; font-style: italic;">Chưa có ảnh đại diện</span>
                        <?php endif; ?>
                    </div>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Thay đổi ảnh đại diện
                        <input type="file" name="thumbnail" style="display: none;" accept="image/*"
                            onchange="document.getElementById('hiententen').innerText = this.files.length > 0 ? this.files[0].name : ''">
                    </label>
                    <span id="hiententen" style="font-size: 14px; color: #0f172a; font-weight: 500; font-style: italic;"></span>
                </div>

                <div class="product-field-group span-4">
                    <label>Tóm tắt ngắn</label>
                    <textarea name="summary" class="product-input" placeholder="Nhập tóm tắt ngắn về nội dung bài viết..." style="height: 80px;"><?php echo htmlspecialchars($summary); ?></textarea>
                </div>

                <div class="product-field-group span-4">
                    <label>Nội dung chi tiết</label>
                    <textarea name="content" class="product-input" placeholder="Nhập nội dung chi tiết bài viết..." style="height: 200px;" required><?php echo htmlspecialchars($content); ?></textarea>
                </div>
            </div>

            <div class="product-form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-submit-save">
                    Lưu lại
                </button>

                <a href="<?php echo BASE_URL; ?>/admin/baiviet.php" class="btn-submit-cancel nutHuy"
                    data-type="bài viết"
                    data-redirect="<?php echo BASE_URL; ?>/admin/baiviet.php">
                    Hủy bỏ
                </a>
            </div>

        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý xác nhận submit form cập nhật bài viết
        const updateForm = document.getElementById('updateArticleForm');
        if (updateForm) {
            updateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Xác nhận cập nhật?',
                    text: "Bạn có muốn lưu các thay đổi cho bài viết này không?",
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
                        hiddenInput.value = 'Cập nhật bài viết';
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        }

        // Xử lý nút thêm nhanh danh mục bài viết bằng AJAX
        document.querySelectorAll(".nut-them").forEach((button) => {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                const modalTitle = this.dataset.title;
                const inputLabel = this.dataset.label;
                const successMsg = this.dataset.success;
                const actionType = this.dataset.action; // news_category

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

                                    // Tự động thêm option mới vào thẻ select danh mục và chọn sẵn
                                    const selectElement = document.querySelector('select[name="category_id"]');
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

<?php
include("includes/footer.php");
?>