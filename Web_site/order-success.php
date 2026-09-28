<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';

$order_id = $_GET['order_id'] ?? '';
$order = get_order_by_id($order_id);

if (!$order) {
    set_flash('error', 'Không tìm thấy thông tin đơn hàng.');
    redirect('index.php');
}

$page_title = 'Đặt Hàng Thành Công - ' . $order['id'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 800px;">
    <div style="background: #fff; padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 70px; height: 70px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 15px;">
                <i class="fas fa-check"></i>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--secondary-color);">CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!</h1>
            <p style="color: var(--text-secondary);">Mã đơn hàng của bạn là: <strong style="color: var(--primary-color); font-size: 1.1rem;"><?php echo sanitize($order['id']); ?></strong></p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: var(--radius-md);">
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 8px;">Thông tin giao hàng</h4>
                <p style="font-size: 0.88rem; line-height: 1.6; color: var(--text-secondary);">
                    <strong>Người nhận:</strong> <?php echo sanitize($order['customer_name']); ?><br>
                    <strong>SĐT:</strong> <?php echo sanitize($order['customer_phone']); ?><br>
                    <strong>Email:</strong> <?php echo sanitize($order['customer_email']); ?><br>
                    <strong>Địa chỉ:</strong> <?php echo sanitize($order['customer_address']); ?>
                </p>
            </div>
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 8px;">Chi tiết đơn hàng</h4>
                <p style="font-size: 0.88rem; line-height: 1.6; color: var(--text-secondary);">
                    <strong>Phương thức:</strong> <?php echo $order['payment_method'] === 'online' ? 'Thanh toán Trực tuyến' : 'Thanh toán khi nhận hàng (COD)'; ?><br>
                    <strong>Trạng thái thanh toán:</strong> 
                    <span class="badge badge-<?php echo $order['payment_status'] === 'paid' ? 'completed' : 'pending'; ?>">
                        <?php echo $order['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                    </span><br>
                    <strong>Tổng tiền:</strong> <strong style="color: var(--accent-red); font-size: 1.05rem;"><?php echo format_currency($order['total_amount']); ?></strong>
                </p>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
