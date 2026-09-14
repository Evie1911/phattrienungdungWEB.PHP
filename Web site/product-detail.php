<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';

$product_id = $_GET['id'] ?? 0;
$product = get_product_by_id($product_id);

if (!$product) {
    $page_title = 'Không tìm thấy sản phẩm';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <div class="container" style="padding: 80px 20px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: var(--accent-yellow); margin-bottom: 20px;"></i>
        <h2>Sản phẩm không tồn tại hoặc đã ngừng kinh doanh</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">ID sản phẩm bạn yêu cầu không hợp lệ hoặc đã bị gỡ bỏ khỏi hệ thống.</p>
        <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary btn-lg">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
        </a>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = $product['name'];
require_once __DIR__ . '/includes/header.php';

$has_sale = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
$effective_price = $has_sale ? $product['sale_price'] : $product['price'];

// Sản phẩm liên quan cùng danh mục
$all_same_cat = get_all_products(['category' => $product['category_id']]);
$related_products = [];
foreach ($all_same_cat as $p) {
    if ($p['id'] !== $product['id']) {
        $related_products[] = $p;
    }
}
$related_products = array_slice($related_products, 0, 4);
?>

<div class="container">
    <!-- Breadcrumb -->
    <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">
        <a href="<?php echo BASE_URL; ?>/index.php">Trang chủ</a> / 
        <a href="<?php echo BASE_URL; ?>/products.php?category=<?php echo $product['category_id']; ?>">
            <?php 
                $cats = get_all_categories(); 
                echo sanitize($cats[$product['category_id']]['name'] ?? 'Sản phẩm'); 
            ?>
        </a> / 
        <span style="color: var(--text-primary); font-weight: 500;"><?php echo sanitize($product['name']); ?></span>
    </div>

    <!-- Main Detail Wrapper -->
    <div class="detail-wrapper">
        <div class="detail-gallery">
            <div class="main-image-box">
                <img src="<?php echo get_image_src($product['image'], $product['name'], 500, 500); ?>" alt="<?php echo sanitize($product['name']); ?>">
            </div>
        </div>

        <div class="detail-info">
            <h1 class="detail-title"><?php echo sanitize($product['name']); ?></h1>

            <div class="detail-meta">
                <span>Thương hiệu: <strong><?php echo sanitize($product['brand']); ?></strong></span>
                <span>|</span>
                <span>Tình trạng: 
                    <?php if ($product['stock'] > 0): ?>
                        <strong style="color: var(--accent-green);"><i class="fas fa-check-circle"></i> Còn hàng (<?php echo $product['stock']; ?>)</strong>
                    <?php else: ?>
                        <strong style="color: var(--accent-red);"><i class="fas fa-times-circle"></i> Hết hàng</strong>
                    <?php endif; ?>
                </span>
            </div>

            <div class="detail-price-box">
                <span class="current-price"><?php echo format_currency($effective_price); ?></span>
                <?php if ($has_sale): ?>
                    <span class="old-price" style="font-size: 1.2rem;"><?php echo format_currency($product['price']); ?></span>
                    <span class="badge badge-sale" style="font-size: 0.85rem;">Giảm <?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%</span>
                <?php endif; ?>
            </div>

            <p style="color: var(--text-secondary); margin-bottom: 24px; font-size: 0.95rem; line-height: 1.6;">
                <?php echo sanitize($product['description']); ?>
            </p>

            <form action="<?php echo BASE_URL; ?>/cart.php" method="GET">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <!-- Tùy chọn màu sắc -->
                <?php if (!empty($product['colors'])): ?>
                    <div class="option-group">
                        <label class="option-label"><i class="fas fa-palette text-primary"></i> Chọn màu sắc:</label>
                        <div class="option-chips">
                            <?php foreach ($product['colors'] as $idx => $color): ?>
                                <input type="radio" name="color" id="color_<?php echo $idx; ?>" value="<?php echo sanitize($color); ?>" class="chip-input" <?php echo $idx === 0 ? 'checked' : ''; ?>>
                                <label for="color_<?php echo $idx; ?>" class="chip-label"><?php echo sanitize($color); ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tùy chọn dung lượng -->
                <?php if (!empty($product['storage'])): ?>
                    <div class="option-group">
                        <label class="option-label"><i class="fas fa-hdd text-primary"></i> Chọn dung lượng / phiên bản:</label>
                        <div class="option-chips">
                            <?php foreach ($product['storage'] as $idx => $st): ?>
                                <input type="radio" name="storage" id="st_<?php echo $idx; ?>" value="<?php echo sanitize($st); ?>" class="chip-input" <?php echo $idx === 0 ? 'checked' : ''; ?>>
                                <label for="st_<?php echo $idx; ?>" class="chip-label"><?php echo sanitize($st); ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Số lượng -->
                <div class="option-group">
                    <label class="option-label">Số lượng mua:</label>
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn js-qty-minus">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input">
                        <button type="button" class="quantity-btn js-qty-plus">+</button>
                    </div>
                </div>

                <!-- Nút thao tác -->
                <div class="detail-actions">
                    <button type="submit" name="redirect_to" value="cart" class="btn btn-outline btn-lg" style="flex:1;" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                    <button type="submit" name="redirect_to" value="checkout" class="btn btn-primary btn-lg" style="flex:1;" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-bolt"></i> Mua Ngay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thông số kỹ thuật -->
    <?php if (!empty($product['specs'])): ?>
        <section style="background: #fff; padding: 24px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-bottom: 40px;">
            <h2 class="section-title"><i class="fas fa-list-alt text-primary"></i> Thông Số Kỹ Thuật Chi Tiết</h2>
            <table class="spec-table">
                <tbody>
                    <?php foreach ($product['specs'] as $key => $val): ?>
                        <tr>
                            <th><?php echo sanitize($key); ?></th>
                            <td><?php echo sanitize($val); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <!-- Sản phẩm liên quan -->
    <?php if (!empty($related_products)): ?>
        <section style="margin-bottom: 40px;">
            <div class="section-header">
                <h2 class="section-title">Sản Phẩm Cùng Danh Mục</h2>
            </div>
            <div class="product-grid">
                <?php foreach ($related_products as $product): ?>
                    <?php include __DIR__ . '/includes/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
