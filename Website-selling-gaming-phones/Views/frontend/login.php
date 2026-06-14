<?php
$currentPage = $data['page'] ?? 'login';
?>
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
        <div class="auth-card">
            <!-- ========================================================= -->
            <!-- PHẦN GIỚI THIỆU (BÊN TRÁI) -->
            <!-- ========================================================= -->
            <div class="auth-intro">
                <span class="eyebrow">Tài khoản người dùng</span>
                <h1>Đăng nhập để theo dõi giỏ hàng và đơn hàng.</h1>
                <p>
                    Form này kết nối với bảng <code>users</code> trong cơ sở dữ liệu.
                    Tài khoản mẫu: <code>gaming_thu_99 / 123456</code>.
                </p>
                <div class="auth-links" style="flex-direction: column; align-items: flex-start; gap: 10px;">
                    <a href="index.php?page=register">
                        <i class="fa-solid fa-user-plus"></i> Chưa có tài khoản? Đăng ký
                    </a>
                    <a href="index.php?page=forgot">
                        <i class="fa-solid fa-key"></i> Quên mật khẩu
                    </a>
                    <a href="index.php?page=home">
                        <i class="fa-solid fa-house"></i> Về trang chủ
                    </a>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- PHẦN FORM ĐĂNG NHẬP (BÊN PHẢI) -->
            <!-- ========================================================= -->
            <div>
                <div style="margin-bottom: 18px;">
                    <span class="eyebrow">Đăng nhập</span>
                    <h2 style="margin: 6px 0 4px; font-family: var(--font-head); font-size: 2rem;">
                        Chào mừng trở lại
                    </h2>
                    <p style="color: var(--muted); margin: 0;">
                        Nhập thông tin tài khoản để tiếp tục mua sắm.
                    </p>
                </div>

                <!-- Form POST về chính trang login -->
                <form class="auth-form" method="post" action="index.php?page=login" autocomplete="on">
                    <label>
                        Tên đăng nhập
                        <input type="text"
                               name="username"
                               required
                               placeholder="VD: gaming_thu_99"
                               autocomplete="username"
                               value="<?= e($_POST['username'] ?? '') ?>">
                    </label>
                    <label>
                        Mật khẩu
                        <input type="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               autocomplete="current-password">
                    </label>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
                    </button>
                </form>

                <div class="auth-links" style="margin-top: 16px; justify-content: center;">
                    <a href="index.php?page=register">Tạo tài khoản mới</a>
                    <a href="index.php?page=forgot">Quên mật khẩu?</a>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>