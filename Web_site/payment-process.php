<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';
require_once __DIR__ . '/includes/vnpay_helper.php';

$orderCode = trim((string)($_GET['order_id'] ?? ''));
$order = $orderCode !== '' ? get_order_by_id($orderCode) : null;

if (!$order || $order['payment_method'] !== 'online') {
    set_flash('error', 'Không tìm thấy đơn hàng cần thanh toán VNPay.');
    redirect('index.php');
}
if ($order['payment_status'] === 'paid') {
    set_flash('success', 'Đơn hàng này đã được thanh toán.');
    redirect('order-success.php?order_id=' . urlencode($orderCode));
}
if ($order['order_status'] === 'Đã hủy') {
    set_flash('error', 'Đơn hàng đã bị hủy nên không thể thanh toán.');
    redirect('order-success.php?order_id=' . urlencode($orderCode));
}

$payment = prepare_vnpay_payment($orderCode);
if (!$payment) {
    set_flash('error', 'Không thể khởi tạo thanh toán VNPay cho đơn hàng này.');
    redirect('order-success.php?order_id=' . urlencode($orderCode));
}

try {
    $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
    $now = new DateTimeImmutable('now', $timezone);
    $ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) $ipAddress = '127.0.0.1';

    $params = [
        'vnp_Version' => '2.1.0',
        'vnp_Command' => 'pay',
        'vnp_TmnCode' => VNPAY_TMN_CODE,
        'vnp_Amount' => $payment['amount'] * 100,
        'vnp_CurrCode' => 'VND',
        'vnp_TxnRef' => $payment['order_code'],
        'vnp_OrderInfo' => 'Thanh toan don hang ' . $payment['order_code'],
        'vnp_OrderType' => 'other',
        'vnp_Locale' => 'vn',
        'vnp_ReturnUrl' => VNPAY_RETURN_URL,
        'vnp_IpAddr' => $ipAddress,
        'vnp_CreateDate' => $now->format('YmdHis'),
        'vnp_ExpireDate' => $now->modify('+15 minutes')->format('YmdHis'),
    ];

    redirect(vnpay_create_payment_url($params));
} catch (Throwable $e) {
    error_log('Create VNPay URL failed: ' . $e->getMessage());
    set_flash('error', 'Cấu hình VNPay chưa đầy đủ hoặc không hợp lệ.');
    redirect('order-success.php?order_id=' . urlencode($orderCode));
}
