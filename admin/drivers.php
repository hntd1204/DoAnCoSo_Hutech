<?php
session_start();
require_once("../database.php");
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Xoá tài xế
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'staff'");
    header("Location: drivers.php");
    exit();
}

$drivers = $conn->query("SELECT id, username, email, phone, cccd FROM users WHERE role = 'staff'");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý tài xế</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
</head>

<body>

    <?php include("includes/sidebar.php"); ?>

    <div class="content">
        <?php include("includes/header.php"); ?>

        <div class="container py-4">
            <h4 class="mb-4">🚚 Danh sách tài xế</h4>

            <table class="table table-bordered bg-white shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Tên tài xế</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>CCCD</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $drivers->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['cccd']) ?></td>
                            <td>
                                <a href="drivers.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Xóa tài xế này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>