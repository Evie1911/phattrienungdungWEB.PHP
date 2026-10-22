<?php
require_once '../config.php';
include("xyly/xuly.php");

$db = new xuly();
$pageTitle = "Trang chủ - Thống kê";


// THỐNG KÊ TỔNG QUAN 

$sql_users = "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'";
$res_users = $db->xuatdulieu($sql_users);
$total_customers = $res_users[0]['total'] ?? 0;

$sql_products = "SELECT COUNT(*) AS total FROM products WHERE status = 1";
$res_products = $db->xuatdulieu($sql_products);
$total_products = $res_products[0]['total'] ?? 0;

$sql_orders = "SELECT COUNT(*) AS total FROM orders";
$res_orders = $db->xuatdulieu($sql_orders);
$total_orders = $res_orders[0]['total'] ?? 0;

$sql_low_stock = "SELECT COUNT(*) AS total FROM products WHERE stock <= 5 AND status = 1";
$res_low_stock = $db->xuatdulieu($sql_low_stock);
$total_low_stock = $res_low_stock[0]['total'] ?? 0;


// DANH SÁCH ĐƠN HÀNG VÀ KHÁCH HÀNG GẦN ĐÂY 

$sql_recent_orders = "SELECT id, order_code, receiver_name, total_amount, order_status FROM orders ORDER BY created_at DESC LIMIT 4";
$recent_orders = $db->xuatdulieu($sql_recent_orders);

$sql_recent_users = "SELECT id, user_code, full_name, email, phone, birthday, avatar FROM users WHERE role = 'customer' ORDER BY created_at DESC LIMIT 4";
$recent_users = $db->xuatdulieu($sql_recent_users);


// TỒN KHO SẢN PHẨM THEO THÁNG

$sql_import_chart = "
    SELECT MONTH(created_at) AS thong_thang, SUM(stock) AS tong_sl 
    FROM products 
    WHERE YEAR(created_at) = YEAR(CURDATE()) AND status = 1
    GROUP BY MONTH(created_at) 
    ORDER BY thong_thang ASC
";
$import_data_raw = $db->xuatdulieu($sql_import_chart);
$import_months = ["Th 1", "Th 2", "Th 3", "Th 4", "Th 5", "Th 6", "Th 7", "Th 8", "Th 9", "Th 10", "Th 11", "Th 12"];
$import_totals = array_fill(0, 12, 0);
foreach ($import_data_raw as $item) {
    $m = (int)$item['thong_thang'];
    $import_totals[$m - 1] = (int)$item['tong_sl'];
}


// DỮ LIỆU DOANH THU (Đơn hoàn thành)

// Theo Tháng
$sql_month = "SELECT MONTH(created_at) AS m, SUM(total_amount) AS rev FROM orders WHERE YEAR(created_at) = YEAR(CURDATE()) AND order_status = 'Hoàn thành' GROUP BY MONTH(created_at)";
$data_month = $db->xuatdulieu($sql_month);
$arr_month_rev = array_fill(0, 12, 0);
foreach ($data_month as $row) {
    $arr_month_rev[$row['m'] - 1] = (float)$row['rev'];
}

// Theo Quý
$sql_quarter = "SELECT QUARTER(created_at) AS q, SUM(total_amount) AS rev FROM orders WHERE YEAR(created_at) = YEAR(CURDATE()) AND order_status = 'Hoàn thành' GROUP BY QUARTER(created_at)";
$data_quarter = $db->xuatdulieu($sql_quarter);
$arr_quarter_rev = [0, 0, 0, 0];
foreach ($data_quarter as $row) {
    $arr_quarter_rev[$row['q'] - 1] = (float)$row['rev'];
}

// Theo Năm
$sql_year = "SELECT YEAR(created_at) AS y, SUM(total_amount) AS rev FROM orders WHERE order_status = 'Hoàn thành' GROUP BY YEAR(created_at) ORDER BY y ASC";
$data_year = $db->xuatdulieu($sql_year);
$arr_year_labels = [];
$arr_year_rev = [];
foreach ($data_year as $row) {
    $arr_year_labels[] = "Năm " . $row['y'];
    $arr_year_rev[] = (float)$row['rev'];
}
if (empty($arr_year_labels)) {
    $arr_year_labels = [date('Y')];
    $arr_year_rev = [0];
}


// DỮ LIỆU SỐ LƯỢNG SẢN PHẨM BÁN RA (Đơn hoàn thành)

// Theo Tháng
$sql_qty_month = "
    SELECT MONTH(o.created_at) AS m, SUM(od.quantity) AS total_qty 
    FROM orders o 
    JOIN order_items od ON o.id = od.order_id 
    WHERE YEAR(o.created_at) = YEAR(CURDATE()) AND o.order_status = 'Hoàn thành' 
    GROUP BY MONTH(o.created_at)
";
$data_qty_month = $db->xuatdulieu($sql_qty_month);
$arr_month_qty = array_fill(0, 12, 0);
foreach ($data_qty_month as $row) {
    $arr_month_qty[$row['m'] - 1] = (int)$row['total_qty'];
}

// Theo Quý
$sql_qty_quarter = "
    SELECT QUARTER(o.created_at) AS q, SUM(od.quantity) AS total_qty 
    FROM orders o 
    JOIN order_items od ON o.id = od.order_id 
    WHERE YEAR(o.created_at) = YEAR(CURDATE()) AND o.order_status = 'Hoàn thành' 
    GROUP BY QUARTER(o.created_at)
";
$data_qty_quarter = $db->xuatdulieu($sql_qty_quarter);
$arr_quarter_qty = [0, 0, 0, 0];
foreach ($data_qty_quarter as $row) {
    $arr_quarter_qty[$row['q'] - 1] = (int)$row['total_qty'];
}

// Theo Năm
$sql_qty_year = "
    SELECT YEAR(o.created_at) AS y, SUM(od.quantity) AS total_qty 
    FROM orders o 
    JOIN order_items od ON o.id = od.order_id 
    WHERE o.order_status = 'Hoàn thành' 
    GROUP BY YEAR(o.created_at) 
    ORDER BY y ASC
";
$data_qty_year = $db->xuatdulieu($sql_qty_year);
$arr_year_qty_labels = [];
$arr_year_qty_values = [];
foreach ($data_qty_year as $row) {
    $arr_year_qty_labels[] = "Năm " . $row['y'];
    $arr_year_qty_values[] = (int)$row['total_qty'];
}
if (empty($arr_year_qty_labels)) {
    $arr_year_qty_labels = [date('Y')];
    $arr_year_qty_values = [0];
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/data.css">

<main class="main-content" style="margin-top: 20px;">
    <div class="form-scroll-container dashboard-container">

        <div class="dashboard-header">
            Trang chủ quản trị
        </div>

        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-title">Tổng khách hàng</div>
                <div class="stat-value"><?php echo number_format($total_customers); ?> khách hàng</div>
                <div class="stat-desc">Tổng số khách hàng được quản lý.</div>
            </div>
            <div class="stat-card dark-blue">
                <div class="stat-icon"><i class="fa-solid fa-database"></i></div>
                <div class="stat-title">Tổng sản phẩm</div>
                <div class="stat-value"><?php echo number_format($total_products); ?> sản phẩm</div>
                <div class="stat-desc">Tổng số sản phẩm đang kinh doanh.</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="stat-title">Tổng đơn hàng</div>
                <div class="stat-value"><?php echo number_format($total_orders); ?> đơn hàng</div>
                <div class="stat-desc">Tổng số hóa đơn bán hàng hệ thống.</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="stat-title">Sắp hết hàng</div>
                <div class="stat-value"><?php echo number_format($total_low_stock); ?> sản phẩm</div>
                <div class="stat-desc">Số sản phẩm cảnh báo hết cần nhập thêm.</div>
            </div>
        </div>

        <div class="dashboard-main-grid">

            <div class="dashboard-column">
                <div class="dashboard-box">
                    <h3 class="dashboard-box-title">Tình trạng đơn hàng gần đây</h3>
                    <div class="dashboard-table-wrapper">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($recent_orders)) {
                                    $stt = 1;
                                    foreach ($recent_orders as $order) {
                                        $badgeStyle = 'color: #475569; ';

                                        if ($order['order_status'] == 'Chờ xác nhận') {
                                            $badgeStyle = 'color: #d97706; ';
                                        } elseif ($order['order_status'] == 'Đã xác nhận') {
                                            $badgeStyle = 'color: #0284c7; ';
                                        } elseif ($order['order_status'] == 'Đang đóng gói') {
                                            $badgeStyle = 'color: #7c3aed; ';
                                        } elseif ($order['order_status'] == 'Đang giao hàng') {
                                            $badgeStyle = 'color: #4f46e5; ';
                                        } elseif ($order['order_status'] == 'Hoàn thành') {
                                            $badgeStyle = ' color: #16a34a; ';
                                        } elseif ($order['order_status'] == 'Đã hủy') {
                                            $badgeStyle = 'color: #dc2626; ';
                                        }

                                        echo '<tr>
                                                <td><strong>' . $stt++ . '</strong></td>
                                                <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($order['order_code']) . '</span></td>
                                                <td>' . htmlspecialchars($order['receiver_name']) . '</td>
                                                <td>' . number_format($order['total_amount'], 0, ',', '.') . ' đ</td>
                                                <td><span style="' . $badgeStyle . '">' . htmlspecialchars($order['order_status']) . '</span></td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" style="text-align: center; padding: 20px;">Chưa có đơn hàng nào.</td></tr>';
                                }
                                ?>
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
                                    <th>STT</th>
                                    <th>Mã TK</th>
                                    <th>Tên khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngày sinh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($recent_users)) {
                                    $stt = 1;
                                    foreach ($recent_users as $user) {
                                        $birthday = $user['birthday'] ? date('d/m/Y', strtotime($user['birthday'])) : 'Chưa cập nhật';

                                        echo '<tr>
                                                <td><strong>' . $stt++ . '</strong></td>
                                                <td><span style="font-weight: 500;">' . htmlspecialchars($user['user_code']) . '</span></td>
                                                <td><span style="font-weight: 600; color: #0f172a;">' . htmlspecialchars($user['full_name']) . '</span></td>
                                                <td>' . htmlspecialchars($user['phone']) . '</td>
                                                <td>' . $birthday . '</td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" style="text-align: center; padding: 20px;">Chưa có khách hàng nào.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: Biểu đồ -->
            <div class="dashboard-column">
                <div class="dashboard-box">
                    <h3 class="dashboard-box-title" style="margin-bottom: 15px;">Thống kê tồn kho sản phẩm theo tháng</h3>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <!-- Biểu đồ kết hợp Doanh thu & Số lượng sản phẩm bán ra -->
                <div class="dashboard-box">
                    <div class="dashboard-box-title" style="margin-bottom: 15px;">
                        <span>Thống kê doanh thu & Số lượng bán ra (Đơn thành công)</span>
                        <select id="combinedFilter" style="padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;">
                            <option value="month">Theo Tháng</option>
                            <option value="quarter">Theo Quý</option>
                            <option value="year">Theo Năm</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="combinedChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // 1. Dữ liệu biểu đồ đường (Tồn kho)
        const importLabels = <?php echo json_encode($import_months); ?>;
        const importDataValues = <?php echo json_encode($import_totals); ?>;

        const ctxLine = document.getElementById("lineChart");
        if (ctxLine) {
            new Chart(ctxLine, {
                type: "line",
                data: {
                    labels: importLabels,
                    datasets: [{
                        label: "Tổng tồn kho sản phẩm",
                        data: importDataValues,
                        borderColor: "#0284c7",
                        backgroundColor: "rgba(2, 132, 199, 0.1)",
                        fill: true,
                        tension: 0.4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            align: "end"
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                },
            });
        }

        // 2. Dữ liệu biểu đồ kết hợp (Doanh thu & Số lượng)
        const combinedData = {
            month: {
                labels: ["Th 1", "Th 2", "Th 3", "Th 4", "Th 5", "Th 6", "Th 7", "Th 8", "Th 9", "Th 10", "Th 11", "Th 12"],
                revenue: <?php echo json_encode($arr_month_rev); ?>,
                quantity: <?php echo json_encode($arr_month_qty); ?>,
            },
            quarter: {
                labels: ["Quý 1", "Quý 2", "Quý 3", "Quý 4"],
                revenue: <?php echo json_encode($arr_quarter_rev); ?>,
                quantity: <?php echo json_encode($arr_quarter_qty); ?>,
            },
            year: {
                labels: <?php echo json_encode($arr_year_labels); ?>,
                revenue: <?php echo json_encode($arr_year_rev); ?>,
                quantity: <?php echo json_encode($arr_year_qty_values); ?>,
            }
        };

        const ctxCombined = document.getElementById("combinedChart");
        if (ctxCombined) {
            let combinedChart = new Chart(ctxCombined, {
                type: "bar",
                data: {
                    labels: combinedData.month.labels,
                    datasets: [{
                            label: "Doanh thu (VNĐ)",
                            data: combinedData.month.revenue,
                            backgroundColor: "#eab308",
                            borderRadius: 4,
                            yAxisID: 'y', // Trục Y bên trái
                        },
                        {
                            label: "Số lượng sản phẩm",
                            data: combinedData.month.quantity,
                            backgroundColor: "#3b82f6",
                            borderRadius: 4,
                            yAxisID: 'y1', // Trục Y bên phải
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            align: "end"
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false // Không hiển thị đè lưới lên trục bên trái
                            },
                            title: {
                                display: true,
                                text: 'Số lượng sản phẩm'
                            },
                            ticks: {
                                precision: 0 // Đảm bảo số lượng là số nguyên
                            }
                        }
                    },
                },
            });

            const filterSelect = document.getElementById("combinedFilter");
            if (filterSelect) {
                filterSelect.addEventListener("change", function() {
                    const selectedType = this.value;
                    const currentDataset = combinedData[selectedType];

                    combinedChart.data.labels = currentDataset.labels;
                    combinedChart.data.datasets[0].data = currentDataset.revenue;
                    combinedChart.data.datasets[1].data = currentDataset.quantity;
                    combinedChart.update();
                });
            }
        }
    });
</script>

<?php
include 'includes/footer.php';
?>