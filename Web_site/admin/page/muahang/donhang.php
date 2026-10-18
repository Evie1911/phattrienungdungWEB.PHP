<?php
require_once '../../config.php';

// Cấu hình tiêu đề động cho trang
$pageTitle = "Quản lý đơn hàng";

// Các thành phần giao diện
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>


<main class="main-content">
    <div class="content-card">
        <!-- Tiêu đề trang & Nút hành động -->
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Đơn Hàng</h1>
                <p>Theo dõi, cập nhật trạng thái và quản lý đơn hàng của khách hàng</p>
            </div>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" placeholder="Tìm theo mã đơn hàng, tên khách hàng, số điện thoại...">
            </div>
            <select class="filter-select">
                <option value="">-- Trạng thái đơn hàng --</option>
                <option value="pending">Chờ xác nhận</option>
                <option value="confirmed">Đã xác nhận</option>
                <option value="packing">Đang đóng gói</option>
                <option value="shipping">Đang giao hàng</option>
                <option value="completed">Hoàn thành</option>
                <option value="cancelled">Đã hủy</option>
            </select>
            <button class="btn-filter">Lọc</button>
        </div>

        <!-- Bảng danh sách đơn hàng -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Đơn hàng</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Tình trạng</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DH-9012</strong></td>
                        <td>
                            <div class="customer-name">Nguyễn Văn A</div>
                            <div class="customer-phone">0901234567</div>
                        </td>
                        <td>iPhone 15 Pro Max 256GB</td>
                        <td>1</td>
                        <td><strong>29.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-confirmed">
                                <option value="confirmed" selected>Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-view btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9013</strong></td>
                        <td>
                            <div class="customer-name">Trần Thị B</div>
                            <div class="customer-phone">0912345678</div>
                        </td>
                        <td>Samsung Galaxy S24 Ultra</td>
                        <td>1</td>
                        <td><strong>26.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-shipping">
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping" selected>Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9013</strong></td>
                        <td>
                            <div class="customer-name">Trần Thị B</div>
                            <div class="customer-phone">0912345678</div>
                        </td>
                        <td>Samsung Galaxy S24 Ultra</td>
                        <td>1</td>
                        <td><strong>26.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-shipping">
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping" selected>Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9013</strong></td>
                        <td>
                            <div class="customer-name">Trần Thị B</div>
                            <div class="customer-phone">0912345678</div>
                        </td>
                        <td>Samsung Galaxy S24 Ultra</td>
                        <td>1</td>
                        <td><strong>26.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-shipping">
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping" selected>Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9013</strong></td>
                        <td>
                            <div class="customer-name">Trần Thị B</div>
                            <div class="customer-phone">0912345678</div>
                        </td>
                        <td>Samsung Galaxy S24 Ultra</td>
                        <td>1</td>
                        <td><strong>26.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-shipping">
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping" selected>Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9014</strong></td>
                        <td>
                            <div class="customer-name">Lê Văn C</div>
                            <div class="customer-phone">0988888888</div>
                        </td>
                        <td>Tai Nghe AirPods Pro 2</td>
                        <td>2</td>
                        <td><strong>11.980.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-packing">
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing" selected>Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9015</strong></td>
                        <td>
                            <div class="customer-name">Phạm Thị D</div>
                            <div class="customer-phone">0977777777</div>
                        </td>
                        <td>MacBook Pro 16" M3 Max</td>
                        <td>1</td>
                        <td><strong>69.990.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-pending">
                                <option value="pending" selected>Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DH-9016</strong></td>
                        <td>
                            <div class="customer-name">Hoàng Văn E</div>
                            <div class="customer-phone">0933333333</div>
                        </td>
                        <td>Sạc Nhanh Anker 20W</td>
                        <td>3</td>
                        <td><strong>1.050.000 đ</strong></td>
                        <td>
                            <select class="product-input status-select status-completed">
                                <option value="pending">Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="packing">Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed" selected>Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn-action btn-action-view" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
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