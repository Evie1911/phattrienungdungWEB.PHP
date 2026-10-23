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
    set_flash("error", "Kh\u{00F4}ng th\u{1EC3} c\u{1EAD}p nh\u{1EAD}t k\u{1EBF}t qu\u{1EA3} giao d\u{1ECB}ch. Vui l\u{00F2}ng li\u{00EAn h\u{1EC7} c\u{1EED}a h\u{00E0}ng ki\u{1EC3}m tra.");
    redirect('order-success.php?order_id=' . urlencode($order['id']));
}

if (!empty($result['paid'])) {
    set_flash("success", "Thanh to\u{00E1}n VNPay th\u{00E0}nh c\u{00F4}ng.");
} elseif (($_GET['vnp_ResponseCode'] ?? '') === '24') {
    set_flash("error", "B\u{1EA1}n \u{0111}\u{00E3} h\u{1EE7}y giao d\u{1ECB}ch VNPay. \u{0110}\u{01A1}n h\u{00E0}ng \u{0111}\u{00E3} \u{0111}\u{01B0}\u{1EE3}c h\u{1EE7}y.");
} else {
    set_flash("error", "Giao d\u{1ECB}ch VNPay th\u{1EA5}t b\u{1EA1}i. \u{0110}\u{01A1}n h\u{00E0}ng ch\u{01B0}a \u{0111}\u{01B0}\u{1EE3}c thanh to\u{00E1}n.");
}
redirect('order-success.php?order_id=' . urlencode($order['id']));
