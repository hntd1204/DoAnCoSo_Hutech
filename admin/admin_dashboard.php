<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản lý - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>📦 Admin Panel</h4>
        <hr class="border-white">
        <a href="admin_dashboard.php">📊 Tổng quan</a>
        <a href="users.php">👥 Người dùng</a>
        <a href="drivers.php">🚚 Tài xế</a>
        <a href="orders.php">📦 Đơn hàng</a>
        <a href="revenue.php">💰 Doanh thu</a>
        <a href="logout.php">🚪 Đăng xuất</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="header d-flex justify-content-between align-items-center">
            <h5>Trang quản lý</h5>
            <p>Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> 👋</p>
        </div>

        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="dashboard-box">
                        <h3>123</h3>
                        <p>Người dùng</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-box">
                        <h3>45</h3>
                        <p>Tài xế</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-box">
                        <h3>78</h3>
                        <p>Đơn hàng hôm nay</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-box">
                        <h3>5,600,000đ</h3>
                        <p>Doanh thu hôm nay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>