<?php
require_once __DIR__ . '/../../config/database.php';

class xuly extends csdltmdt
{
    public function xuatdulieu($sql)
    {
        $arr = array();
        $link = $this->connect();

        if ($link) {
            $result = $link->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
        }
        return $arr;
    }

    public function themxoasua($sql)
    {
        $link = $this->connect();
        if ($link && $link->query($sql)) {
            // Nếu là câu lệnh INSERT, trả về ID vừa được tạo
            if (stripos(trim($sql), 'INSERT') === 0) {
                return $link->insert_id;
            }
            return true;
        }
        return false;
    }

    public function uploadfile($name, $tmp_name, $folder)
    {
        $destination = $folder . '/' . $name;
        if (move_uploaded_file($tmp_name, $destination)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function mylogin($email, $pass)
    {
        $link = $this->connect();
        $email = trim($email);
        $stmt = $link->prepare(
            "SELECT id, user_code, full_name, email, password, role, status,avatar,phone,birthday FROM users WHERE email = ? LIMIT 1"
        );
        //gửi email lên csdl
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        //kt Sai email hoặc mật khẩu
        if (!$row || !password_verify($pass, $row['password'])) {
            $link->close();
            return 0;
        }
        // Tài khoản bị khóa
        if ($row['status'] !== 'active') {
            $link->close();
            return -2;
        }
        // Không phải admin
        if ($row['role'] !== 'admin') {
            $link->close();
            return -1;
        }
        // Đăng nhập thành công
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);

        $_SESSION['id']        = $row['id'];
        $_SESSION['user_code'] = $row['user_code'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['email']     = $row['email'];
        $_SESSION['role']      = $row['role'];
        $_SESSION['status']    = $row['status'];
        $_SESSION['avatar']    = $row['avatar'];
        $_SESSION['phone']    = $row['phone'];
        $_SESSION['birthday']    = $row['birthday'];

        $link->close();
        header('location:index.php');
        exit();
    }
    public function confirmlogin($id, $user_code, $email, $role)
    {
        $link = $this->connect();

        $stmt = $link->prepare(
            "SELECT id FROM users WHERE id = ? AND user_code = ? AND email = ? AND status = 'active' AND role = ? LIMIT 1"
        );
        // 'i' cho id (int), 's' cho user_code (string), 's' cho email (string), 's' cho role (string) -> 'isss'
        $stmt->bind_param('isss', $id, $user_code, $email, $role);
        $stmt->execute();
        $ok = $stmt->get_result()->num_rows === 1;
        $stmt->close();
        $link->close();

        if (!$ok) {
            header('location: login.php');
            exit();
        }
    }
}
