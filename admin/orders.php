<?php
session_start();
require_once("../database.php");
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Cập nhật trạng thái
if (isset($_GET['update']) && isset($_GET['status'])) {
    $id = intval($_GET['update']);
    $status = $_GET['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $id");
    header("Location: orders.php");
    exit();
}

// Xoá đơn
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: orders.php");
    exit();
}

// Lấy danh sách đơn hàng
$sql = "SELECT o.*, 
               u.username AS customer_name,
               d.username AS driver_name 
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        LEFT JOIN users d ON o.driver_id = d.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
</head>

<body>

    <?php include("includes/sidebar.php"); ?>

    <div class="content">
        <?php include("includes/header.php"); ?>

        <div class="container py-4">
            <h4 class="mb-4">📦 Danh sách đơn hàng</h4>

            <table class="table table-bordered bg-white shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tài xế</th>
                        <th>Điểm lấy hàng</th>
                        <th>Điểm giao hàng</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= $order['driver_name'] ? htmlspecialchars($order['driver_name']) : "<em>Chưa phân</em>" ?>
                            </td>
                            <td><?= htmlspecialchars($order['pickup_address']) ?></td>
                            <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                            <td>
                                <span
                                    class="badge text-bg-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'delivering' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= $order['created_at'] ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="?update=<?= $order['id'] ?>&status=delivering"
                                        class="btn btn-sm btn-warning">Đang giao</a>
                                    <a href="?update=<?= $order['id'] ?>&status=completed"
                                        class="btn btn-sm btn-success">Hoàn tất</a>
                                    <a href="?delete=<?= $order['id'] ?>" onclick="return confirm('Xóa đơn hàng này?');"
                                        class="btn btn-sm btn-danger">Xoá</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>