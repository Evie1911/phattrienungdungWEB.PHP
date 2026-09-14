<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';

$order_id = $_REQUEST['order_id'] ?? '';
$order = get_order_by_id($order_id);

if (!$order) {
    set_flash('error', 'Đơn hàng không tồn tại hoặc đã hết hạn.');
    redirect('index.php');
}

// Xử lý kết quả mô phỏng thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token()) {
        $result = $_POST['simulation_result'] ?? '';

        if ($result === 'success') {
            if (isset($_SESSION['data']['orders'][$order_id])) {
                $_SESSION['data']['orders'][$order_id]['payment_status'] = 'paid';
            }
            set_flash('success', 'Mô phỏng thanh toán TRỰC TUYẾN THÀNH CÔNG! Đơn hàng đã được xác nhận.');
            redirect('order-success.php?order_id=' . urlencode($order_id));
        } elseif ($result === 'failed') {
            if (isset($_SESSION['data']['orders'][$order_id])) {
                $_SESSION['data']['orders'][$order_id]['payment_status'] = 'failed';
            }
            set_flash('error', 'Mô phỏng thanh toán THẤT BẠI! Vui lòng thử lại.');
        } elseif ($result === 'cancel') {
            redirect('checkout.php');
        }
    }
}

$page_title = 'Mô Phỏng Thanh Toán Trực Tuyến - ' . $order['id'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 600px;">
    <div style="background: #fff; padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 40px; text-align: center;">
        <div style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 12px;">
            <i class="fas fa-credit-card"></i>
        </div>
        <h1 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 6px;">Mô Phỏng Thanh Toán Trực Tuyến</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 20px;">Cổng thanh toán Sandbox thử nghiệm cho bài tập lớn Web</p>

        <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 24px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>Mã đơn hàng:</span>
                <strong><?php echo sanitize($order['id']); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>Khách hàng:</span>
                <strong><?php echo sanitize($order['customer_name']); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem; border-top: 1px dashed var(--border-color); padding-top: 8px; margin-top: 8px;">
                <span>Số tiền thanh toán:</span>
                <strong style="color: var(--accent-red);"><?php echo format_currency($order['total_amount']); ?></strong>
            </div>
        </div>

        <form action="<?php echo BASE_URL; ?>/payment-process.php" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="order_id" value="<?php echo sanitize($order['id']); ?>">

            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                <button type="submit" name="simulation_result" value="success" class="btn btn-primary btn-lg btn-block">
                    <i class="fas fa-check-circle"></i> Giả lập Thanh toán THÀNH CÔNG
                </button>
                <button type="submit" name="simulation_result" value="failed" class="btn btn-danger btn-lg btn-block">
                    <i class="fas fa-times-circle"></i> Giả lập Thanh toán THẤT BẠI
                </button>
                <button type="submit" name="simulation_result" value="cancel" class="btn btn-secondary btn-block">
                    Hủy giao dịch
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
