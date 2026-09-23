<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Quản lý sản phẩm'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/stylemain.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css' rel='stylesheet'>
    <!-- Thư viện Icon Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- Thư viện SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- thư viện biểu đồ -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>

<body>
