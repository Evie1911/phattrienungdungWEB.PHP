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
    // Phương thức băm mật khẩu
    public function bammatkhau($pass)
    {
        $salt = '1@Fd';
        return md5($pass . $salt);
    }
}
