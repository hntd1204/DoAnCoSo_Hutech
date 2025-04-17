<?php
session_start();
require_once 'database.php';

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Sai mật khẩu!";
        }
    } else {
        $_SESSION['error'] = "Tài khoản không tồn tại!";
    }

    $stmt->close();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - SwiftShip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet">
    <link rel="icon" href="./img/logo.png" type="image/x-icon">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="./img/logo.png" alt="Logo" style="height: 50px;">
                <span class="ms-2 fs-4 fw-bold">SwiftShip</span>
            </a>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center"
        style="min-height: 100vh; padding-top: 60px;">
        <div class="col-md-4">
            <div class="login-card">
                <h3 class="mb-4 text-center text-primary">Đăng nhập</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu"
                            required>
                    </div>
                    <button type="submit" class="btn btn-warning text-white w-100">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>