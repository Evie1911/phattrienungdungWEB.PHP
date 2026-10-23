<?php
require_once __DIR__ . '/../config.php';

/** Tạo chuỗi tham số đã sắp xếp và mã hóa đúng quy ước ký checksum của VNPay. */
function vnpay_build_hash_data(array $params): string
{
    unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);
    ksort($params, SORT_STRING);
    $parts = [];
    foreach ($params as $key => $value) {
        if ($value === null || $value === '') continue;
        $parts[] = urlencode((string)$key) . '=' . urlencode((string)$value);
    }
    return implode('&', $parts);
}

/** Kiểm tra checksum HMAC-SHA512 và mã terminal trong dữ liệu callback VNPay. */
function vnpay_verify_callback(array $params): bool
{
    if (VNPAY_HASH_SECRET === '' || VNPAY_TMN_CODE === '' || empty($params['vnp_SecureHash']) ||
        !isset($params['vnp_TmnCode']) || !hash_equals(VNPAY_TMN_CODE, (string)$params['vnp_TmnCode'])) {
        return false;
    }
    $expected = hash_hmac('sha512', vnpay_build_hash_data($params), VNPAY_HASH_SECRET);
    return hash_equals(strtolower($expected), strtolower((string)$params['vnp_SecureHash']));
}

/** Trả URL khởi tạo giao dịch đã ký để chuyển hướng khách sang VNPay. */
function vnpay_create_payment_url(array $params): string
{
    if (VNPAY_URL === '' || VNPAY_TMN_CODE === '' || VNPAY_HASH_SECRET === '' || VNPAY_RETURN_URL === '') {
        throw new RuntimeException('Thiếu cấu hình VNPay trong file .env.');
    }

    ksort($params, SORT_STRING);
    $query = http_build_query($params, '', '&', PHP_QUERY_RFC1738);
    $secureHash = hash_hmac('sha512', $query, VNPAY_HASH_SECRET);
    return VNPAY_URL . '?' . $query . '&vnp_SecureHash=' . $secureHash;
}
