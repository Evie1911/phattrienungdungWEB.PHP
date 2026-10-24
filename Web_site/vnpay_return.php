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

// ReturnUrl is signed by VNPay. Record it as well as IPN so cancelled
// transactions are persisted even when VNPay does not send a separate IPN.
$result = record_vnpay_result($_GET);
if (empty($result['valid'])) {
    error_log('VNPay return could not record transaction result.');
    set_flash('error', 'Không thể cập nhật kết quả giao dịch. Vui lòng liên hệ cửa hàng kiểm tra.');
    redirect('order-success.php?order_id=' . urlencode($order['id']));
}

if (!empty($result['paid'])) {
    set_flash('success', 'Thanh toán VNPay thành công.');
} elseif (($_GET['vnp_ResponseCode'] ?? '') === '24') {
    set_flash('error', 'Bạn đã hủy giao dịch VNPay. Đơn hàng đã được hủy.');
} else {
    set_flash('error', 'Giao dịch VNPay thất bại. Đơn hàng chưa được thanh toán.');
}
redirect('order-success.php?order_id=' . urlencode($order['id']));
