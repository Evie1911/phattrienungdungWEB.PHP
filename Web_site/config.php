<?php
// Khởi tạo PHP Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chế độ mô phỏng Session
define('DEMO_MODE', true);

// Định nghĩa BASE_URL theo APP_URL nếu cấu hình; nếu không, dùng host hiện tại.
$configuredBaseUrl = project_env('APP_URL');
$forwardedProtocol = strtolower(trim(explode(',', (string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));
$protocol = in_array($forwardedProtocol, ['http', 'https'], true)
    ? $forwardedProtocol
    : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = rtrim($scriptDir, '/');

define('BASE_URL', rtrim($configuredBaseUrl !== '' ? $configuredBaseUrl : ($protocol . '://' . $host . $basePath), '/'));
define('ROOT_PATH', __DIR__);

// Đọc giá trị trong môi trường trước, sau đó mới đọc file .env ở thư mục gốc dự án.
// Không ghi đè biến môi trường thật bằng giá trị trong file .env.
function project_env(string $name, string $default = ''): string
{
    $value = getenv($name);
    if ($value !== false && $value !== '') {
        return trim($value);
    }

    static $envValues = null;
    if ($envValues === null) {
        $envValues = [];
        // Trên hosting, .env nằm trong document root và bị chặn truy cập bởi .htaccess.
        // Ở môi trường local, dùng file .env tại thư mục gốc repository làm phương án dự phòng.
        $envFiles = [
            __DIR__ . DIRECTORY_SEPARATOR . '.env',
            dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
        ];
        foreach ($envFiles as $envFile) {
            if (!is_readable($envFile)) {
                continue;
            }
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$key, $envValue] = explode('=', $line, 2);
                $key = trim($key);
                $envValue = trim($envValue);
                if (strlen($envValue) >= 2 && (($envValue[0] === '"' && str_ends_with($envValue, '"')) || ($envValue[0] === "'" && str_ends_with($envValue, "'")))) {
                    $envValue = substr($envValue, 1, -1);
                }
                if ($key !== '') {
                    $envValues[$key] = $envValue;
                }
            }
            break;
        }
    }

    return $envValues[$name] ?? $default;
}

// VNPay Sandbox: các mã kết nối phải lấy từ file .env, không commit lên mã nguồn.
define('VNPAY_URL', project_env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'));
define('VNPAY_TMN_CODE', project_env('VNPAY_TMN_CODE'));
define('VNPAY_HASH_SECRET', project_env('VNPAY_HASH_SECRET'));
define('VNPAY_RETURN_URL', project_env('VNPAY_RETURN_URL', BASE_URL . '/vnpay_return.html'));
define('VNPAY_IPN_URL', project_env('VNPAY_IPN_URL', ''));

// Hàm định dạng tiền tệ VNĐ
function format_currency($amount) {
    return number_format((float)$amount, 0, ',', '.') . ' ₫';
}

// Hàm làm sạch dữ liệu (XSS)
function sanitize($data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

// Chuyển tên thành slug ASCII để tạo URL sản phẩm dạng ten-san-pham-123.html.
function slugify($value) {
    $value = trim((string)$value);
    if (function_exists('iconv')) {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($ascii !== false) {
            $value = $ascii;
        }
    }
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim((string)$value, '-') ?: 'san-pham';
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
