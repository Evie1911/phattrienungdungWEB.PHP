<?php
require_once '../config.php';
include("xyly/xuly.php");

$pageTitle = "Quản lý danh mục";
$p = new xuly();

// Xử lý xóa danh mục
if (isset($_REQUEST['nut']) && $_REQUEST['nut'] === 'Xóa danh mục') {
    $myid = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
    if ($myid > 0) {
        // Kiểm tra xem danh mục này có chứa sản phẩm nào không
        $kiemtra_sp = $p->xuatdulieu("SELECT COUNT(*) as soluong FROM products WHERE category_id = '$myid'");
        $so_luong_sp = !empty($kiemtra_sp) ? intval($kiemtra_sp[0]['soluong']) : 0;

        if ($so_luong_sp > 0) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể xóa!",
                        text: "Danh mục này vẫn còn sản phẩm bên trong. Vui lòng xóa hoặc chuyển sản phẩm sang danh mục khác trước khi xóa danh mục này.",
                        confirmButtonText: "Đã hiểu"
                    }).then(() => {
                        window.location.href = "danhsachdanhmuc.php";
                    });
                });
            </script>';
        } else {
            if ($p->themxoasua("DELETE FROM categories WHERE id='$myid' LIMIT 1")) {
                header("Location: danhsachdanhmuc.php?msg=deleted");
                exit();
            }
        }
    }
}

// Nhận từ khóa tìm kiếm (GET)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Xây dựng câu lệnh SQL truy vấn bảng categories
$sql = "SELECT * FROM categories WHERE 1=1";
if ($search !== '') {
    $sql .= " AND name LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC";

$danhmuc = $p->xuatdulieu($sql);

// Các thành phần giao diện
include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>
<main class="main-content">
    <div class="content-card">
        <!-- Tiêu đề trang & Nút hành động -->
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Danh Mục</h1>
                <p>Thêm mới, cập nhật và quản lý hình ảnh, thông tin danh mục</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/themdanhmuc.php" class="btn-primary" title="Thêm danh mục mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm danh mục mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm -->
        <form method="GET" action="" class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" name="search" placeholder="Tìm theo tên danh mục, mã..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <button type="submit" class="btn-filter">Tìm kiếm</button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="danhsachdanhmuc.php" class="btn-filter" style="text-decoration:none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <!-- Bảng danh sách danh mục -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số lượng sản phẩm</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($danhmuc)) {
                        $stt = 1;
                        foreach ($danhmuc as $row) {
                            // Đếm số lượng sản phẩm thuộc danh mục này
                            $catId = $row['id'];
                            $countRes = $p->xuatdulieu("SELECT COUNT(*) as total FROM products WHERE category_id = '$catId'");
                            $productCount = !empty($countRes) ? $countRes[0]['total'] : 0;

                            // Xử lý đường dẫn ảnh danh mục chính xác
                            $imgName = !empty($row['image']) ? $row['image'] : '';
                            $imageSrc = (!empty($imgName)) ? '../uploads/anhdanhmuc/' . $imgName : 'https://image.dienthoaivui.com.vn/x,webp,q90/https://media-asset.dienthoaivui.com.vn/uploads/dashboard/editor_upload/nen-trang-tron-48.jpg';

                            echo '<tr>
                                <td><strong>' . $stt++ . '</strong></td>
                                <td><img src="' . $imageSrc . '" alt="Category Image" class="product-img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                                <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($row['name']) . '</span></td>
                                <td>' . htmlspecialchars($row['description']) . '</td>
                                <td>' . $productCount . '</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="' . BASE_URL . '/capnhatdm.php?id=' . $row['id'] . '" class="btn-action btn-edit" title="Chỉnh sửa">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete nutxoa" title="Xóa" data-id="' . $row['id'] . '" data-name="' . htmlspecialchars($row['name']) . '">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center; padding: 20px;">Chưa có danh mục nào.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Thư viện SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script thông báo thành công khi xóa -->
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Xóa danh mục thành công!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        });
    </script>
<?php endif; ?>

<!-- Script bắt sự kiện click nút xóa -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nutxoa').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name || 'danh mục này';

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: `Bạn có chắc chắn muốn xóa "${name}" không?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Có, xóa ngay!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';

                        const inputId = document.createElement('input');
                        inputId.type = 'hidden';
                        inputId.name = 'txtid';
                        inputId.value = id;
                        form.appendChild(inputId);

                        const inputNut = document.createElement('input');
                        inputNut.type = 'hidden';
                        inputNut.name = 'nut';
                        inputNut.value = 'Xóa danh mục';
                        form.appendChild(inputNut);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<?php
include("includes/footer.php");
?>