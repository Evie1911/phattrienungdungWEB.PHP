<?php
require_once '../config.php';
include("xyly/xuly.php");

$pageTitle = "Quản lý thanh toán";
$p = new xuly();

// XỬ LÝ CÁC HÀNH ĐỘNG CẬP NHẬT TRẠNG THÁI (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    $payment_id = isset($_POST['payment_id']) ? intval($_POST['payment_id']) : 0;
    $action_type = $_POST['action_type'];

    if ($payment_id > 0) {
        $admin_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;

        // 1. LẤY order_id TỪ BẢNG payments ĐỂ CẬP NHẬT ĐƠN HÀNG TƯƠNG ỨNG
        $sql_get_order = "SELECT order_id FROM payments WHERE id = $payment_id LIMIT 1";
        $res_order = $p->xuatdulieu($sql_get_order);
        $order_id = (!empty($res_order) && isset($res_order[0]['order_id'])) ? intval($res_order[0]['order_id']) : 0;

        // DUYỆT THANH TOÁN 
        if ($action_type === 'approve_payment') {
            $ref = isset($_POST['reference_code']) ? trim($_POST['reference_code']) : '';
            $note = isset($_POST['note']) ? trim($_POST['note']) : 'Đã xác nhận thanh toán';
            $refEscaped = addslashes($ref);
            $noteEscaped = addslashes($note);

            // Cập nhật bảng payments
            $sql_update = "UPDATE payments SET status = 'Thành công', reference_code = IF('$refEscaped' != '', '$refEscaped', reference_code), note = '$noteEscaped', confirmed_at = NOW()";
            if ($admin_id) {
                $sql_update .= ", confirmed_by = $admin_id";
            }
            $sql_update .= " WHERE id = $payment_id";

            if ($p->themxoasua($sql_update)) {
                // ĐỒNG BỘ: Cập nhật payment_status trong bảng orders thành 'Đã thanh toán'
                if ($order_id > 0) {
                    $sql_update_order = "UPDATE orders SET payment_status = 'Đã thanh toán' WHERE id = $order_id";
                    $p->themxoasua($sql_update_order);
                }

                header("Location: thanhtoan.php?msg=success_approve");
                exit();
            }
        }
        // Hủy thanh toán khi hủy đơn
        elseif ($action_type === 'cancel_payment') {
            $cancel_reason = isset($_POST['cancel_reason']) ? trim($_POST['cancel_reason']) : 'Hủy đơn hàng';
            $reasonEscaped = addslashes($cancel_reason);

            $sql_update_payment = "UPDATE payments SET status = 'Đã hủy', note = CONCAT(IFNULL(note,''), ' | Hủy thanh toán: ', '$reasonEscaped') WHERE id = $payment_id";
            if ($p->themxoasua($sql_update_payment)) {
                // ĐỒNG BỘ: Cập nhật payment_status trong bảng orders thành 'Đã hủy'
                if ($order_id > 0) {
                    $sql_update_order = "UPDATE orders SET payment_status = 'Đã hủy' WHERE id = $order_id";
                    $p->themxoasua($sql_update_order);
                }

                header("Location: thanhtoan.php?msg=success_cancel");
                exit();
            }
        }
        // Yêu cầu hoàn tiền (Hoàn toàn bộ / một phần)
        elseif ($action_type === 'refund') {
            $rf_type = isset($_POST['rf_type']) ? trim($_POST['rf_type']) : 'full';
            $rf_amount = isset($_POST['rf_amount']) ? floatval($_POST['rf_amount']) : 0;
            $rf_reason_type = isset($_POST['rf_reason_type']) ? trim($_POST['rf_reason_type']) : '';
            $rf_reason_detail = isset($_POST['rf_reason_detail']) ? trim($_POST['rf_reason_detail']) : '';
            $rf_ref = isset($_POST['rf_ref']) ? trim($_POST['rf_ref']) : '';

            if ($rf_amount > 0) {
                $full_reason = $rf_reason_type;
                if (!empty($rf_reason_detail)) {
                    $full_reason .= "\n" . $rf_reason_detail;
                }
                $reasonEscaped = addslashes($full_reason);
                $refEscaped = addslashes($rf_ref);

                $sql_insert_refund = "INSERT INTO refunds (payment_id, refund_amount, reason, created_at) VALUES ($payment_id, $rf_amount, '$reasonEscaped', NOW())";
                @$p->themxoasua($sql_insert_refund);

                $sql_update_payment = "UPDATE payments SET status = 'Đã hoàn tiền', reference_code = IF('$refEscaped' != '', '$refEscaped', reference_code), note = CONCAT(IFNULL(note,''), ' | Hoàn tiền (', '$rf_type', '): ', '$reasonEscaped') WHERE id = $payment_id";
                if ($p->themxoasua($sql_update_payment)) {
                    // ĐỒNG BỘ: Cập nhật payment_status trong bảng orders thành 'Đã hoàn tiền'
                    if ($order_id > 0) {
                        $sql_update_order = "UPDATE orders SET payment_status = 'Đã hoàn tiền' WHERE id = $order_id";
                        $p->themxoasua($sql_update_order);
                    }

                    header("Location: thanhtoan.php?msg=success_refund");
                    exit();
                }
            }
        }
    }
}

// Nhận từ khóa tìm kiếm và bộ lọc phần dưới giữ nguyên...

// Nhận từ khóa tìm kiếm và bộ lọc
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$method_filter = isset($_GET['method']) ? trim($_GET['method']) : '';

//các số liệu thống kê
$sql_revenue = "SELECT SUM(amount) as total FROM payments WHERE status = 'Thành công'";
$res_revenue = $p->xuatdulieu($sql_revenue);
$total_revenue = !empty($res_revenue) && $res_revenue[0]['total'] ? floatval($res_revenue[0]['total']) : 0;

$sql_count_success = "SELECT COUNT(*) as total FROM payments WHERE status = 'Thành công'";
$res_cs = $p->xuatdulieu($sql_count_success);
$count_success = !empty($res_cs) ? intval($res_cs[0]['total']) : 0;

$sql_count_pending = "SELECT COUNT(*) as total FROM payments WHERE status = 'Chờ xác nhận'";
$res_cp = $p->xuatdulieu($sql_count_pending);
$count_pending = !empty($res_cp) ? intval($res_cp[0]['total']) : 0;

$sql_count_cod = "SELECT COUNT(*) as total FROM payments WHERE status = 'COD - Chờ thu tiền' OR (method = 'COD' AND status = 'Chờ xác nhận')";
$res_ccod = $p->xuatdulieu($sql_count_cod);
$count_cod = !empty($res_ccod) ? intval($res_ccod[0]['total']) : 0;

//Truy vấn danh sách thanh toán
$sql = "SELECT p.*, o.id as order_code_id, o.receiver_name, o.phone as order_phone, 
               u.full_name as customer_name, u.phone as customer_phone 
        FROM payments p 
        LEFT JOIN orders o ON p.order_id = o.id 
        LEFT JOIN users u ON o.user_id = u.id 
        WHERE 1=1";

if ($search !== '') {
    $search_esc = addslashes($search);
    $sql .= " AND (p.payment_code LIKE '%$search_esc%' OR o.id LIKE '%$search_esc%' OR u.full_name LIKE '%$search_esc%' OR o.receiver_name LIKE '%$search_esc%' OR u.phone LIKE '%$search_esc%' OR o.phone LIKE '%$search_esc%')";
}

if ($status_filter !== '') {
    $status_esc = addslashes($status_filter);
    $sql .= " AND p.status = '$status_esc'";
}

if ($method_filter !== '') {
    $method_esc = addslashes($method_filter);
    $sql .= " AND p.method = '$method_esc'";
}

$sql .= " ORDER BY p.id DESC";
$payments_list = $p->xuatdulieu($sql);

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content">
    <div class="content-card">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Tổng Doanh Thu</div>
                <div class="stat-value stat-value-default" style="color: #0f172a;"><?php echo number_format($total_revenue, 0, ',', '.'); ?> đ</div>
                <div class="stat-desc stat-desc-success" style="color: #16a34a;"><i class="fa-solid fa-arrow-up"></i> Từ các giao dịch thành công</div>
            </div>

            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Thành Công</div>
                <div class="stat-value stat-value-success" style="color: #16a34a;"><?php echo $count_success; ?></div>
                <div class="stat-desc stat-desc-muted" style="color: #94a3b8;">Đã quyết toán xong</div>
            </div>

            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Chờ Xác Nhận / Duyệt</div>
                <div class="stat-value stat-value-warning" style="color: #d97706;"><?php echo $count_pending; ?></div>
                <div class="stat-desc stat-desc-warning" style="color: #d97706;"><i class="fa-regular fa-clock"></i> Cần kiểm tra ngân hàng</div>
            </div>

            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Thanh Toán Khi Nhận Hàng (COD)</div>
                <div class="stat-value stat-value-info" style="color: #0284c7;"><?php echo $count_cod; ?></div>
                <div class="stat-desc stat-desc-info" style="color: #0284c7;"><i class="fa-solid fa-truck-fast"></i> Chờ Shipper thu tiền</div>
            </div>
        </div>

        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Thanh Toán</h1>
                <p>Theo dõi, kiểm tra lịch sử giao dịch và trạng thái thanh toán đơn hàng</p>
            </div>
        </div>

        <form method="GET" action="" class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" name="search" placeholder="Tìm theo mã giao dịch, mã đơn hàng, tên khách hàng, SĐT..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <select name="status" class="filter-select">
                <option value="">-- Trạng thái thanh toán --</option>
                <option value="Thành công" <?php echo ($status_filter === 'Thành công') ? 'selected' : ''; ?>>Đã thanh toán / Thành công</option>
                <option value="Chờ xác nhận" <?php echo ($status_filter === 'Chờ xác nhận') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                <option value="COD - Chờ thu tiền" <?php echo ($status_filter === 'COD - Chờ thu tiền') ? 'selected' : ''; ?>>COD - Chờ thu tiền</option>
                <option value="Thất bại" <?php echo ($status_filter === 'Thất bại') ? 'selected' : ''; ?>>Thất bại</option>
                <option value="Đã hoàn tiền" <?php echo ($status_filter === 'Đã hoàn tiền') ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                <option value="Đã hủy" <?php echo ($status_filter === 'Đã hủy') ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
            <select name="method" class="filter-select">
                <option value="">-- Phương thức thanh toán --</option>
                <option value="BANK_TRANSFER" <?php echo ($method_filter === 'BANK_TRANSFER') ? 'selected' : ''; ?>>Chuyển khoản Ngân hàng</option>
                <option value="VNPAY_QR" <?php echo ($method_filter === 'VNPAY_QR') ? 'selected' : ''; ?>>VNPay QR</option>
                <option value="COD" <?php echo ($method_filter === 'COD') ? 'selected' : ''; ?>>Thanh toán khi nhận hàng (COD)</option>
                <option value="SANDBOX" <?php echo ($method_filter === 'SANDBOX') ? 'selected' : ''; ?>>Sandbox / Thử nghiệm</option>
            </select>
            <button type="submit" class="btn-filter">Lọc</button>
            <?php if ($search !== '' || $status_filter !== '' || $method_filter !== ''): ?>
                <a href="thanhtoan.php" class="btn-filter" style="text-decoration:none; display: inline-flex; align-items: center; justify-content: center;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã giao dịch</th>
                        <th>Khách hàng</th>
                        <th>Phương thức</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($payments_list)) {
                        foreach ($payments_list as $row) {
                            $method_badge = '';
                            switch ($row['method']) {
                                case 'COD':
                                    $method_badge = '<span class="badge-category"><i class="fa-solid fa-truck-fast"></i> Thanh toán COD</span>';
                                    break;
                                case 'BANK_TRANSFER':
                                    $method_badge = '<span class="badge-category" style="color: #2563eb;"><i class="fa-solid fa-building-columns"></i> Chuyển khoản</span>';
                                    break;
                                case 'VNPAY_QR':
                                    $method_badge = '<span class="badge-category" style="color: #4f46e5;"><i class="fa-solid fa-qrcode"></i> VNPay QR</span>';
                                    break;
                                case 'SANDBOX':
                                    $method_badge = '<span class="badge-category" style="color: #d97706;"><i class="fa-solid fa-vial"></i> Sandbox</span>';
                                    break;
                                default:
                                    $method_badge = '<span class="badge-category"><i class="fa-solid fa-wallet"></i> ' . htmlspecialchars($row['method']) . '</span>';
                                    break;
                            }

                            $status_class = '';
                            switch ($row['status']) {
                                case 'Thành công':
                                    $status_class = 'status-success';
                                    break;
                                case 'Chờ xác nhận':
                                    $status_class = 'status-warning';
                                    break;
                                case 'COD - Chờ thu tiền':
                                    $status_class = 'status-cod';
                                    break;
                                case 'Đã hoàn tiền':
                                    $status_class = 'status-danger';
                                    break;
                                default:
                                    $status_class = 'status-default';
                                    break;
                            }

                            $customer_name = !empty($row['customer_name']) ? $row['customer_name'] : (!empty($row['receiver_name']) ? $row['receiver_name'] : 'Khách vãng lai');
                            $customer_phone = !empty($row['customer_phone']) ? $row['customer_phone'] : (!empty($row['order_phone']) ? $row['order_phone'] : 'Chưa có SĐT');
                            $amount_formatted = number_format($row['amount'], 0, ',', '.') . ' đ';
                            $ref_code = !empty($row['reference_code']) ? $row['reference_code'] : 'Chưa có';
                            $note_text = !empty($row['note']) ? $row['note'] : 'Không có ghi chú';

                            echo '<tr>
                                    <td><strong>' . htmlspecialchars($row['payment_code']) . '</strong></td>
                                    <td>
                                        <div class="customer-info">
                                            <span class="customer-name">' . htmlspecialchars($customer_name) . '</span>
                                            <span class="customer-phone">' . htmlspecialchars($customer_phone) . '</span>
                                        </div>
                                    </td>
                                    <td>' . $method_badge . '</td>
                                    <td><strong>' . $amount_formatted . '</strong></td>
                                    <td>' . date('d/m/Y H:i', strtotime($row['created_at'])) . '</td>
                                    <td><span class="badge-status ' . $status_class . '">' . htmlspecialchars($row['status']) . '</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Nút Xem chi tiết -->
                                            <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"
                                                data-code="' . htmlspecialchars($row['payment_code']) . '"
                                                data-name="' . htmlspecialchars($customer_name) . '"
                                                data-phone="' . htmlspecialchars($customer_phone) . '"
                                                data-amount="' . $amount_formatted . '"
                                                data-method="' . htmlspecialchars($row['method']) . '"
                                                data-ref="' . htmlspecialchars($ref_code) . '"
                                                data-status="' . htmlspecialchars($row['status']) . '"
                                                data-note="' . htmlspecialchars($note_text) . '">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>';

                            //  NÚT DUYỆT CHUNG DUY NHẤT (Nếu chưa Thành công / Chưa Hoàn tiền / Chưa Hủy)
                            if ($row['status'] === 'Chờ xác nhận' || $row['status'] === 'COD - Chờ thu tiền') {
                                echo '<button type="button" class="btn-action btn-approve action-approve" title="Duyệt thanh toán"
                                        data-id="' . $row['id'] . '"
                                        data-code="' . htmlspecialchars($row['payment_code']) . '"
                                        data-name="' . htmlspecialchars($customer_name) . '"
                                        data-method="' . htmlspecialchars($row['method']) . '"
                                        data-amount="' . $amount_formatted . '">
                                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
                                      </button>';

                                // Nút Hủy giao dịch (Khách chưa chuyển hoặc sao kê chưa có)
                                echo '<button type="button" class="btn-action btn-cancel action-cancel" title="Hủy giao dịch"
                                        data-id="' . $row['id'] . '"
                                        data-code="' . htmlspecialchars($row['payment_code']) . '">
                                        <i class="fa-solid fa-ban" style="color: #ef4444;"></i>
                                      </button>';
                            }

                            // 2. NÚT HOÀN TIỀN (Dành cho đơn đã Thành công)
                            if ($row['status'] === 'Thành công') {
                                echo '<button type="button" class="btn-action btn-refund action-refund" title="Hoàn tiền giao dịch"
                                        data-id="' . $row['id'] . '"
                                        data-code="' . htmlspecialchars($row['payment_code']) . '"
                                        data-name="' . htmlspecialchars($customer_name) . '"
                                        data-method="' . htmlspecialchars($row['method']) . '"
                                        data-paid="' . $row['amount'] . '">
                                        <i class="fa-solid fa-rotate-left" style="color: #e11d48;"></i>
                                      </button>';
                            }

                            echo '</div>
                                    </td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" style="text-align: center; padding: 20px;">Không tìm thấy giao dịch thanh toán nào.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- Thông báo-->
<?php if (isset($_GET['msg'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($_GET['msg'] === 'success_approve'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã duyệt thanh toán thành công!',
                    timer: 1500,
                    showConfirmButton: false
                });
            <?php elseif ($_GET['msg'] === 'success_refund'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã ghi nhận hoàn tiền giao dịch thành công!',
                    timer: 1500,
                    showConfirmButton: false
                });
            <?php elseif ($_GET['msg'] === 'success_cancel'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Đã hủy!',
                    text: 'Đã cập nhật trạng thái giao dịch thành Đã hủy!',
                    timer: 1500,
                    showConfirmButton: false
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function fmtVnd(num) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(num).replace('₫', 'đ');
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function sendPostRequest(paymentId, actionType, extraData = {}) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'payment_id';
            inputId.value = paymentId;
            form.appendChild(inputId);

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action_type';
            inputAction.value = actionType;
            form.appendChild(inputAction);

            for (const key in extraData) {
                const inputExtra = document.createElement('input');
                inputExtra.type = 'hidden';
                inputExtra.name = key;
                inputExtra.value = extraData[key];
                form.appendChild(inputExtra);
            }

            document.body.appendChild(form);
            form.submit();
        }

        // 1. POPUP XEM CHI TIẾT
        document.addEventListener("click", function(e) {
            const viewBtn = e.target.closest(".action-view");
            if (viewBtn) {
                e.preventDefault();
                Swal.fire({
                    title: '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i> Chi Tiết Thanh Toán</div>',
                    html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.8; color: #334155; border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 5px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Mã giao dịch:</span>
                        <strong style="color: #0f172a;">${viewBtn.dataset.code}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Tên khách hàng:</span>
                        <strong style="color: #0f172a;">${viewBtn.dataset.name}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Số điện thoại:</span>
                        <strong style="color: #0f172a;">${viewBtn.dataset.phone}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Số tiền:</span>
                        <strong style="color: #2563eb; font-size: 16px;">${viewBtn.dataset.amount}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Phương thức:</span>
                        <strong style="color: #0f172a;">${viewBtn.dataset.method}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; align-items: center;">
                        <span style="color: #64748b;">Mã đối soát / Ref:</span>
                        <span style="font-family: monospace; background: #f1f5f9; color: #334155; padding: 3px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #e2e8f0;">${viewBtn.dataset.ref}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Trạng thái:</span>
                        <strong style="color: #0369a1;">${viewBtn.dataset.status}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <span style="color: #64748b;">Ghi chú:</span>
                        <span style="font-style: italic; color: #475569;">${viewBtn.dataset.note}</span>
                    </div>
                </div>`,
                    confirmButtonText: "Đóng",
                    confirmButtonColor: "#3b82f6",
                    width: "550px",
                    padding: "20px",
                });
            }
        });

        // 2. POPUP DUYỆT (Dùng cho cả BANK_TRANSFER, VNPAY_QR, COD, SANDBOX)
        document.addEventListener("click", function(e) {
            const approveBtn = e.target.closest(".action-approve");
            if (approveBtn) {
                e.preventDefault();
                const id = approveBtn.dataset.id;
                const code = approveBtn.dataset.code;
                const name = approveBtn.dataset.name;
                const method = approveBtn.dataset.method;
                const amount = approveBtn.dataset.amount;

                Swal.fire({
                    title: '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Xác Nhận Duyệt Thanh Toán</div>',
                    html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.6; color: #334155; margin-top: 10px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px; line-height: 1.8;">
                        <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Mã GD:</span> <strong>${code}</strong></div>
                        <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Khách hàng:</span> <strong>${name}</strong></div>
                        <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Phương thức:</span> <strong style="color: #2563eb;">${method}</strong></div>
                        <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Số tiền:</span> <strong style="color: #16a34a; font-size: 15px;">${amount}</strong></div>
                    </div>
                    
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Mã đối soát / Ref (Ngân hàng/VNPay nếu có):</label>
                    <input type="text" id="swal-input-ref" class="swal2-input" placeholder="VD: VCB-987654321..." style="width: 100%; margin: 0 0 12px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">

                    <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Ghi chú duyệt:</label>
                    <textarea id="swal-input-note" class="swal2-textarea" placeholder="Nhập ghi chú xác nhận..." style="width: 100%; height: 70px; margin: 0 0 10px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">Đã xác nhận thanh toán thành công</textarea>
                </div>`,
                    showCancelButton: true,
                    confirmButtonText: "Xác Nhận Duyệt",
                    cancelButtonText: "Hủy",
                    confirmButtonColor: "#10b981",
                    width: "520px",
                    padding: "20px",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const refVal = document.getElementById("swal-input-ref").value;
                        const noteVal = document.getElementById("swal-input-note").value;
                        sendPostRequest(id, 'approve_payment', {
                            reference_code: refVal,
                            note: noteVal
                        });
                    }
                });
            }
        });

        // 3. POPUP HỦY THANH TOÁN
        document.addEventListener("click", function(e) {
            const cancelBtn = e.target.closest(".action-cancel");
            if (cancelBtn) {
                e.preventDefault();
                const id = cancelBtn.dataset.id;
                const code = cancelBtn.dataset.code;

                Swal.fire({
                    title: 'Xác nhận hủy thanh toán?',
                    text: `Xác nhận chưa nhận được tiền cho mã giao dịch "${code}"? Trạng thái sẽ đổi thành "Đã hủy".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận Hủy',
                    cancelButtonText: 'Bỏ qua',
                    confirmButtonColor: '#ef4444',
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendPostRequest(id, 'cancel_payment', {
                            cancel_reason: 'Admin kiểm tra chưa nhận được tiền / Hủy đơn'
                        });
                    }
                });
            }
        });

        // 4. POPUP HOÀN TIỀN
        document.addEventListener("click", function(e) {
            const refundBtn = e.target.closest(".action-refund");
            if (refundBtn) {
                e.preventDefault();
                const id = refundBtn.dataset.id;
                const paid = parseFloat(refundBtn.dataset.paid);
                const remain = paid;

                const label = "display:block; font-size:13px; font-weight:500; color:#64748b; margin-bottom:5px;";
                const field = "width:100%; font-size:13px; padding:10px; border:1px solid #cbd5e1; border-radius:6px; box-sizing:border-box; margin-bottom:12px;";

                Swal.fire({
                    title: '<div style="font-size:18px; font-weight:600; text-align:left; color:#0f172a; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-rotate-left" style="color:#e11d48;"></i> Hoàn Tiền Giao Dịch</div>',
                    html: `
                <div style="text-align:left; font-size:14px; color:#334155; margin-top:8px;">
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:16px; line-height:1.9;">
                        <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Mã giao dịch</span><strong>${escapeHtml(refundBtn.dataset.code)}</strong></div>
                        <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Khách hàng</span><strong>${escapeHtml(refundBtn.dataset.name)}</strong></div>
                        <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Phương thức</span><strong>${escapeHtml(refundBtn.dataset.method)}</strong></div>
                        <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Đã thanh toán</span><strong>${fmtVnd(paid)}</strong></div>
                    </div>
                    <label style="${label}">Loại hoàn tiền</label>
                    <div style="display:flex; gap:20px; margin-bottom:14px;">
                        <label style="cursor:pointer;"><input type="radio" name="rf-type" value="full" checked> Hoàn toàn bộ</label>
                    </div>
                    <label style="${label}">Số tiền hoàn (đ)</label>
                    <input type="number" id="rf-amount" min="1" max="${remain}" step="1000" value="${remain}" disabled style="${field}background:#f1f5f9;">
                    <label style="${label}">Lý do hoàn tiền (*)</label>
                    <select id="rf-reason-type" style="${field}">
                        <option value="">-- Chọn lý do --</option>
                        <option value="Hủy đơn">Hủy đơn</option>
                        <option value="Khách trả hàng">Khách trả hàng</option>
                        <option value="Hàng lỗi / sai mô tả">Hàng lỗi / sai mô tả</option>
                        <option value="Thanh toán nhầm / trùng">Thanh toán nhầm / trùng</option>
                        <option value="Khác">Khác</option>
                    </select>
                    <textarea id="rf-reason" placeholder="Ghi chú chi tiết..." style="${field}height:70px; resize:vertical;"></textarea>
                    <label style="${label}">Mã giao dịch hoàn tiền / Ref (nếu có)</label>
                    <input type="text" id="rf-ref" placeholder="VD: mã chuyển khoản hoàn cho khách" style="${field}">
                    <label style="display:flex; gap:8px; align-items:flex-start; font-size:13px; color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:10px; cursor:pointer;">
                        <input type="checkbox" id="rf-confirm" style="margin-top:3px;">
                        <span>Tôi xác nhận đã kiểm tra và thao tác này không thể hoàn tác.</span>
                    </label>
                </div>`,
                    showCancelButton: true,
                    confirmButtonText: "Xác Nhận Hoàn Tiền",
                    cancelButtonText: "Hủy",
                    confirmButtonColor: "#e11d48",
                    width: "560px",
                    padding: "20px",
                    didOpen: () => {
                        const amountInput = document.getElementById('rf-amount');
                        document.querySelectorAll('input[name="rf-type"]').forEach(r => {
                            r.addEventListener('change', () => {
                                const partial = r.value === 'partial' && r.checked;
                                if (r.checked) {
                                    amountInput.disabled = !partial;
                                    amountInput.style.background = partial ? '#fff' : '#f1f5f9';
                                    if (!partial) amountInput.value = remain;
                                }
                            });
                        });
                    },
                    preConfirm: () => {
                        const rfType = document.querySelector('input[name="rf-type"]:checked').value;
                        const rfAmount = parseFloat(document.getElementById('rf-amount').value);
                        const reasonType = document.getElementById('rf-reason-type').value;
                        const reasonDetail = document.getElementById('rf-reason').value.trim();
                        const rfRef = document.getElementById('rf-ref').value.trim();
                        const isConfirmed = document.getElementById('rf-confirm').checked;

                        if (isNaN(rfAmount) || rfAmount <= 0 || rfAmount > remain) {
                            Swal.showValidationMessage("Số tiền hoàn không hợp lệ!");
                            return false;
                        }
                        if (!reasonType) {
                            Swal.showValidationMessage("Vui lòng chọn lý do hoàn tiền!");
                            return false;
                        }
                        if (!isConfirmed) {
                            Swal.showValidationMessage("Bạn phải xác nhận trước khi hoàn tiền!");
                            return false;
                        }

                        return {
                            rf_type: rfType,
                            rf_amount: rfAmount,
                            rf_reason_type: reasonType,
                            rf_reason_detail: reasonDetail,
                            rf_ref: rfRef
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const data = result.value;
                        sendPostRequest(id, 'refund', {
                            rf_type: data.rf_type,
                            rf_amount: data.rf_amount,
                            rf_reason_type: data.rf_reason_type,
                            rf_reason_detail: data.rf_reason_detail,
                            rf_ref: data.rf_ref
                        });
                    }
                });
            }
        });
    });
</script>

<?php
include("includes/footer.php");
?>