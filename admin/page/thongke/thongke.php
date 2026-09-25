<?php
require_once '../../config.php';
// Cấu hình tiêu đề động cho trang
$pageTitle = "Trang chủ - Thống kê";

//Các thành phần giao diện
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/data.css">

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container dashboard-container">

        <!-- Tiêu đề trang quản trị -->
        <div class="dashboard-header">
            Trang chủ quản trị
        </div>

        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-title">Tổng khách hàng</div>
                <div class="stat-value">56 khách hàng</div>
                <div class="stat-desc">Tổng số khách hàng được quản lý.</div>
            </div>
            <div class="stat-card dark-blue">
                <div class="stat-icon"><i class="fa-solid fa-database"></i></div>
                <div class="stat-title">Tổng sản phẩm</div>
                <div class="stat-value">1850 sản phẩm</div>
                <div class="stat-desc">Tổng số sản phẩm được quản lý.</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="stat-title">Tổng đơn hàng</div>
                <div class="stat-value">247 đơn hàng</div>
                <div class="stat-desc">Tổng số hóa đơn bán hàng trong tháng.</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="stat-title">Sắp hết hàng</div>
                <div class="stat-value">4 sản phẩm</div>
                <div class="stat-desc">Số sản phẩm cảnh báo hết cần nhập thêm.</div>
            </div>
        </div>

        <div class="dashboard-main-grid">

            <div class="dashboard-column">

                <!-- Tình trạng đơn hàng -->
                <div class="dashboard-box">
                    <h3 class="dashboard-box-title">Tình trạng đơn hàng</h3>
                    <div class="dashboard-table-wrapper">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>ID đơn hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500;">AL3947</td>
                                    <td>Phạm Thị Ngọc</td>
                                    <td>19.770.000 đ</td>
                                    <td><span class="badge badge-pending">Chờ xử lý</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">ER3835</td>
                                    <td>Nguyễn Thị Mỹ Yến</td>
                                    <td>16.770.000 đ</td>
                                    <td><span class="badge badge-shipping">Đang vận chuyển</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">MD0837</td>
                                    <td>Triệu Thanh Phú</td>
                                    <td>9.400.000 đ</td>
                                    <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">MT9B35</td>
                                    <td>Đặng Hoàng Phúc</td>
                                    <td>40.650.000 đ</td>
                                    <td><span class="badge badge-cancel">Đã hủy</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Khách hàng mới -->
                <div class="dashboard-box">
                    <h3 class="dashboard-box-title">Khách hàng mới</h3>
                    <div class="dashboard-table-wrapper">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Ngày sinh</th>
                                    <th>Số điện thoại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500;">#183</td>
                                    <td>Hột vịt muối</td>
                                    <td>21/7/1992</td>
                                    <td>0921387221</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">#219</td>
                                    <td>Bánh tráng trộn</td>
                                    <td>30/4/1975</td>
                                    <td>0912376352</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">#627</td>
                                    <td>Cút rang bơ</td>
                                    <td>12/3/1999</td>
                                    <td>01287326854</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">#175</td>
                                    <td>Hủ tiếu nam vang</td>
                                    <td>4/12/2000</td>
                                    <td>0912376763</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI: Biểu đồ -->
            <div class="dashboard-column">

                <!-- Biểu đồ đường  -->
                <div class="dashboard-box">
                    <h3 class="dashboard-box-title" style="margin-bottom: 15px;">Dữ liệu 6 tháng đầu vào</h3>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <!-- Biểu đồ cột (Bar Chart) -->
                <div class="dashboard-box">
                    <div class="dashboard-box-title" style="margin-bottom: 15px;">
                        <span>Thống kê doanh thu</span>
                        <select style="padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;">
                            <option>Theo Tháng</option>
                            <option>Theo Quý</option>
                            <option>Theo Năm</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>



<?php
include '../../includes/footer.php';
?>