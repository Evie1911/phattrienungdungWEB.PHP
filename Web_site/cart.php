<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';

$action = $_REQUEST['action'] ?? '';

if ($action === 'add') {
    $product_id = $_REQUEST['id'] ?? 0;
    $quantity = $_REQUEST['quantity'] ?? 1;
    $color = $_REQUEST['color'] ?? '';
    $storage = $_REQUEST['storage'] ?? '';
    $redirect_target = $_REQUEST['redirect_to'] ?? 'cart';

    if (add_to_cart($product_id, $quantity, $color, $storage)) {
        set_flash('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    } else {
        set_flash('error', 'Không thể thêm sản phẩm vào giỏ hàng.');
    }

    if ($redirect_target === 'checkout') {
        redirect('checkout.php');
    } else {
        redirect('cart.php');
    }
} elseif ($action === 'update_ajax' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $item_key = $_POST['item_key'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);
    if (!empty($item_key)) {
        if ($quantity > 0) {
            update_cart_quantity($item_key, $quantity);
        } else {
            remove_from_cart($item_key);
        }
    }
    $cart_totals = calculate_cart_totals();
    echo json_encode([
        'status' => 'success',
        'cart_totals' => $cart_totals,
        'formatted_total' => format_currency($cart_totals['total_amount']),
        'total_count' => $cart_totals['total_count']
    ]);
    exit;
} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token()) {
        $quantities = $_POST['quantities'] ?? [];
        foreach ($quantities as $item_key => $qty) {
            update_cart_quantity($item_key, $qty);
        }
        set_flash('success', 'Cập nhật số lượng giỏ hàng thành công.');
    }
    redirect('cart.php');
} elseif ($action === 'remove') {
    $item_key = $_GET['key'] ?? '';
    if (!empty($item_key)) {
        remove_from_cart($item_key);
        set_flash('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
    redirect('cart.php');
} elseif ($action === 'clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token()) {
        clear_cart();
        set_flash('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
    redirect('cart.php');
}

$page_title = 'Giỏ Hàng';
require_once __DIR__ . '/includes/header.php';

$cart_items = get_cart_items();
$cart_totals = calculate_cart_totals();
?>

<div class="container">
    <div class="section-header">
        <h1 class="section-title"><i class="fas fa-shopping-bag"></i> Giỏ hàng của bạn</h1>
        <?php if (!empty($cart_items)): ?>
            <form action="<?php echo BASE_URL; ?>/cart.php" method="POST" class="js-confirm-delete" data-confirm-message="Xóa toàn bộ giỏ hàng?">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="clear">
                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--accent-red);">
                    <i class="fas fa-trash-alt"></i> Xóa tất cả
                </button>
            </form>
        <?php endif; ?>
    </div>

    <?php if (!empty($cart_items)): ?>
        <div class="cart-layout">
            <div>
                <form action="<?php echo BASE_URL; ?>/cart.php" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="update">

                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th style="text-align: center;">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $key => $item): 
                                    $product = get_product_by_id($item['product_id']);
                                    $unit_price = ($product && !empty($product['sale_price'])) ? $product['sale_price'] : ($product ? $product['price'] : $item['price']);
                                    $subtotal = $unit_price * $item['quantity'];
                                ?>
                                    <tr class="cart-item-row" data-item-key="<?php echo $key; ?>" data-unit-price="<?php echo $unit_price; ?>">
                                        <td>
                                            <div class="cart-product-info">
                                                <img src="<?php echo get_image_src($item['image'], $item['name'], 60, 60); ?>" alt="<?php echo sanitize($item['name']); ?>">
                                                <div>
                                                    <a href="<?php echo BASE_URL; ?>/product-detail.php?id=<?php echo $item['product_id']; ?>" style="font-weight: 600; color: var(--text-primary);">
                                                        <?php echo sanitize($item['name']); ?>
                                                    </a>
                                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                                                        <?php if (!empty($item['color'])): ?><span>Màu: <strong><?php echo sanitize($item['color']); ?></strong></span><?php endif; ?>
                                                        <?php if (!empty($item['storage'])): ?><span style="margin-left: 8px;">Dung lượng: <strong><?php echo sanitize($item['storage']); ?></strong></span><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong class="unit-price-display"><?php echo format_currency($unit_price); ?></strong></td>
                                        <td>
                                            <div class="quantity-control">
                                                <button type="button" class="quantity-btn js-qty-minus" aria-label="Giảm số lượng">-</button>
                                                <input type="number" name="quantities[<?php echo $key; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $product['stock'] ?? 99; ?>" class="quantity-input js-cart-qty-input">
                                                <button type="button" class="quantity-btn js-qty-plus" aria-label="Tăng số lượng">+</button>
                                            </div>
                                        </td>
                                        <td><strong class="current-price js-item-subtotal"><?php echo format_currency($subtotal); ?></strong></td>
                                        <td style="text-align: center;">
                                            <a href="<?php echo BASE_URL; ?>/cart.php?action=remove&key=<?php echo urlencode($key); ?>" class="btn btn-secondary btn-sm js-confirm-delete" data-confirm-message="Xóa sản phẩm này khỏi giỏ hàng?" title="Xóa">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Tiếp tục chọn sản phẩm
                        </a>
                        <button type="submit" class="btn btn-outline">
                            <i class="fas fa-sync-alt"></i> Cập nhật giỏ hàng
                        </button>
                    </div>
                </form>
            </div>

            <div>
                <div class="cart-summary-box">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                        Tóm tắt đơn hàng
                    </h3>

                    <div class="summary-row">
                        <span>Số lượng sản phẩm:</span>
                        <strong class="js-cart-total-count"><?php echo $cart_totals['total_count']; ?> thiết bị</strong>
                    </div>
                    <div class="summary-row">
                        <span>Phí giao hàng:</span>
                        <strong style="color: var(--accent-green);">Miễn phí</strong>
                    </div>

                    <div class="summary-row total">
                        <span>TỔNG TIỀN:</span>
                        <span class="js-cart-total-amount" style="color: var(--accent-red); font-weight: 800; font-size: 1.2rem;"><?php echo format_currency($cart_totals['total_amount']); ?></span>
                    </div>

                    <a href="<?php echo BASE_URL; ?>/checkout.php" class="btn btn-primary btn-lg btn-block" style="margin-top: 16px;">
                        Đặt hàng ngay <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div style="background: #fff; padding: 60px 20px; text-align: center; border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-bottom: 40px;">
            <i class="fas fa-shopping-bag" style="font-size: 3.5rem; color: var(--text-muted); margin-bottom: 16px;"></i>
            <h2 style="font-size: 1.3rem; margin-bottom: 8px;">Giỏ hàng của bạn đang trống</h2>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Hãy chọn các thiết bị di động chính hãng tại IT Mobile nhé.</p>
            <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary btn-lg">
                Khám phá sản phẩm
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
