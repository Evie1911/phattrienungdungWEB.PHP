<?php
// Khởi tạo PHP Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chế độ mô phỏng Session
define('DEMO_MODE', true);

// Định nghĩa BASE_URL và ROOT_PATH cho dự án
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = rtrim($scriptDir, '/');

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('ROOT_PATH', __DIR__);

// Hàm định dạng tiền tệ VNĐ
function format_currency($amount) {
    return number_format((float)$amount, 0, ',', '.') . ' ₫';
}

// Hàm làm sạch dữ liệu (XSS)
function sanitize($data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

// Hàm chuyển hướng trang
function redirect($path) {
    if (strpos($path, 'http') === 0) {
        header("Location: " . $path);
    } else {
        header("Location: " . BASE_URL . '/' . ltrim($path, '/'));
    }
    exit;
}

// Tạo mã CSRF bảo mật đơn giản
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}

function verify_csrf_token() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || empty($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
        return false;
    }
    return true;
}

// Quản lý thông báo Flash
function set_flash($type, $message) {
    $_SESSION['flash'][$type][] = $message;
}

function get_flash() {
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}
