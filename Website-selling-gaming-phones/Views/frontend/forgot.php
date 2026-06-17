<?php
$currentPage = $data['page'] ?? 'forgot';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Gaming Phone Store</title>
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
                <span class="eyebrow">Quên mật khẩu</span>
                <h1>Đổi mật khẩu nhanh</h1>


                <!-- Lưu ý cơ chế đơn giản hóa -->
                <div style="margin-top: 20px; padding: 14px; background: var(--surface-2); border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="display: flex; align-items: center; gap: 8px; color: var(--primary); margin-bottom: 6px;">
                        <i class="fa-solid fa-circle-info"></i> Lưu ý
                    </strong>
                    <p style="margin: 0; font-size: 0.9rem; color: var(--muted);">
                        Trong thực tế, hệ thống sẽ gửi email/SMS xác nhận.
                    </p>
                </div>

                <div class="auth-links" style="flex-direction: column; align-items: flex-start; gap: 10px; margin-top: 20px;">
                    <a href="index.php?page=login">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại đăng nhập
                    </a>
                    <a href="index.php?page=home">
                        <i class="fa-solid fa-house"></i> Về trang chủ
                    </a>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- PHẦN FORM QUÊN MẬT KHẨU (BÊN PHẢI) -->
            <!-- ========================================================= -->
            <div>
                <div style="margin-bottom: 18px;">
                    <span class="eyebrow">Khôi phục tài khoản</span>
                    <h2 style="margin: 6px 0 4px; font-family: var(--font-head); font-size: 2rem;">
                        Đặt lại mật khẩu
                    </h2>
                    <p style="color: var(--muted); margin: 0;">
                        Điền đầy đủ 3 trường bên dưới để xác minh và đổi mật khẩu mới.
                    </p>
                </div>

                <form class="auth-form" method="post" action="index.php?page=forgot" autocomplete="off">
                    <label>
                        Tên đăng nhập <span style="color: var(--accent);">*</span>
                        <input type="text"
                            name="username"
                            required
                            placeholder="VD: gaming_thu_99"
                            value="<?= e($_POST['username'] ?? '') ?>">
                    </label>
                    <label>
                        Liên hệ (SĐT/Email đã đăng ký) <span style="color: var(--accent);">*</span>
                        <input type="text"
                            name="contact"
                            required
                            placeholder="Phải trùng với thông tin khi đăng ký"
                            value="<?= e($_POST['contact'] ?? '') ?>">
                    </label>
                    <label>
                        Mật khẩu mới <span style="color: var(--accent);">*</span>
                        <input type="password"
                            name="new_password"
                            required
                            minlength="6"
                            placeholder="Tối thiểu 6 ký tự"
                            autocomplete="new-password">
                    </label>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa-solid fa-rotate-right"></i> Cập nhật mật khẩu
                    </button>
                </form>

                <div class="auth-links" style="margin-top: 16px; justify-content: center;">
                    <a href="index.php?page=login">Quay lại đăng nhập</a>
                    <a href="index.php?page=register">Đăng ký tài khoản mới</a>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>