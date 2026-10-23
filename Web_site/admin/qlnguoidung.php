<?php
require_once '../config.php';
include("xyly/xuly.php");

$pageTitle = "Quản lý tài khoản";
$p = new xuly();

// Xử lý cập nhật quyền (role) hoặc trạng thái (status) 
if (isset($_POST['action'])) {
    $user_id = intval($_POST['user_id']);

    if ($_POST['action'] === 'update_role') {
        $role = $_POST['role'];
        if (in_array($role, ['admin', 'customer'])) {
            $sql = "UPDATE users SET role = '$role' WHERE id = '$user_id' LIMIT 1";
            if ($p->themxoasua($sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật quyền thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật quyền!']);
            }
        }
        exit();
    }

    if ($_POST['action'] === 'update_status') {
        $status = $_POST['status'];
        if (in_array($status, ['active', 'locked'])) {
            $sql = "UPDATE users SET status = '$status' WHERE id = '$user_id' LIMIT 1";
            if ($p->themxoasua($sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái tài khoản thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái!']);
            }
        }
        exit();
    }
}

// Nhận dữ liệu tìm kiếm và lọc từ form GET
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$role_filter    = isset($_GET['role']) ? trim($_GET['role']) : '';
$status_filter  = isset($_GET['status']) ? trim($_GET['status']) : '';

//Truy vấn danh sách người dùng kèm đếm số lượng đơn hàng
$sql_users = "SELECT u.*, 
              (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders 
              FROM users u WHERE 1=1";

if ($search_keyword !== '') {
    $keyword_esc = addslashes($search_keyword);
    $sql_users .= " AND u.full_name LIKE '%$keyword_esc%'";
}

if ($role_filter !== '') {
    $role_esc = addslashes($role_filter);
    $sql_users .= " AND u.role = '$role_esc'";
}

if ($status_filter !== '') {
    $status_esc = addslashes($status_filter);
    $sql_users .= " AND u.status = '$status_esc'";
}

$sql_users .= " ORDER BY u.created_at DESC";
$list_users = $p->xuatdulieu($sql_users);

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content">
    <div class="content-card">
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Tài Khoản</h1>
                <p>Phân quyền, quản lý tài khoản và trạng thái hoạt động</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/addnguoidung.php" class="btn-primary" title="Thêm người dùng mới">
                <i class="fa-solid fa-plus"></i> <span>Tạo tài khoản Admin</span>
            </a>
        </div>

        <form method="GET" action="" class="filter-section" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div class="search-box" style="flex: 1; min-width: 250px;">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Tìm kiếm theo tên.." style="width: 100%; padding: 10px 14px;" class="product-input">
            </div>

            <select name="role" class="filter-select product-input" style="width: 180px; padding: 10px;">
                <option value="">-- Tất cả quyền --</option>
                <option value="admin" <?php echo ($role_filter === 'admin') ? 'selected' : ''; ?>>Admin (Quản trị)</option>
                <option value="customer" <?php echo ($role_filter === 'customer') ? 'selected' : ''; ?>>Khách hàng</option>
            </select>

            <select name="status" class="filter-select product-input" style="width: 180px; padding: 10px;">
                <option value="">-- Trạng thái --</option>
                <option value="active" <?php echo ($status_filter === 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="locked" <?php echo ($status_filter === 'locked') ? 'selected' : ''; ?>>Đã khóa</option>
            </select>

            <button type="submit" class="btn-filter" style="padding: 10px 20px; cursor: pointer;">Lọc</button>
            <?php if ($search_keyword !== '' || $role_filter !== '' || $status_filter !== ''): ?>
                <a href="qlnguoidung.php" class="btn-filter" style="text-decoration: none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ảnh</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Số đơn hàng</th>
                        <th>Quyền</th>
                        <th>Thao tác (Khóa / Mở)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($list_users)) {
                        $stt = 1;
                        foreach ($list_users as $usr) {
                            $avatarSrc = '../uploads/avatar/' . $usr['avatar'];
                            $avatarDefault = '../uploads/avatar/null.jpg';
                            $finalAvatar = (!empty($usr['avatar'])) ? $avatarSrc : $avatarDefault;

                            $statusStyle = ($usr['status'] === 'locked') ? 'color: #dc2626; background: #fee2e2;' : 'color: #059669; background: #d1fae5;';

                            echo '<tr>
                                    <td><strong>' . $stt++ . '</strong></td>
                                    <td><img src="' . htmlspecialchars($finalAvatar) . '" alt="Avatar" class="product-img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;"></td>
                                    <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($usr['full_name']) . '</span></td>
                                    <td>' . htmlspecialchars($usr['email']) . '</td>
                                    <td>' . htmlspecialchars($usr['phone']) . '</td>
                                    <td style="text-align: center; font-weight: bold;">' . intval($usr['total_orders']) . '</td>
                                    <td>
                                        <select class="role-select product-input" data-id="' . $usr['id'] . '" style="padding: 6px;">
                                            <option value="admin" ' . (($usr['role'] === 'admin') ? 'selected' : '') . '>Admin</option>
                                            <option value="customer" ' . (($usr['role'] === 'customer') ? 'selected' : '') . '>Khách hàng</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="role-select status-action-select product-input" data-id="' . $usr['id'] . '" style="padding: 6px; ' . $statusStyle . '">
                                            <option value="active" ' . (($usr['status'] === 'active') ? 'selected' : '') . '>Mở khóa</option>
                                            <option value="locked" ' . (($usr['status'] === 'locked') ? 'selected' : '') . '>Khóa tài khoản</option>
                                        </select>
                                    </td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="9" style="text-align: center; color: #64748b; padding: 20px;">Không tìm thấy tài khoản người dùng nào phù hợp.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Xử lý đổi quyền (role)
        document.querySelectorAll(".role-select:not(.status-action-select)").forEach(function(select) {
            select.addEventListener("change", function() {
                const userId = this.getAttribute("data-id");
                const newRole = this.value;

                fetch(window.location.href, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=update_role&user_id=${userId}&role=${encodeURIComponent(newRole)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Lỗi!", data.message, "error");
                        }
                    });
            });
        });

        // Xử lý khóa / mở tài khoản (status)
        document.querySelectorAll(".status-action-select").forEach(function(select) {
            select.addEventListener("change", function() {
                const userId = this.getAttribute("data-id");
                const newStatus = this.value;

                fetch(window.location.href, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=update_status&user_id=${userId}&status=${encodeURIComponent(newStatus)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Lỗi!", data.message, "error");
                        }
                    });
            });
        });
    });
</script>

<?php
include("includes/footer.php");
?>