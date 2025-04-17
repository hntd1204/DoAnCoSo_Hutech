<?php
session_start();
require_once("../database.php");
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Doanh thu hôm nay
$today = date('Y-m-d');
$res_today = $conn->query("SELECT SUM(total_price) AS total FROM orders WHERE DATE(created_at) = '$today' AND status = 'completed'");
$revenue_today = $res_today->fetch_assoc()['total'] ?? 0;

// Doanh thu tháng
$month = date('Y-m');
$res_month = $conn->query("SELECT SUM(total_price) AS total FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month' AND status = 'completed'");
$revenue_month = $res_month->fetch_assoc()['total'] ?? 0;

// Doanh thu năm
$year = date('Y');
$res_year = $conn->query("SELECT SUM(total_price) AS total FROM orders WHERE YEAR(created_at) = '$year' AND status = 'completed'");
$revenue_year = $res_year->fetch_assoc()['total'] ?? 0;

// Dữ liệu biểu đồ: doanh thu theo 7 ngày gần nhất
$chartData = [];
$chartQuery = $conn->query("
    SELECT DATE(created_at) as day, SUM(total_price) as total
    FROM orders 
    WHERE status = 'completed' 
    GROUP BY day 
    ORDER BY day DESC 
    LIMIT 7
");

while ($row = $chartQuery->fetch_assoc()) {
    $chartData[] = [
        'day' => $row['day'],
        'total' => (float) $row['total']
    ];
}
$chartData = array_reverse($chartData); // Đảo ngược cho đúng thứ tự thời gian
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Doanh thu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <?php include("includes/sidebar.php"); ?>

    <div class="content">
        <?php include("includes/header.php"); ?>

        <div class="container py-4">
            <h4 class="mb-4">💰 Thống kê doanh thu</h4>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="dashboard-box">
                        <h3><?= number_format($revenue_today, 0, ',', '.') ?>đ</h3>
                        <p>Hôm nay</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-box">
                        <h3><?= number_format($revenue_month, 0, ',', '.') ?>đ</h3>
                        <p>Tháng này</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-box">
                        <h3><?= number_format($revenue_year, 0, ',', '.') ?>đ</h3>
                        <p>Năm nay</p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h5 class="mb-3">📈 Doanh thu 7 ngày gần nhất</h5>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script>
        const chartData = <?= json_encode($chartData) ?>;
        const labels = chartData.map(item => item.day);
        const totals = chartData.map(item => item.total);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: totals,
                    fill: false,
                    borderColor: '#0d6efd',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>