<?php
require_once '../config.php';
include("xyly/xuly.php");

$pageTitle = "Quản lý nhà cung cấp và thương hiệu";
$p = new xuly();

// 1. Xử lý xóa nhà cung cấp
if (isset($_REQUEST['nut']) && $_REQUEST['nut'] === 'Xóa nhà cung cấp') {
    $myid = isset($_REQUEST['txtid']) ? intval($_REQUEST['txtid']) : 0;
    if ($myid > 0) {
        if ($p->themxoasua("DELETE FROM suppliers WHERE id='$myid' LIMIT 1")) {
            header("Location: nhacungcap.php?msg=deleted_supplier");
            exit();
        }
    }
}

// 2. Xử lý xóa thương hiệu
if (isset($_REQUEST['nut']) && $_REQUEST['nut'] === 'Xóa thương hiệu') {
    $brandid = isset($_REQUEST['brandid']) ? intval($_REQUEST['brandid']) : 0;
    if ($brandid > 0) {
        if ($p->themxoasua("DELETE FROM brands WHERE id='$brandid' LIMIT 1")) {
            header("Location: nhacungcap.php?msg=deleted_brand");
            exit();
        }
    }
}

// Nhận từ khóa tìm kiếm nhà cung cấp
$search_sup = isset($_GET['search_sup']) ? trim($_GET['search_sup']) : '';
$sql_sup = "SELECT * FROM suppliers WHERE 1=1";
if ($search_sup !== '') {
    $search_sup_esc = addslashes($search_sup);
    $sql_sup .= " AND (name LIKE '%$search_sup_esc%' OR email LIKE '%$search_sup_esc\%' OR phone LIKE '\%$search_sup_esc%')";
}
$sql_sup .= " ORDER BY id DESC";
$nhacungcap = $p->xuatdulieu($sql_sup);

// Nhận từ khóa tìm kiếm thương hiệu
$search_brand = isset($_GET['search_brand']) ? trim($_GET['search_brand']) : '';
$sql_brand = "SELECT * FROM brands WHERE 1=1"; // Giả định tên bảng thương hiệu là brands
if ($search_brand !== '') {
    $search_brand_esc = addslashes($search_brand);
    $sql_brand .= " AND name LIKE '%$search_brand_esc%'";
}
$sql_brand .= " ORDER BY id DESC";
$thuonghieu = $p->xuatdulieu($sql_brand);

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>
<main class="main-content">

    <!-- PHẦN 1: BẢNG QUẢN LÝ NHÀ CUNG CẤP -->
    <div class="content-card" style="margin-bottom: 30px;">
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Nhà Cung Cấp</h1>
                <p>Thêm mới, cập nhật và quản lý thông tin các nhà cung cấp sản phẩm</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/themnhacungcap.php" class="btn-primary" title="Thêm nhà cung cấp mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm nhà cung cấp mới</span>
            </a>
        </div>

        <form method="GET" action="" class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" name="search_sup" placeholder="Tìm theo tên nhà cung cấp, email, số điện thoại..." value="<?php echo htmlspecialchars($search_sup); ?>">
            </div>
            <button type="submit" class="btn-filter">Tìm kiếm</button>
            <?php if ($search_sup !== ''): ?>
                <a href="nhacungcap.php" class="btn-filter" style="text-decoration:none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($nhacungcap)) {
                        $stt = 1;
                        foreach ($nhacungcap as $row) {
                            echo '<tr>
                                    <td><strong>' . $stt++ . '</strong></td>
                                    <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($row['name']) . '</span></td>
                                    <td>' . htmlspecialchars($row['phone']) . '</td>
                                    <td>' . htmlspecialchars($row['email']) . '</td>
                                    <td>' . htmlspecialchars($row['address']) . '</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="' . BASE_URL . '/capnhatnhacungcap.php?id=' . $row['id'] . '" class="btn-action btn-edit" title="Chỉnh sửa">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete nutxoa-sup" title="Xóa" data-id="' . $row['id'] . '" data-name="' . htmlspecialchars($row['name']) . '">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center; padding: 20px;">Chưa có nhà cung cấp nào trong hệ thống.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PHẦN 2: BẢNG QUẢN LÝ THƯƠNG HIỆU (Dựa trên cấu trúc id, name[cite: 5]) -->
    <div class="content-card">
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Thương Hiệu</h1>
                <p>Thêm mới, cập nhật danh sách thương hiệu sản phẩm (Anker, Apple, Samsung, v.v.)[cite: 5]</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/themthuonghieu.php" class="btn-primary" title="Thêm thương hiệu mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm thương hiệu mới</span>
            </a>
        </div>

        <form method="GET" action="" class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" name="search_brand" placeholder="Tìm kiếm theo tên thương hiệu..." value="<?php echo htmlspecialchars($search_brand); ?>">
            </div>
            <button type="submit" class="btn-filter">Tìm kiếm</button>
            <?php if ($search_brand !== ''): ?>
                <a href="nhacungcap.php" class="btn-filter" style="text-decoration:none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">STT</th>
                        <th style="width: 20%;">Mã ID</th>
                        <th style="width: 50%;">Tên Thương Hiệu</th>
                        <th style="width: 20%;">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($thuonghieu)) {
                        $stt_brand = 1;
                        foreach ($thuonghieu as $brand) {
                            echo '<tr>
                                    <td><strong>' . $stt_brand++ . '</strong></td>
                                    <td>' . htmlspecialchars($brand['id']) . '</td>
                                    <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($brand['name']) . '</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="' . BASE_URL . '/capnhatthuonghieu.php?id=' . $brand['id'] . '" class="btn-action btn-edit" title="Chỉnh sửa">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete nutxoa-brand" title="Xóa" data-id="' . $brand['id'] . '" data-name="' . htmlspecialchars($brand['name']) . '">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Chưa có thương hiệu nào trong hệ thống.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- Script thông báo SweetAlert2 -->
<?php if (isset($_GET['msg'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let msgText = '';
            <?php if ($_GET['msg'] === 'deleted_supplier'): ?>
                msgText = 'Xóa nhà cung cấp thành công!';
            <?php elseif ($_GET['msg'] === 'deleted_brand'): ?>
                msgText = 'Xóa thương hiệu thành công!';
            <?php endif; ?>

            if (msgText !== '') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: msgText,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }
        });
    </script>
<?php endif; ?>

<!-- Script xử lý sự kiện xóa của cả 2 bảng -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xóa Nhà cung cấp
        document.querySelectorAll('.nutxoa-sup').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name || 'nhà cung cấp này';

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: `Bạn có chắc chắn muốn xóa nhà cung cấp "${name}" không?`,
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
                        inputNut.value = 'Xóa nhà cung cấp';
                        form.appendChild(inputNut);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Xóa Thương hiệu
        document.querySelectorAll('.nutxoa-brand').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name || 'thương hiệu này';

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: `Bạn có chắc chắn muốn xóa thương hiệu "${name}" không?`,
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
                        inputId.name = 'brandid';
                        inputId.value = id;
                        form.appendChild(inputId);

                        const inputNut = document.createElement('input');
                        inputNut.type = 'hidden';
                        inputNut.name = 'nut';
                        inputNut.value = 'Xóa thương hiệu';
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