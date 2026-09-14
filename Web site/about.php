<?php
$page_title = 'Giới Thiệu Về IT Mobile';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 960px;">
    <div style="background: #fff; padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <span class="badge badge-new" style="font-size:0.85rem; padding:6px 14px; margin-bottom:10px;">HỆ THỐNG BÁN LẺ ĐIỆN THOẠI UY TÍN</span>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--secondary-color);">VỀ CHÚNG TÔI - IT MOBILE</h1>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 10px auto 0; font-size: 1.05rem;">
                Tiên phong mang tới người dùng những sản phẩm di động, máy tính bảng và phụ kiện công nghệ chất lượng nhất.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; align-items: center;">
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--primary-color); margin-bottom: 12px;">Sứ Mệnh & Tầm Nhìn</h2>
                <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 15px;">
                    Được thành lập từ niềm đam mê công nghệ, <strong>IT Mobile</strong> luôn nỗ lực trở thành điểm đến tin cậy của cộng đồng yêu thích các thiết bị di động chính hãng tại Việt Nam.
                </p>
                <p style="color: var(--text-secondary); line-height: 1.7;">
                    Chúng tôi không chỉ cung cấp sản phẩm với mức giá tốt nhất mà còn chú trọng mang tới trải nghiệm mua sắm tuyệt vời cùng dịch vụ chăm sóc khách hàng chuyên nghiệp.
                </p>
            </div>
            <div style="background: var(--primary-light); padding: 30px; border-radius: var(--radius-md); text-align: center;">
                <i class="fas fa-award" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 700;">Cam Kết 100% Chính Hãng</h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 8px;">Mọi sản phẩm bán ra đều có nguồn gốc xuất xứ rõ ràng và chế độ bảo hành chính hãng chuẩn 12 tháng.</p>
            </div>
        </div>

        <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--secondary-color); margin-bottom: 20px; text-align: center;">Cam Kết & Chính Sách Nổi Bật</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div style="padding: 20px; border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                <i class="fas fa-sync-alt" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 12px;"></i>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">1 Đổi 1 Trong 30 Ngày</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Áp dụng lỗi từ nhà sản xuất với thủ tục cực kỳ nhanh chóng.</p>
            </div>

            <div style="padding: 20px; border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                <i class="fas fa-shipping-fast" style="font-size: 2rem; color: var(--accent-green); margin-bottom: 12px;"></i>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Giao Hàng Siêu Tốc</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Giao hàng hỏa tốc 2h tại Hà Nội & TP.HCM, miễn phí giao toàn quốc.</p>
            </div>

            <div style="padding: 20px; border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                <i class="fas fa-headset" style="font-size: 2rem; color: var(--accent-yellow); margin-bottom: 12px;"></i>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Hỗ Trợ 24/7</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Đội ngũ tư vấn viên am hiểu kỹ thuật sẵn sàng hỗ trợ khách hàng mọi lúc.</p>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-cart"></i> Mua sắm ngay hôm nay
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
