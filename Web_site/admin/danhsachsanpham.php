<?php
require_once '../config.php';
include("xyly/xuly.php");

$pageTitle = "Quản lý sản phẩm";
$p = new xuly();

// Xử lý xóa sản phẩm
// Xử lý xóa sản phẩm
// Xử lý xóa sản phẩm
if (isset($_REQUEST['nut']) && $_REQUEST['nut'] === 'Xóa sản phẩm') {
    $myid = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
    if ($myid > 0) {
        // 1. Kiểm tra xem sản phẩm có nằm trong đơn hàng
        $check_order = $p->xuatdulieu("SELECT COUNT(*) AS total FROM order_items WHERE product_id = '$myid'");
        $order_total = !empty($check_order) ? intval($check_order[0]['total']) : 0;

        // 2. Kiểm tra số lượng tồn kho
        $check_stock = $p->xuatdulieu("SELECT stock FROM products WHERE id = '$myid' LIMIT 1");
        $stock_val = !empty($check_stock) ? intval($check_stock[0]['stock']) : 0;

        // Nếu sản phẩm đã có trong đơn hàng
        if ($order_total > 0) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Không thể xóa!",
                        text: "Sản phẩm này đã phát sinh đơn hàng trong hệ thống.",
                        confirmButtonText: "Đã hiểu"
                    }).then(() => {
                        window.location.href = "danhsachsanpham.php";
                    });
                });
            </script>';
        }
        //  Nếu còn hàng trong kho
        elseif ($stock_val > 0) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể xóa!",
                        text: "Sản phẩm này vẫn còn hàng trong kho (stock > 0). Không thể xóa khi còn hàng.",
                        confirmButtonText: "Đã hiểu"
                    }).then(() => {
                        window.location.href = "danhsachsanpham.php";
                    });
                });
            </script>';
        }
        // Thỏa mãn tất cả điều kiện (không có đơn hàng và đã hết hàng) -> Tiến hành xóa
        else {
            if ($p->themxoasua("DELETE FROM products WHERE id='$myid' LIMIT 1")) {
                header("Location: danhsachsanpham.php?msg=deleted");
                exit();
            }
        }
    }
}

// Nhận dữ liệu tìm kiếm và lọc từ form (GET)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat_filter = isset($_GET['category']) ? trim($_GET['category']) : '';
$stock_filter = isset($_GET['stock_status']) ? trim($_GET['stock_status']) : '';

// Xây dựng câu lệnh SQL động dựa trên bộ lọc
$sql = "SELECT * FROM products WHERE 1=1";
if ($search !== '') {
    $sql .= " AND name LIKE '%" . $search . "%'";
}
if ($cat_filter !== '') {
    $sql .= " AND category_id = '$cat_filter'";
}
if ($stock_filter === 'instock') {
    $sql .= " AND stock > 10";
} elseif ($stock_filter === 'lowstock') {
    $sql .= " AND stock > 0 AND stock <= 10";
} elseif ($stock_filter === 'outstock') {
    $sql .= " AND stock = 0";
}
$sql .= " ORDER BY id DESC";

$sanpham = $p->xuatdulieu($sql);
$list_categories = $p->xuatdulieu("SELECT id, name FROM categories WHERE status = 1");

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content">
    <div class="content-card">

        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Sản Phẩm</h1>
                <p>Thêm mới, cập nhật và quản lý danh mục điện thoại, phụ kiện</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/themsanpham.php" class="btn-primary" title="Thêm sản phẩm mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm sản phẩm mới</span>
            </a>
        </div>

        <form method="GET" action="" class="filter-section">
            <div class="search-box">
                <input type="text" name="search" placeholder="Tìm theo tên sản phẩm, mã SKU..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>

            <select name="category" class="filter-select">
                <option value="">-- Danh mục --</option>
                <?php foreach ($list_categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="stock_status" class="filter-select">
                <option value="">-- Trạng thái kho --</option>
                <option value="instock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'instock') ? 'selected' : ''; ?>>Còn hàng</option>
                <option value="lowstock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'lowstock') ? 'selected' : ''; ?>>Sắp hết</option>
                <option value="outstock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'outstock') ? 'selected' : ''; ?>>Hết hàng</option>
            </select>

            <button type="submit" class="btn-filter">Lọc</button>
            <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['stock_status'])): ?>
                <a href="danhsachsanpham.php" class="btn-filter" style="text-decoration:none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Tình trạng</th>
                        <th>Giá tiền</th>
                        <th>Danh mục</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($sanpham)) {
                        for ($i = 0; $i < count($sanpham); $i++) {
                            if ($sanpham[$i]['stock'] > 10) {
                                $statusClass = 'status-green';
                                $statusText = 'Còn hàng';
                            } elseif ($sanpham[$i]['stock'] > 0) {
                                $statusClass = 'status-yellow';
                                $statusText = 'Sắp hết';
                            } else {
                                $statusClass = 'status-red';
                                $statusText = 'Hết hàng';
                            }

                            $imageSrc = '../uploads/imagesanpham/' . $sanpham[$i]['image'];
                            $categoryId = $sanpham[$i]['category_id'];
                            $danhmuc = $p->xuatdulieu("select name from categories where id = '$categoryId'");

                            $categoryName = !empty($danhmuc) ? $danhmuc[0]['name'] : 'Khác';
                            $formattedPrice = number_format($sanpham[$i]['price'], 0, ',', '.') . ' đ';

                            echo '<tr>
                                <td><strong>' . ($i + 1) . '</strong></td>
                                <td><span style="font-weight: 600; color: #0f172a;">' . $sanpham[$i]['name'] . '</span></td>
                                <td><img src="' . $imageSrc . '" alt="Product Image" class="product-img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                                <td>' . $sanpham[$i]['stock'] . '</td>
                                <td><span class="badge-status ' . $statusClass . '">' . $statusText . '</span></td>
                                <td><strong>' . $formattedPrice . '</strong></td>
                                <td><span class="badge-category">' . $categoryName . '</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="' . BASE_URL . '/admin/capnhatsp.php?id=' . $sanpham[$i]['id'] . '" class="btn-action btn-edit" title="Chỉnh sửa">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete nutxoa" title="Xóa" data-id="' . $sanpham[$i]['id'] . '" data-name="' . htmlspecialchars($sanpham[$i]['name']) . '">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8" style="text-align: center; padding: 20px;">Chưa có sản phẩm nào.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Xóa sản phẩm thành công!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nutxoa').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name || 'sản phẩm này';

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
                        inputNut.value = 'Xóa sản phẩm';
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