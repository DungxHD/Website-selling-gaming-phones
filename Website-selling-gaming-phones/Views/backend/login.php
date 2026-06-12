<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-login-body">
<main class="admin-login-shell">
    <section class="admin-login-card">
        <a class="back-link" href="index.php?page=home"><i class="fa-solid fa-arrow-left"></i> Về trang chủ</a>
        <div class="admin-login-heading">
            <span><i class="fa-solid fa-shield-halved"></i></span>
            <h1>Admin Portal</h1>
            <p>Đăng nhập để sử dụng dịch vụ dành riêng cho admin.</p>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?page=admin_login&action=admin_login" class="admin-login-form">
            <label>Tài khoản admin
                <input class="form-control" type="text" name="username" required autocomplete="username" placeholder="admin_fpt">
            </label>
            <label>Mật khẩu
                <input class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="123456">
            </label>
            <button class="btn btn-primary w-100" type="submit">Đăng nhập quản trị</button>
        </form>
    </section>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
