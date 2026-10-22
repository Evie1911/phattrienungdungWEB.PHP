<?php
session_start();
// Xóa sạch toàn bộ các biến lưu trong $_SESSION
$_SESSION = array();
// Xóa Cookie của Session trên trình duyệt (nếu có)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
//Hủy bỏ hoàn toàn Session trên Server
session_destroy();
header("Location:login.php");
exit();
?>
