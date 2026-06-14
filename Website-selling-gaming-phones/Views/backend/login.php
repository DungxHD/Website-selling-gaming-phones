<?php
// Nếu đã đăng nhập admin rồi thì chuyển thẳng sang dashboard
if (!empty($_SESSION['admin'])) {
    header("Location: index.php?page=admin_dashboard");
    exit();
}
?>
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
<div class="admin-login-shell">
    <div class="admin-login-card">
        <a class="back-link" href="index.php?page=home">
            <i class="fa-solid fa-arrow-left"></i> Về trang chủ
        </a>
        <div class="admin-login-heading">
            <span><i class="fa-solid fa-shield-halved"></i></span>
            <h1>Admin Portal</h1>
            <p>Đăng nhập để sử dụng dịch vụ dành riêng cho quản trị viên.</p>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>">
                <?= e($data['flash']['message']) ?>
            </div>
        <?php endif; ?>

        <form class="admin-login-form" method="post" action="index.php?page=admin_login">
            <label>
                Tài khoản admin
                <input type="text" name="username" required placeholder="VD: admin_fpt" autocomplete="username">
            </label>
            <label>
                Mật khẩu
                <input type="password" name="password" required placeholder="••••••••" autocomplete="current-password">
            </label>
            <button class="btn btn-primary w-100" type="submit">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập quản trị
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                <i class="fa-solid fa-circle-info"></i>
                Tài khoản mẫu: <code>admin_fpt / 123456</code>
            </small>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>