<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';
require_once __DIR__ . '/includes/vnpay_helper.php';

header('Content-Type: application/json; charset=utf-8');

if (!vnpay_verify_callback($_GET)) {
    http_response_code(200);
    echo json_encode(['RspCode'=>'97', 'Message'=>'Invalid signature']);
    exit;
}

$result = record_vnpay_result($_GET);
if (!$result['valid']) {
    http_response_code(200);
    $messages = ['01'=>'Order not found', '04'=>'Invalid amount', '97'=>'Invalid signature', '99'=>'System error'];
    echo json_encode(['RspCode'=>$result['rsp_code'] ?? '99', 'Message'=>$messages[$result['rsp_code'] ?? '99'] ?? 'System error']);
    exit;
}

http_response_code(200);
echo json_encode([
    'RspCode'=>$result['rsp_code'] ?? '00',
    'Message'=>($result['rsp_code'] ?? '00') === '02' ? 'Transaction already confirmed' : 'Confirm Success'
]);
