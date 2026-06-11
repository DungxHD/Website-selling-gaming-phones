<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - Gaming Phone Store</title>
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
            <span class="eyebrow">Tài khoản</span>
            <h1>Đổi mật khẩu</h1>
            <p>Nhập đúng mật khẩu cũ để đổi sang mật khẩu mới.</p>
        </div>
        <form class="auth-form" method="post" action="index.php?page=change_password&action=change_password">
            <label>Mật khẩu cũ
                <input type="password" name="old_password" required autocomplete="current-password">
            </label>
            <label>Mật khẩu mới
                <input type="password" name="new_password" required autocomplete="new-password">
            </label>
            <button class="btn btn-primary w-100" type="submit">Đổi mật khẩu</button>
            <div class="auth-links">
                <a href="index.php?page=home">Về trang chủ</a>
                <a href="index.php?page=logout">Đăng xuất</a>
            </div>
        </form>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>



