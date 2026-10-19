<?php
require_once '../config.php';
include("xyly/xuly.php");
// Khởi tạo đối tượng xử lý AJAX
$db = new xuly();
// Thiết lập header trả về kiểu JSON
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['value'])) {
    $action = $_POST['action'];
    $val    = trim($_POST['value']);

    if ($val === '') {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không được để trống!']);
        exit;
    }

    $sql = "";
    switch ($action) {
        case 'supplier': // Thêm nhà cung cấp
            $sql = "INSERT INTO suppliers (name) VALUES ('$val')";
            break;
        case 'category': // Thêm danh mục
            $code = 'DM-' . time();
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val), '-'));
            $sql = "INSERT INTO categories (category_code, name, slug, status) VALUES ('$code', '$val', '$slug', 1)";
            break;
        case 'brand': // Thêm thương hiệu / tình trạng
            $sql = "INSERT INTO brands (name) VALUES ('$val')";
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ!']);
            exit;
    }

    // Thực thi và nhận trực tiếp ID trả về từ hàm themxoasua
    $newId = $db->themxoasua($sql);
    if ($newId) {
        echo json_encode([
            'status' => 'success',
            'id' => is_numeric($newId) ? $newId : 0,
            'name' => $val
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu hoặc tên đã tồn tại!']);
    }
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ!']);
    exit;
}
