<?php
require_once '../config.php';
include("xyly/xuly.php");

$db = new xuly();
$article_code = 'BV-' . rand(100, 999);

// Xử lý PHP khi form được submit
if (isset($_POST['nut'])) {
    switch ($_POST['nut']) {
        case 'Thêm bài viết':
            $article_code = $_REQUEST['article_code'];
            $title        = $_REQUEST['title'];
            $category_id  = $_REQUEST['category_id'];
            $author       = $_REQUEST['author'];
            $summary      = $_REQUEST['summary'];
            $content      = $_REQUEST['content'];
            $status       = $_REQUEST['status'];
            $is_visible   = isset($_REQUEST['is_visible']) ? intval($_REQUEST['is_visible']) : 1;

            // Kiểm tra thông tin bắt buộc
            if ($article_code != '' && $title != '' && $category_id != '' && $author != '' && $status != '' && $content != '') {

                // Xử lý upload ảnh đại diện (thumbnail) sử dụng phương thức uploadfile
                $imageName = NULL;
                if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name      = $_FILES['thumbnail']['tmp_name'];
                    $original_name = $_FILES['thumbnail']['name'];
                    $fileExtension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                    $imageName     = md5(time() . $original_name) . '.' . $fileExtension;
                    $folder        = '../uploads/baiviet';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    if ($db->uploadfile($imageName, $tmp_name, $folder) != 1) {
                        $imageName = NULL;
                    }
                }

                $image_val = ($imageName !== NULL) ? "'$imageName'" : "NULL";

                // Câu lệnh INSERT vào bảng articles khớp cấu trúc thực tế
                $sql = "INSERT INTO articles (article_code, category_id, title, summary, content, thumbnail, author, view_count, status, is_visible, published_at) 
                        VALUES ('$article_code', '$category_id', '$title', '$summary', '$content', $image_val, '$author', 0, '$status', '$is_visible', NOW())";

                // Thông báo kết quả bằng SweetAlert
                if ($db->themxoasua($sql)) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Thêm bài viết mới thành công!',
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
                                text: 'Không thể thêm bài viết vào cơ sở dữ liệu.'
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
            break;
    }
}

// Lấy danh sách danh mục bài viết (news_categories) cho thẻ select
$categories = $db->xuatdulieu("SELECT id, name FROM news_categories");

$pageTitle = "Thêm bài viết mới";
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
?>
<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container" style="background-color: white; padding: 20px; border-radius: 12px;">

        <div class="product-header-wrap">
            <div class="product-title-area">
                <h1>Tạo Mới Bài Viết</h1>
            </div>
            <div class="product-breadcrumb">
                <a href="<?php echo BASE_URL; ?>/baiviet.php">Quản Lý Bài Viết</a> / <span>Thêm Bài Viết</span>
            </div>
        </div>

        <!-- Nút bổ sung nhanh danh mục bài viết -->
        <div class="quick-action-group">
            <button type="button" class="btn-quick-action nut-them"
                data-action="news_category"
                data-title="Thêm danh mục bài viết mới"
                data-label="Tên danh mục bài viết"
                data-success="Đã thêm danh mục bài viết thành công!">
                <i class="fa-solid fa-plus"></i> Thêm danh mục bài viết
            </button>
        </div>

        <form id="articleForm" action="" method="POST" enctype="multipart/form-data">
            <div class="product-form-grid">
                <div class="product-field-group">
                    <label>Mã bài viết</label>
                    <input type="text" name="article_code" class="product-input" value="<?php echo $article_code; ?>" readonly style="background-color: #f1f5f9; cursor: not-allowed;" required>
                </div>

                <div class="product-field-group">
                    <label>Tiêu đề bài viết</label>
                    <input type="text" name="title" class="product-input" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                <div class="product-field-group">
                    <label>Danh mục bài viết</label>
                    <select name="category_id" class="product-input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Tác giả</label>
                    <input type="text" name="author" class="product-input" placeholder="Nhập tên tác giả..." required>
                </div>

                <div class="product-field-group">
                    <label>Trạng thái</label>
                    <select name="status" class="product-input" required>
                        <option value="Xuất bản">Xuất bản</option>
                        <option value="Bản nháp">Bản nháp</option>
                        <option value="Chờ duyệt">Chờ duyệt</option>
                    </select>
                </div>

                <div class="product-field-group">
                    <label>Hiển thị</label>
                    <select name="is_visible" class="product-input" required>
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <div class="product-field-group span-4">
                    <label>Ảnh đại diện (Thumbnail)</label>
                    <label class="btn-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh
                        <input type="file" name="thumbnail" style="display: none;" accept="image/*"
                            onchange="document.getElementById('hiententen').innerText = this.files.length > 0 ? this.files[0].name : ''">
                    </label>
                    <span id="hiententen" style="font-size: 14px; color: #0f172a; font-weight: 500; font-style: italic;"></span>
                </div>

                <div class="product-field-group span-4">
                    <label>Tóm tắt bài viết</label>
                    <textarea name="summary" class="product-input" rows="3" placeholder="Nhập đoạn tóm tắt ngắn gọn..."></textarea>
                </div>

                <div class="product-field-group span-4">
                    <label>Nội dung chi tiết bài viết</label>
                    <textarea name="content" class="product-input" rows="6" placeholder="Nhập nội dung chi tiết bài viết..." required></textarea>
                </div>
            </div>

            <div class="product-form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-submit-save">Lưu lại</button>
                <a href="<?php echo BASE_URL; ?>/baiviet.php" class="btn-submit-cancel nutHuv"
                    data-type="bài viết"
                    data-redirect="<?php echo BASE_URL; ?>/baiviet.php">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý submit form thêm bài viết
        const articleForm = document.getElementById('articleForm');
        if (articleForm) {
            articleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Xác nhận thêm bài viết?',
                    text: "Bạn có muốn thêm bài viết này vào hệ thống không?",
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
                        hiddenInput.value = 'Thêm bài viết';
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

                                    // Tự động thêm option mới vào thẻ select danh mục bài viết và chọn sẵn
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

<?php include 'includes/footer.php'; ?>