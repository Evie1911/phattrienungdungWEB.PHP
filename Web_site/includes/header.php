<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../data/data_helper.php';
require_once __DIR__ . '/image_helper.php';

$cart_totals = calculate_cart_totals();
$categories = get_all_categories();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitize($page_title) . ' - IT Mobile' : 'IT Mobile - Cửa Hàng Thiết Bị Di Động Uy Tín'; ?></title>
    <meta name="description" content="IT Mobile - Chuyên kinh doanh điện thoại, máy tính bảng và phụ kiện công nghệ chính hãng với mức giá ưu đãi nhất.">
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <script>window.BASE_URL = "<?php echo BASE_URL; ?>";</script>
</head>
<body>

    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-top">
                <!-- Brand Logo IT Mobile -->
                <a href="<?php echo BASE_URL; ?>/index.php" class="brand-logo">
                    <i class="fas fa-mobile-alt text-primary"></i> IT <span>Mobile</span>
                </a>

                <!-- Search Bar Desktop (Main Row) -->
                <form action="<?php echo BASE_URL; ?>/products.php" method="GET" class="search-form desktop-only">
                    <input type="text" name="keyword" placeholder="Nhập tên điện thoại, tablet, phụ kiện..." value="<?php echo sanitize($_GET['keyword'] ?? ''); ?>">
                    <button type="submit" title="Tìm kiếm"><i class="fas fa-search"></i></button>
                </form>

                <!-- Header Actions (Cart & Mobile Hamburger Button) -->
                <div class="header-actions">
                    <a href="<?php echo BASE_URL; ?>/cart.php" class="header-action-item cart-action-item" title="Giỏ hàng">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="desktop-only-inline">Giỏ hàng</span>
                        <span class="cart-count"><?php echo $cart_totals['total_count']; ?></span>
                    </a>

                    <!-- Mobile Menu Toggle Button -->
                    <button type="button" class="mobile-nav-toggle js-mobile-toggle" aria-label="Mở menu" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar (Row 2 on Mobile screens) -->
            <div class="mobile-search-row mobile-only">
                <form action="<?php echo BASE_URL; ?>/products.php" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm kiếm điện thoại, tablet, phụ kiện..." value="<?php echo sanitize($_GET['keyword'] ?? ''); ?>">
                    <button type="submit" title="Tìm kiếm"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <!-- Main Navigation (Dark Navbar #1e293b) -->
        <nav class="main-nav">
            <div class="container nav-container">
                <ul class="nav-list">
                    <li class="nav-item <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>/index.php"><i class="fas fa-home"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'products.php' && empty($_GET['category'])) ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>/products.php"><i class="fas fa-th-large"></i> Tất cả sản phẩm</a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="nav-item <?php echo ($current_page === 'products.php' && ($_GET['category'] ?? '') === $cat['id']) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/products.php?category=<?php echo $cat['id']; ?>">
                                <i class="fas <?php echo $cat['icon']; ?>"></i> <?php echo sanitize($cat['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item <?php echo $current_page === 'news.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>/news.php"><i class="fas fa-newspaper"></i> Tin tức & Bài viết</a>
                    </li>
                    <li class="nav-item <?php echo $current_page === 'about.php' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL; ?>/about.php"><i class="fas fa-info-circle"></i> Giới thiệu</a>
                    </li>
                </ul>

                <div class="nav-contact">
                    <i class="fas fa-headset"></i> Hotline: 1900.6868
                </div>
            </div>
        </nav>
    </header>

    <!-- Display Flash Alerts -->
    <?php include __DIR__ . '/alerts.php'; ?>

    <main class="main-content">
