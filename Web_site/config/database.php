<?php
class csdltmdt
{
    protected function connect()
    {
        $con = new mysqli("localhost", "root", "", "it_mobile");

        if ($con->connect_error) {
            echo 'Không kết nối được csdl';
            exit();
        } else {
            $con->query("set names 'utf8'");
            return $con;
        }
    }
}
