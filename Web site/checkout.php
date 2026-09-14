<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/data_helper.php';

$cart_items = get_cart_items();
$cart_totals = calculate_cart_totals();

if (empty($cart_items)) {
    set_flash('error', 'Giỏ hàng của bạn đang trống! Vui lòng chọn sản phẩm trước khi thanh toán.');
    redirect('products.php');
}

$errors = [];
$form_data = [
    'name' => '',
    'phone' => '',
    'email' => '',
    'address' => '',
    'note' => '',
    'payment_method' => 'cod'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token()) {
        $form_data['name'] = trim($_POST['name'] ?? '');
        $form_data['phone'] = trim($_POST['phone'] ?? '');
        $form_data['email'] = trim($_POST['email'] ?? '');
        $form_data['address'] = trim($_POST['address'] ?? '');
        $form_data['note'] = trim($_POST['note'] ?? '');
        $form_data['payment_method'] = $_POST['payment_method'] ?? 'cod';

        // Kiểm tra hợp lệ phía server
        if (empty($form_data['name'])) {
            $errors['name'] = 'Vui lòng nhập họ và tên người nhận.';
        }
        if (empty($form_data['phone'])) {
            $errors['phone'] = 'Vui lòng nhập số điện thoại giao hàng.';
        }
        if (empty($form_data['email']) || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Vui lòng nhập địa chỉ email hợp lệ.';
        }
        if (empty($form_data['address'])) {
            $errors['address'] = 'Vui lòng nhập địa chỉ giao hàng cụ thể.';
        }

        if (empty($errors)) {
            $order = create_order_from_cart($form_data, $form_data['payment_method']);

            if ($order) {
                if ($form_data['payment_method'] === 'online') {
                    redirect('payment-process.php?order_id=' . urlencode($order['id']));
                } else {
                    set_flash('success', 'Đặt hàng thành công! Đơn hàng của bạn đã được tiếp nhận.');
                    redirect('order-success.php?order_id=' . urlencode($order['id']));
                }
            } else {
                set_flash('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
            }
        }
    }
}

$page_title = 'Đặt Hàng Online';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; margin-bottom: 40px;">
        <!-- Form Thông tin giao hàng -->
        <div style="background: #fff; padding: 24px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
            <h2 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">
                <i class="fas fa-truck text-primary"></i> Thông Tin Giao Hàng
            </h2>

            <form action="<?php echo BASE_URL; ?>/checkout.php" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Họ và tên người nhận <span style="color:var(--accent-red);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Ví dụ: Nguyễn Văn An" value="<?php echo sanitize($form_data['name']); ?>" required>
                    <?php if (isset($errors['name'])): ?><small style="color:var(--accent-red);"><?php echo $errors['name']; ?></small><?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Số điện thoại <span style="color:var(--accent-red);">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="0901234567" value="<?php echo sanitize($form_data['phone']); ?>" required>
                        <?php if (isset($errors['phone'])): ?><small style="color:var(--accent-red);"><?php echo $errors['phone']; ?></small><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email thông báo <span style="color:var(--accent-red);">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" value="<?php echo sanitize($form_data['email']); ?>" required>
                        <?php if (isset($errors['email'])): ?><small style="color:var(--accent-red);"><?php echo $errors['email']; ?></small><?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ nhận hàng chi tiết <span style="color:var(--accent-red);">*</span></label>
                    <input type="text" name="address" class="form-control" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP" value="<?php echo sanitize($form_data['address']); ?>" required>
                    <?php if (isset($errors['address'])): ?><small style="color:var(--accent-red);"><?php echo $errors['address']; ?></small><?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Ghi chú giao hàng (nếu có)</label>
                    <textarea name="note" class="form-control" placeholder="Ví dụ: Giao ngoài giờ hành chính..."><?php echo sanitize($form_data['note']); ?></textarea>
                </div>

                <h3 style="font-size: 1.1rem; font-weight: 700; margin: 24px 0 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fas fa-credit-card text-primary"></i> Phương Thức Thanh Toán
                </h3>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 2px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; margin-bottom: 10px;">
                        <input type="radio" name="payment_method" value="cod" <?php echo $form_data['payment_method'] === 'cod' ? 'checked' : ''; ?>>
                        <div>
                            <strong><i class="fas fa-hand-holding-usd text-primary"></i> Thanh toán khi nhận hàng (COD)</strong>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">Thanh toán tiền mặt cho nhân viên giao hàng sau khi kiểm tra thiết bị.</div>
                        </div>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 2px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer;">
                        <input type="radio" name="payment_method" value="online" <?php echo $form_data['payment_method'] === 'online' ? 'checked' : ''; ?>>
                        <div>
                            <strong><i class="fas fa-globe text-primary"></i> Thanh toán trực tuyến (Mô phỏng Sandbox)</strong>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">Mô phỏng thanh toán qua Thẻ ATM, VNPay hoặc QR Code.</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 24px;">
                    <i class="fas fa-check-circle"></i> XÁC NHẬN ĐẶT HÀNG
                </button>
            </form>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div>
            <div class="cart-summary-box">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">
                    Sản Phẩm Đã Chọn (<?php echo $cart_totals['total_count']; ?>)
                </h3>

                <div style="max-height: 320px; overflow-y: auto; margin-bottom: 16px;">
                    <?php foreach ($cart_items as $item): ?>
                        <div style="display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                            <img src="<?php echo get_image_src($item['image'], $item['name'], 50, 50); ?>" style="width:50px; height:50px; object-fit:contain;" alt="">
                            <div style="flex:1;">
                                <div style="font-weight: 600; font-size:0.9rem;"><?php echo sanitize($item['name']); ?></div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                    <?php echo sanitize($item['color']); ?> | <?php echo sanitize($item['storage']); ?> x <?php echo $item['quantity']; ?>
                                </div>
                                <div style="font-size:0.9rem; color:var(--accent-red); font-weight:700;">
                                    <?php echo format_currency($item['price'] * $item['quantity']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-row total">
                    <span>TỔNG CỘNG:</span>
                    <span><?php echo format_currency($cart_totals['total_amount']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
