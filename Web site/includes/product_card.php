<?php
// Component Thẻ sản phẩm tái sử dụng - Tinh gọn (5 SP/dòng PC, 2 SP/dòng Mobile)
if (isset($product)):
    $has_sale = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    $current_price = $has_sale ? $product['sale_price'] : $product['price'];
    $discount_percent = $has_sale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
?>
<div class="product-card">
    <div class="product-badges">
        <?php if ($has_sale): ?>
            <span class="badge badge-sale">-<?php echo $discount_percent; ?>%</span>
        <?php endif; ?>
        <?php if (!empty($product['is_new'])): ?>
            <span class="badge badge-new">MỚI</span>
        <?php endif; ?>
    </div>

    <a href="<?php echo BASE_URL; ?>/product-detail.php?id=<?php echo $product['id']; ?>" class="product-thumb" title="<?php echo sanitize($product['name']); ?>">
        <img src="<?php echo get_image_src($product['image'], $product['name'], 200, 200); ?>" alt="<?php echo sanitize($product['name']); ?>">
    </a>

    <div class="product-info">
        <span class="product-brand"><?php echo sanitize($product['brand']); ?></span>
        <h3 class="product-name">
            <a href="<?php echo BASE_URL; ?>/product-detail.php?id=<?php echo $product['id']; ?>" style="color:inherit;">
                <?php echo sanitize($product['name']); ?>
            </a>
        </h3>

        <div class="product-price-box">
            <span class="current-price"><?php echo format_currency($current_price); ?></span>
            <?php if ($has_sale): ?>
                <span class="old-price"><?php echo format_currency($product['price']); ?></span>
            <?php endif; ?>
        </div>

        <div class="product-actions">
            <a href="<?php echo BASE_URL; ?>/product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm" title="Xem chi tiết">
                Chi tiết
            </a>
            <a href="<?php echo BASE_URL; ?>/cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm" title="Thêm vào giỏ">
                + Giỏ
            </a>
        </div>
    </div>
</div>
<?php endif; ?>
