<?php
class csdltmdt
{
    protected function connect(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $env = static function (string $key, string $default): string {
            if (function_exists('project_env')) {
                return project_env($key, $default);
            }
            $value = getenv($key);
            return $value === false || $value === '' ? $default : $value;
        };

        $host = $env('DB_HOST', 'localhost');
        $username = $env('DB_USERNAME', 'root');
        $password = $env('DB_PASSWORD', '');
        $database = $env('DB_DATABASE', 'it_mobile');
        $port = (int)$env('DB_PORT', '3306');

        $con = new mysqli($host, $username, $password, $database, $port);
        $con->set_charset('utf8mb4');
        return $con;
    }
}
