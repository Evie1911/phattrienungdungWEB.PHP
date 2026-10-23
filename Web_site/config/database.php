<?php
class csdltmdt
{
    protected function connect(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $env = static function (string $key): string {
            if (function_exists('project_env')) {
                return project_env($key);
            }
            $value = getenv($key);
            return $value === false ? '' : trim($value);
        };

        $host = $env('DB_HOST');
        $username = $env('DB_USERNAME');
        $password = $env('DB_PASSWORD');
        $database = $env('DB_DATABASE');
        $portValue = $env('DB_PORT');

        if ($host === '' || $username === '' || $database === '') {
            throw new RuntimeException('Thiếu cấu hình database. Hãy cấu hình DB_HOST, DB_USERNAME và DB_DATABASE trong .env. DB_PASSWORD có thể để trống khi MySQL local không đặt mật khẩu.');
        }

        $port = $portValue === '' ? 3306 : (int)$portValue;

        $con = new mysqli($host, $username, $password, $database, $port);
        $con->set_charset('utf8mb4');
        return $con;
    }
}
