<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main class="auth-page">
    <section class="auth-card">
        <div class="auth-intro">
            <span class="eyebrow">Tài khoản người dùng</span>
            <h1>Đăng nhập để theo dõi giỏ hàng và đơn hàng.</h1>
            <p>Form này kết nối với bảng <code>users</code> trong cơ sở dữ liệu. Tài khoản mẫu: gaming_thu_99 / 123456.</p>
        </div>
        <form class="auth-form" method="post" action="index.php?page=login&action=login">
            <label>Tên đăng nhập
                <input type="text" name="username" required autocomplete="username">
            </label>
            <label>Mật khẩu
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
            <div class="auth-links">
                <a href="index.php?page=register">Chưa có tài khoản? Đăng ký</a>
                <a href="index.php?page=forgot">Quên mật khẩu</a>
                <a href="index.php?page=home">Về trang chủ</a>
            </div>
        </form>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>


