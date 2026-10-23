<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';
require_once __DIR__ . '/includes/vnpay_helper.php';

if (!vnpay_verify_callback($_GET)) {
    error_log('VNPay return rejected: invalid signature or terminal code.');
    set_flash('error', 'Không xác minh được kết quả trả về từ VNPay. Vui lòng liên hệ cửa hàng để kiểm tra giao dịch.');
    redirect('index.php');
}

$txnRef = (string)($_GET['vnp_TxnRef'] ?? '');
$amount = filter_var($_GET['vnp_Amount'] ?? null, FILTER_VALIDATE_INT);
$order = $amount === false ? null : get_vnpay_order_by_txn_ref($txnRef, (int)$amount);
if (!$order) {
    error_log('VNPay return rejected: order reference or amount mismatch.');
    set_flash('error', 'Thông tin giao dịch VNPay không khớp với đơn hàng. Vui lòng liên hệ cửa hàng.');
    redirect('index.php');
}

if ($order['payment_status'] === 'paid') {
    set_flash('success', 'Thanh toán VNPay thành công.');
} elseif (($_GET['vnp_ResponseCode'] ?? '') === '00' && ($_GET['vnp_TransactionStatus'] ?? '') === '00') {
    set_flash('success', 'VNPay đã nhận giao dịch. Cửa hàng đang chờ thông báo xác nhận thanh toán.');
} else {
    set_flash('error', 'Thanh toán VNPay chưa thành công. Bạn có thể thử thanh toán lại từ trang đơn hàng.');
}
redirect('order-success.php?order_id=' . urlencode($order['id']));
