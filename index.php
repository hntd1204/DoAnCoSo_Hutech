<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "swiftship";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$success_message = $_SESSION['success_message'] ?? "";
$error_message = $_SESSION['error_message'] ?? "";
unset($_SESSION['success_message'], $_SESSION['error_message']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_role'])) {
    $role = $_POST['form_role'];
    $username = $_POST['username'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? null;
    $cccd = $_POST['cccd'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($password) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, role, cccd, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $phone, $role, $cccd, $password);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Đăng ký thành công!";
    } else {
        $_SESSION['error_message'] = "Đăng ký thất bại: " . $stmt->error;
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftShip - Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="./img/logo.png" alt="Logo" style="height: 50px;">
                <span class="ms-2 fs-4 fw-bold">SwiftShip</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Khách hàng</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Cộng đồng khách hàng</a></li>
                            <li><a class="dropdown-item" href="#">Hỗ trợ khách hàng</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tài xế</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Cộng đồng tài xế</a></li>
                            <li><a class="dropdown-item" href="#">Cẩm nang tài xế</a></li>
                            <li><a class="dropdown-item" href="#">Trung tâm hỗ trợ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tin tức</a></li>
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li class="nav-item text-white ms-3">👋 <?= htmlspecialchars($_SESSION['username']) ?></li>
                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-light text-primary ms-2">Đăng xuất</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-warning text-white ms-3">Đăng nhập</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner + Form -->
    <div class="container-fluid bg-light"
        style="margin-top: 100px; background-image: url('./img/bg.png'); background-size: cover; background-position: center; min-height: 600px;">
        <div class="row justify-content-end pe-5">
            <div class="col-md-4 mt-5">
                <div class="form-container">

                    <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success"><?= $success_message ?></div>
                    <?php elseif (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-around mb-4">
                        <button class="btn btn-link tab-btn active" onclick="switchForm('user')">Người dùng</button>
                        <button class="btn btn-link tab-btn" onclick="switchForm('staff')">Tài xế</button>
                    </div>

                    <!-- Form người dùng -->
                    <form id="form-user" method="POST">
                        <input type="hidden" name="form_role" value="user">

                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text">+84</span>
                                <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu"
                                required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Đăng ký</button>
                    </form>

                    <!-- Form tài xế -->
                    <form id="form-staff" method="POST" class="d-none">
                        <input type="hidden" name="form_role" value="staff">

                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text">+84</span>
                                <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CCCD</label>
                            <input type="text" name="cccd" class="form-control" placeholder="Nhập số CCCD" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu"
                                required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách tài xế -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 text-primary">Tài xế có ngay!</h2>
            <div class="row g-4">
                <!-- Card tài xế -->
                <div class="col-md-3" *ngFor="let driver of drivers">
                    <div class="card h-100">
                        <img src="./img/driver.png" class="card-img-top" alt="Tài xế">
                        <div class="card-body">
                            <h5 class="card-title">Nguyễn Văn A</h5>
                            <p class="card-text small text-muted">Giao khu vực: Tp.HCM<br>Đã giao: 120 đơn<br>Đánh giá:
                                ⭐⭐⭐⭐☆</p>
                        </div>
                    </div>
                </div>
                <!-- Repeat cards as needed -->
            </div>
            <div class="text-center mt-4">
                <button class="btn btn-primary">Xem thêm</button>
            </div>
        </div>
    </section>

    <!-- Dịch vụ nổi bật -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center text-primary mb-5">Vì sao chọn SwiftShip?</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="p-4 bg-primary text-white rounded">
                        <img src="/img/fast-delivery.png" alt="" class="mb-3" style="width: 50px;">
                        <h5>Giao hàng trong ngày</h5>
                        <p>Nhanh chóng, đúng hẹn – đảm bảo giao trong vòng 24h.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-primary text-white rounded">
                        <img src="/img/tracking.png" alt="" class="mb-3" style="width: 50px;">
                        <h5>Theo dõi đơn hàng</h5>
                        <p>Xem vị trí đơn hàng trực tuyến mọi lúc, mọi nơi.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-primary text-white rounded">
                        <img src="/img/driver.png" alt="" class="mb-3" style="width: 50px;">
                        <h5>Tài xế chuyên nghiệp</h5>
                        <p>Thân thiện, đúng giờ, thông thạo đường phố.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-primary text-white rounded">
                        <img src="/img/best-price.png" alt="" class="mb-3" style="width: 50px;">
                        <h5>Giá cả cạnh tranh</h5>
                        <p>Dịch vụ chất lượng với chi phí hợp lý nhất.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Đánh giá khách hàng -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center text-primary mb-5">Đánh giá khách hàng</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-white p-3 shadow-sm rounded">
                        <div class="d-flex align-items-center mb-2">
                            <img src="./img/driver.png" class="testimonial-img me-2" alt="avatar">
                            <div>
                                <strong>Nguyễn Chi</strong>
                                <p class="small mb-0">Thủ Đức, Tp.HCM</p>
                            </div>
                        </div>
                        <p class="mb-1 text-warning">★★★★★</p>
                        <p class="small">Dịch vụ chuyên nghiệp, giá cả hợp lý, nhân viên tư vấn tận tình.</p>
                    </div>
                </div>
                <!-- Repeat testimonials as needed -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-3">
                    <h5>SwiftShip</h5>
                    <p>Dịch vụ giao hàng nhanh, an toàn và tiện lợi. Đồng hành cùng mọi bước chuyển động của bạn.</p>
                </div>
                <div class="col-md-3">
                    <h6>Dịch vụ</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Giao hàng trong ngày</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Theo dõi đơn hàng</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Tài xế chuyên nghiệp</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Liên hệ</h6>
                    <ul class="list-unstyled">
                        <li><i class="fa-solid fa-phone me-2"></i>1900 123 456</li>
                        <li><i class="fa-solid fa-envelope me-2"></i>support@swiftship.vn</li>
                        <li><i class="fa-solid fa-house me-2"></i>123 Nguyễn Văn Cừ, Q5, TP.HCM</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kết nối với chúng tôi</h6>
                    <div class="d-flex gap-2 fs-5">
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-google-plus-g"></i>
                        <i class="fab fa-threads"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center py-3 bg-dark">© 2025 SwiftShip. All rights reserved.</div>
    </footer>

    <script>
    function switchForm(type) {
        document.getElementById('form-user').classList.toggle('d-none', type !== 'user');
        document.getElementById('form-staff').classList.toggle('d-none', type !== 'staff');

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-btn[onclick*="${type}"]`).classList.add('active');
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>