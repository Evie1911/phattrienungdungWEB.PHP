<?php
// Đoạn thay thế giúp BASE_URL luôn chuẩn xác ở mọi cấp thư mục con
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Tính đường dẫn tương đối từ thư mục htdocs đến thư mục dự án hiện tại
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$appRoot = str_replace('\\', '/', __DIR__);
$baseFolder = str_replace($docRoot, '', $appRoot);

define('BASE_URL', $protocol . '://' . $host . $baseFolder);
define('ROOT_PATH', __DIR__);
