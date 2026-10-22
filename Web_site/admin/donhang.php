<?php
require_once '../config.php';
include("xyly/xuly.php");

$p = new xuly();

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];
    $status_order = [
        'Chờ xác nhận' => 1,
        'Đã xác nhận' => 2,
        'Đang đóng gói' => 3,
        'Đang giao hàng' => 4,
        'Hoàn thành' => 5,
        'Đã hủy' => 6,
    ];
    // Lấy trạng thái hiện tại trong database
    $current_order = $p->xuatdulieu("SELECT order_status FROM orders WHERE id = '$order_id' LIMIT 1");
    if (!empty($current_order)) {
        $old_status = $current_order[0]['order_status'];
        // Nếu trạng thái là 'Đã hủy' thì không cho sửa nữa
        if ($old_status === 'Đã hủy' || $old_status === 'Hoàn thành') {
            echo json_encode(['status' => 'error', 'message' => 'Đơn hàng đã kết thúc, không thể thay đổi trạng thái!']);
            exit();
        }
        // Kiểm tra nếu không phải chuyển sang 'Đã hủy' và cấp độ mới nhỏ hơn cấp độ cũ (quay đầu)
        if ($new_status !== 'Đã hủy' && isset($status_order[$old_status]) && isset($status_order[$new_status])) {
            if ($status_order[$new_status] < $status_order[$old_status]) {
                echo json_encode(['status' => 'error', 'message' => 'Không thể quay lại trạng thái cũ!']);
                exit();
            }
        }
        // Tiến hành cập nhật nếu hợp lệ
        $update_sql = "UPDATE orders SET order_status = '$new_status' WHERE id = '$order_id' LIMIT 1";
        if ($p->themxoasua($update_sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái!']);
        }
    }
    exit();
}

// Xử lý lấy chi tiết đơn hàng
if (isset($_GET['action']) && $_GET['action'] === 'get_order_detail') {
    $order_id = intval($_GET['id']);
    // Lấy thông tin đơn hàng và thanh toán
    $order_info = $p->xuatdulieu("SELECT * FROM orders o LEFT JOIN payments p ON o.id = p.order_id WHERE o.id = '$order_id' LIMIT 1");
    // Lấy danh sách sản phẩm trong đơn hàng
    $order_items = $p->xuatdulieu("SELECT * FROM order_items WHERE order_id = '$order_id'");
    //nếu có chuyển mảng thành chuỗi dinh dang json 
    if (!empty($order_info)) {
        echo json_encode([
            'status' => 'success',
            'order' => $order_info[0],
            'items' => $order_items
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng!']);
    }
    exit();
}
//Bộ lọc don hang
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$status_filter  = isset($_GET['status']) ? trim($_GET['status']) : '';
// Truy vấn danh sách đơn hàng từ Tổng số lượng sản phẩm trong đơn hàng Tên sản phẩm đầu tiên đơn hàng
$sql_orders = "SELECT o.*,
               (SELECT SUM(quantity) FROM order_items WHERE order_id = o.id) as total_qty,
               (SELECT product_name FROM order_items WHERE order_id = o.id LIMIT 1) as first_product_name
               FROM orders o WHERE 1=1";

if ($search_keyword !== '') {
    $keyword_esc = addslashes($search_keyword);
    $sql_orders .= " AND (o.order_code LIKE '%$keyword_esc%' OR o.receiver_name LIKE '%$keyword_esc%' OR o.phone LIKE '%$keyword_esc%')";
}
if ($status_filter !== '') {
    $status_esc = addslashes($status_filter);
    $sql_orders .= " AND o.order_status = '$status_esc'";
}

$sql_orders .= " ORDER BY o.created_at DESC";

$list_orders = $p->xuatdulieu($sql_orders);

$pageTitle = "Quản lý đơn hàng";

include("includes/header.php");
include("includes/sidebar.php");
include("includes/navbar.php");
?>

<main class="main-content">
    <div class="content-card">
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Đơn Hàng</h1>
                <p>Theo dõi, cập nhật trạng thái và quản lý đơn hàng của khách hàng</p>
            </div>
        </div>

        <form method="GET" action="" class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" name="keyword" id="searchOrderInput" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Tìm theo mã đơn hàng, tên khách hàng, số điện thoại...">
            </div>
            <select name="status" class="filter-select" id="filterStatus">
                <option value="">-- Trạng thái đơn hàng --</option>
                <option value="Chờ xác nhận" <?php echo ($status_filter === 'Chờ xác nhận') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                <option value="Đã xác nhận" <?php echo ($status_filter === 'Đã xác nhận') ? 'selected' : ''; ?>>Đã xác nhận</option>
                <option value="Đang đóng gói" <?php echo ($status_filter === 'Đang đóng gói') ? 'selected' : ''; ?>>Đang đóng gói</option>
                <option value="Đang giao hàng" <?php echo ($status_filter === 'Đang giao hàng') ? 'selected' : ''; ?>>Đang giao hàng</option>
                <option value="Hoàn thành" <?php echo ($status_filter === 'Hoàn thành') ? 'selected' : ''; ?>>Hoàn thành</option>
                <option value="Đã hủy" <?php echo ($status_filter === 'Đã hủy') ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
            <button type="submit" class="btn-filter">Tìm kiếm</button>
            <?php if ($search_keyword !== '' || $status_filter !== ''): ?>
                <a href="donhang.php" style="text-decoration: none;">Làm mới</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm chính</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Tình trạng</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_orders)): ?>
                        <?php foreach ($list_orders as $ord): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ord['order_code']); ?></strong></td>
                                <td>
                                    <div class="customer-name"><?php echo htmlspecialchars($ord['receiver_name']); ?></div>
                                    <div class="customer-phone"><?php echo htmlspecialchars($ord['phone']); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($ord['first_product_name']); ?> <?php echo ($ord['total_qty'] > 1) ? '...và sản phẩm' : ''; ?></td>
                                <td><?php echo intval($ord['total_qty']); ?></td>
                                <td><strong><?php echo number_format($ord['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                                <td>
                                    <?php
                                    $current_status = $ord['order_status'];
                                    // Kiểm tra nếu đơn hàng đã kết thúc (Hoàn thành hoặc Đã hủy)
                                    $is_finished = ($current_status === 'Hoàn thành' || $current_status === 'Đã hủy');
                                    ?>
                                    <select  class="product-input status-select" data-id="<?php echo $ord['id']; ?>" <?php echo $is_finished ? 'disabled style="background-color: #f1f5f9; cursor: not-allowed;"' : ''; ?>>
                                        <?php
                                        $statuses = [
                                            'Chờ xác nhận' => 1,
                                            'Đã xác nhận' => 2,
                                            'Đang đóng gói' => 3,
                                            'Đang giao hàng' => 4,
                                            'Hoàn thành' => 5,
                                            'Đã hủy' => 6
                                        ];
                                        $current_stt = isset($statuses[$current_status]) ? $statuses[$current_status] : 1;
                                        foreach ($statuses as $st => $stt) {
                                            $selected = ($current_status === $st) ? 'selected' : '';
                                            $disabled = '';

                                            // 1. Nếu đã 'Hoàn thành' thì khóa không cho chọn 'Đã hủy'
                                            if ($current_status === 'Hoàn thành' && $st === 'Đã hủy') {
                                                $disabled = 'disabled style="color: #cbd5e1;"';
                                            }
                                            // 2. Nếu đã 'Đã hủy' thì khóa tất cả các trạng thái khác
                                            elseif ($current_status === 'Đã hủy' && $st !== 'Đã hủy') {
                                                $disabled = 'disabled style="color: #cbd5e1;"';
                                            }
                                            // 3. Khóa các bước ngược lại về quá khứ
                                            elseif ($stt < $current_stt) {
                                                $disabled = 'disabled style="color: #cbd5e1;"';
                                            }

                                            echo "<option value=\"$st\" $selected $disabled>$st</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn-action btn-action-view" data-id="<?php echo $ord['id']; ?>" title="Xem chi tiết">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">Chưa có đơn hàng nào trong hệ thống.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Xử lý thông báo thay đổi trạng thái đơn hàng 
        document.querySelectorAll(".status-select").forEach(function(select) {
            select.addEventListener("change", function() {
                const orderId = this.getAttribute("data-id");
                const newStatus = this.value;
                fetch(window.location.href, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=update_status&order_id=${orderId}&status=${encodeURIComponent(newStatus)}`
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
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Lỗi!", data.message, "error");
                        }
                    });
            });
        });

        // Xử lý xem chi tiết đơn hàng 
        document.querySelectorAll(".btn-action-view").forEach((button) => {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                const orderId = this.getAttribute("data-id");
                fetch(`?action=get_order_detail&id=${orderId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const ord = data.order;
                            const items = data.items;

                            let itemsHtml = '';
                            items.forEach(item => {
                                let subtotal = item.unit_price * item.quantity;
                                itemsHtml += `
                                <tr>
                                    <td style="padding: 6px;">${item.product_name} ${item.color ? '('+item.color+')' : ''}</td>
                                    <td style="padding: 6px; text-align: center;">${item.quantity}</td>
                                    <td style="padding: 6px; text-align: right;">${Number(subtotal).toLocaleString('vi-VN')} đ</td>
                                </tr>
                            `;
                            });

                            Swal.fire({
                                title: `<strong>Chi Tiết Đơn Hàng: ${ord.order_code}</strong>`,
                                html: `
                                <div style="text-align: left; font-size: 14px; line-height: 1.6;">
                                    <p><strong>Trạng thái đơn hàng:</strong> <span style="color: #e67e22; font-weight: 500;">${ord.order_status}</span></p>
                                    <p><strong>Thanh toán:</strong> ${ord.payment_method} - <span style="color: #27ae60; font-weight: 500;">${ord.payment_status}</span></p>
                                    ${ord.reference_code ? `<p><strong>Mã giao dịch (TXN):</strong> <span style="font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">${ord.reference_code}</span></p>` : ''}
                                    
                                    <p style="margin-bottom: 5px; margin-top: 10px;"><strong>Khách hàng:</strong></p>
                                    <div style="padding-left: 10px; background: #f8fafc; padding: 8px; border-radius: 6px;">
                                        - ${ord.receiver_name}<br>
                                        - SĐT: ${ord.phone}<br>
                                        - Email: ${ord.email}<br>
                                        - Địa chỉ: ${ord.address}
                                        ${ord.note ? '<br>- Ghi chú: ' + ord.note : ''}
                                    </div>
                                    
                                    <hr style="border: 0; border-top: 1px dashed #ccc; margin: 15px 0;">
                                    
                                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #ddd;">
                                                <th style="padding: 6px;">Sản phẩm</th>
                                                <th style="padding: 6px; text-align: center;">SL</th>
                                                <th style="padding: 6px; text-align: right;">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${itemsHtml}
                                        </tbody>
                                    </table>
                                    
                                    <hr style="border: 0; border-top: 1px dashed #ccc; margin: 15px 0;">
                                    
                                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 15px;">
                                        <span>Phí vận chuyển:</span>
                                        <span>${Number(ord.shipping_fee).toLocaleString('vi-VN')} đ</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px; margin-top: 5px;">
                                        <span>Tổng thanh toán:</span>
                                        <span style="color: #e74c3c;">${Number(ord.total_amount).toLocaleString('vi-VN')} đ</span>
                                    </div>
                                </div>
                            `,
                                confirmButtonText: "Đóng",
                                confirmButtonColor: "#3085d6",
                                width: "580px",
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