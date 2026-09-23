<?php
require_once '../../config.php';
// Cấu hình tiêu đề động cho trang
$pageTitle = "Quản lý thanh toán";

//Các thành phần giao diện
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>


<main class="main-content">
    <div class="content-card">
        <div class="stats-container">
            <!-- Tổng Doanh Thu -->
            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Tổng Doanh Thu</div>
                <div class="stat-value stat-value-default" style="color: #0f172a;">1.500.000 đ</div>
                <div class="stat-desc stat-desc-success" style="color: #16a34a;"><i class="fa-solid fa-arrow-up"></i> +12.5% so với tháng trước</div>
            </div>

            <!-- Thành Công -->
            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Thành Công</div>
                <div class="stat-value stat-value-success" style="color: #16a34a;">1</div>
                <div class="stat-desc stat-desc-muted" style="color: #94a3b8;">Đã quyết toán xong</div>
            </div>

            <!-- Chờ Xác Nhận / Duyệt -->
            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Chờ Xác Nhận / Duyệt</div>
                <div class="stat-value stat-value-warning" style="color: #d97706;">1</div>
                <div class="stat-desc stat-desc-warning" style="color: #d97706;"><i class="fa-regular fa-clock"></i> Cần kiểm tra ngân hàng</div>
            </div>

            <!-- Thanh Toán Khi Nhận Hàng (COD) -->
            <div class="stat-card">
                <div class="stat-title" style="color: #64748b;">Thanh Toán Khi Nhận Hàng (COD)</div>
                <div class="stat-value stat-value-info" style="color: #0284c7;">2</div>
                <div class="stat-desc stat-desc-info" style="color: #0284c7;"><i class="fa-solid fa-truck-fast"></i> Chờ Shipper thu tiền</div>
            </div>
        </div>

        <!-- Tiêu đề trang & Nút hành động -->
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Thanh Toán</h1>
                <p>Theo dõi, kiểm tra lịch sử giao dịch và trạng thái thanh toán đơn hàng</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/page/thanhtoan/themthanhtoan.php" class="btn-primary" title="Tạo giao dịch thủ công">
                <i class="fa-solid fa-plus"></i> <span>Tạo giao dịch mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-box">
                <input type="text" placeholder="Tìm theo mã giao dịch, mã đơn hàng, tên khách hàng...">
            </div>
            <select class="filter-select">
                <option value="">-- Trạng thái thanh toán --</option>
                <option value="paid">Đã thanh toán / Thành công</option>
                <option value="pending">Chờ xác nhận</option>
                <option value="cod">COD (Khi nhận hàng)</option>
                <option value="failed">Thất bại / Hủy</option>
                <option value="refunded">Đã hoàn tiền</option>
            </select>
            <select class="filter-select">
                <option value="">-- Phương thức thanh toán --</option>
                <option value="qr">Chuyển khoản QR</option>
                <option value="momo">Ví MoMo</option>
                <option value="vnpay">VNPay</option>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
            </select>
            <button class="btn-filter">Lọc</button>
        </div>

        <!-- Bảng danh sách thanh toán -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã giao dịch</th>
                        <th>Khách hàng</th>
                        <th>Phương thức</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>PAY-9825</strong></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Phạm Minh Tuấn</span>
                                <span class="customer-phone">0988 123 456</span>
                            </div>
                        </td>
                        <td><span class="badge-category"><i class="fa-solid fa-truck-fast"></i> Thanh toán COD</span></td>
                        <td><strong>850.000 đ</strong></td>
                        <td>19/09/2026 11:20</td>
                        <td><span class="badge-status status-cod"><i class="fa-solid fa-truck"></i> COD (Khi nhận hàng)</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"><i class="fa-regular fa-eye"></i></a>
                                <a href="#" class="btn-action btn-collected action-collected" title="Đã thu tiền">
                                    <i class="fa-solid fa-check-double"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>PAY-9824</strong></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Trần Thị B</span>
                                <span class="customer-phone">0912 888 999</span>
                            </div>
                        </td>
                        <td><span class="badge-category"><i class="fa-solid fa-building-columns"></i> Chuyển khoản</span></td>
                        <td><strong>2.300.000 đ</strong></td>
                        <td>19/09/2026 09:40</td>
                        <td><span class="badge-status status-pending"><i class="fa-regular fa-clock"></i> Chờ xác nhận</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"><i class="fa-regular fa-eye"></i></a>
                                <a href="#" class="btn-action btn-approve action-approve" title="Duyệt giao dịch">
                                    <i class="fa-solid fa-check"></i>
                                </a>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td><strong>PAY-9823</strong></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Nguyễn Văn A</span>
                                <span class="customer-phone">0901 234 567</span>
                            </div>
                        </td>
                        <td><span class="badge-category"><i class="fa-solid fa-qrcode"></i> VNPay QR</span></td>
                        <td><strong>1.500.000 đ</strong></td>
                        <td>19/09/2026 10:15</td>
                        <td><span class="badge-status status-success"><i class="fa-solid fa-circle-check"></i> Thành công</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"><i class="fa-regular fa-eye"></i></a>
                                <a href="#" class="btn-action btn-refund-action action-refund" title="Hoàn tiền">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>PAY-9822</strong></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Hoàng Quốc Việt</span>
                                <span class="customer-phone">0977 444 555</span>
                            </div>
                        </td>
                        <td><span class="badge-category"><i class="fa-solid fa-truck-fast"></i> Thanh toán COD</span></td>
                        <td><strong>3.200.000 đ</strong></td>
                        <td>18/09/2026 18:05</td>
                        <td><span class="badge-status status-cod"><i class="fa-solid fa-truck"></i> COD (Khi nhận hàng)</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"><i class="fa-regular fa-eye"></i></a>
                                <button type="button" class="btn-action btn-collected" title="Đã thu tiền"><i class="fa-solid fa-check-double"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>PAY-9821</strong></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Lê Hoàng C</span>
                                <span class="customer-phone">0933 111 222</span>
                            </div>
                        </td>
                        <td><span class="badge-category"><i class="fa-solid fa-wallet"></i> Ví MoMo</span></td>
                        <td><strong>450.000 đ</strong></td>
                        <td>18/09/2026 16:20</td>
                        <td><span class="badge-status status-refund action-collected"><i class="fa-solid fa-rotate-left"></i> Đã hoàn tiền</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view action-view" title="Xem chi tiết"><i class="fa-regular fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
include '../../includes/footer.php';
?>