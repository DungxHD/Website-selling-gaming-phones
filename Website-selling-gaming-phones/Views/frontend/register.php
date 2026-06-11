<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main class="auth-page">
    <section class="auth-card register-card">
        <div class="auth-intro">
            <span class="eyebrow">Tạo tài khoản</span>
            <h1>Đăng ký thành viên Gaming Phone.</h1>
            <p>Thông tin được lưu vào bảng <code>users</code> với vai trò mặc định là người dùng.</p>
        </div>
        <form class="auth-form" method="post" action="index.php?page=register&action=register">
            <label>Họ và tên
                <input type="text" name="name" required autocomplete="name">
            </label>
            <label>Tên đăng nhập
                <input type="text" name="username" required autocomplete="username">
            </label>
            <label>Email hoặc số điện thoại
                <input type="text" name="contact" required autocomplete="email">
            </label>
            <label>Mật khẩu
                <input type="password" name="password" required autocomplete="new-password">
            </label>
            <button class="btn btn-primary w-100" type="submit">Đăng ký</button>
            <div class="auth-links">
                <a href="index.php?page=login">Đã có tài khoản? Đăng nhập</a>
                <a href="index.php?page=home">Về trang chủ</a>
            </div>
        </form>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>


