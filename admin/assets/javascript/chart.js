document.addEventListener("DOMContentLoaded", () => {

    // 1. Biểu đồ đường: Dữ liệu 6 tháng đầu vào
    const lineChart = new Chart(document.getElementById("lineChart"), {
        type: "line",
        data: {
            labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6"],
            datasets: [
                {
                    label: "Đầu vào A",
                    data: [30, 45, 35, 60, 50, 75],
                    borderColor: "#0284c7",
                    backgroundColor: "rgba(2, 132, 199, 0.1)",
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: "Đầu vào B",
                    data: [20, 60, 40, 50, 70, 90],
                    borderColor: "#eab308",
                    backgroundColor: "rgba(234, 179, 8, 0.1)",
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "top", align: "end" } },
            scales: { y: { beginAtZero: true, max: 100 } },
        },
    });

    // 2. Biểu đồ cột: Thống kê doanh thu (Tháng, Quý, Năm)
    const revenueData = {
        month: {
            labels: ["Th 1", "Th 2", "Th 3", "Th 4", "Th 5", "Th 6"],
            revenue: [15, 30, 60, 40, 70, 95],
            profit: [10, 20, 35, 45, 80, 50],
            max: 120,
        },
        quarter: {
            labels: ["Quý 1", "Quý 2", "Quý 3", "Quý 4"],
            revenue: [120, 150, 180, 210],
            profit: [80, 100, 130, 160],
            max: 300,
        },
        year: {
            labels: ["2024", "2025", "2026"],
            revenue: [500, 750, 920],
            profit: [350, 520, 680],
            max: 1000,
        },
    };

    const barChart = new Chart(document.getElementById("barChart"), {
        type: "bar",
        data: {
            labels: revenueData.month.labels,
            datasets: [
                {
                    label: "Doanh thu",
                    data: revenueData.month.revenue,
                    backgroundColor: "#eab308",
                    borderRadius: 4,
                },
                {
                    label: "Lợi nhuận",
                    data: revenueData.month.profit,
                    backgroundColor: "#0284c7",
                    borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "top", align: "end" } },
            scales: { y: { beginAtZero: true, max: 120 } },
        },
    });

    // Hàm cập nhật khi thay đổi thẻ <select>
    function updateRevenueChart(type) {
        const d = revenueData[type];
        barChart.data.labels = d.labels;
        barChart.data.datasets[0].data = d.revenue;
        barChart.data.datasets[1].data = d.profit;
        barChart.options.scales.y.max = d.max;
        barChart.update();
    }
})