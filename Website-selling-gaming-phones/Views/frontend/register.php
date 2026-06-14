<?php
$currentPage = $data['page'] ?? 'register';
?>
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
        <!-- NHẬN XÉT: Dùng class register-card để đảo cột (form bên trái, intro bên phải) -->
        <div class="auth-card register-card">
            <!-- ========================================================= -->
            <!-- PHẦN FORM ĐĂNG KÝ (BÊN TRÁI - do register-card) -->
            <!-- ========================================================= -->
            <div>
                <div style="margin-bottom: 18px;">
                    <span class="eyebrow">Tạo tài khoản</span>
                    <h2 style="margin: 6px 0 4px; font-family: var(--font-head); font-size: 2rem;">
                        Đăng ký thành viên Gaming Phone
                    </h2>
                    <p style="color: var(--muted); margin: 0;">
                        Thông tin được lưu vào bảng <code>users</code> với vai trò mặc định là người dùng.
                    </p>
                </div>

                <form class="auth-form" method="post" action="index.php?page=register" autocomplete="on">
                    <label>
                        Họ và tên <span style="color: var(--accent);">*</span>
                        <input type="text"
                               name="name"
                               required
                               placeholder="VD: Nguyễn Văn A"
                               autocomplete="name"
                               value="<?= e($_POST['name'] ?? '') ?>">
                    </label>
                    <label>
                        Tên đăng nhập <span style="color: var(--accent);">*</span>
                        <input type="text"
                               name="username"
                               required
                               minlength="3"
                               placeholder="Tối thiểu 3 ký tự"
                               autocomplete="username"
                               value="<?= e($_POST['username'] ?? '') ?>">
                    </label>
                    <label>
                        Email hoặc số điện thoại <span style="color: var(--accent);">*</span>
                        <input type="text"
                               name="contact"
                               required
                               placeholder="VD: 0987654321 hoặc email@domain.com"
                               autocomplete="email"
                               value="<?= e($_POST['contact'] ?? '') ?>">
                    </label>
                    <label>
                        Mật khẩu <span style="color: var(--accent);">*</span>
                        <input type="password"
                               name="password"
                               required
                               minlength="6"
                               placeholder="Tối thiểu 6 ký tự"
                               autocomplete="new-password">
                    </label>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa-solid fa-user-plus"></i> Đăng ký
                    </button>
                </form>

                <div class="auth-links" style="margin-top: 16px; justify-content: center;">
                    <a href="index.php?page=login">Đã có tài khoản? Đăng nhập</a>
                    <a href="index.php?page=home">Về trang chủ</a>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- PHẦN GIỚI THIỆU (BÊN PHẢI - do register-card) -->
            <!-- ========================================================= -->
            <div class="auth-intro">
                <span class="eyebrow">Gia nhập cộng đồng</span>
                <h1>Trở thành game thủ chính hiệu.</h1>
                <p>
                    Đăng ký tài khoản để nhận ưu đãi độc quyền, theo dõi đơn hàng
                    và tích điểm thành viên cho những lần mua sắm tiếp theo.
                </p>
                <ul style="list-style: none; padding: 0; margin-top: 18px;">
                    <li style="margin-bottom: 10px;">
                        <i class="fa-solid fa-check" style="color: var(--success);"></i>
                        Miễn phí đăng ký 100%
                    </li>
                    <li style="margin-bottom: 10px;">
                        <i class="fa-solid fa-check" style="color: var(--success);"></i>
                        Bảo mật thông tin tuyệt đối
                    </li>
                    <li style="margin-bottom: 10px;">
                        <i class="fa-solid fa-check" style="color: var(--success);"></i>
                        Hỗ trợ 24/7 qua hotline
                    </li>
                    <li>
                        <i class="fa-solid fa-check" style="color: var(--success);"></i>
                        Nhiều khuyến mãi dành riêng cho thành viên
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>