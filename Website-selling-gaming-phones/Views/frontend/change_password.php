<?php
$currentPage = $data['page'] ?? 'change_password';
$currentUser = $data['currentUser'] ?? null;
?>
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
        <div class="auth-card">
            <!-- ========================================================= -->
            <!-- PHẦN GIỚI THIỆU (BÊN TRÁI) -->
            <!-- ========================================================= -->
            <div class="auth-intro">
                <span class="eyebrow">Tài khoản</span>
                <h1>Đổi mật khẩu</h1>
                <p>
                    Nhập đúng mật khẩu cũ để đổi sang mật khẩu mới.
                    Hãy chọn mật khẩu mạnh để bảo vệ tài khoản của bạn.
                </p>

                <!-- Thông tin user đang đăng nhập -->
                <?php if ($currentUser): ?>
                <div style="margin-top: 20px; padding: 14px; background: var(--surface-2); border-radius: 8px;">
                    <strong style="display: block; color: var(--primary); margin-bottom: 8px;">
                        <i class="fa-solid fa-user"></i> Đang đăng nhập
                    </strong>
                    <p style="margin: 2px 0; font-size: 0.9rem;">
                        <strong>Tên:</strong> <?= e($currentUser['name']) ?>
                    </p>
                    <p style="margin: 2px 0; font-size: 0.9rem;">
                        <strong>Username:</strong> <?= e($currentUser['username']) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Mẹo đặt mật khẩu mạnh -->
                <div style="margin-top: 20px; padding: 14px; background: var(--surface-2); border-radius: 8px; border-left: 3px solid var(--warning);">
                    <strong style="display: flex; align-items: center; gap: 8px; color: var(--warning); margin-bottom: 6px;">
                        <i class="fa-solid fa-lightbulb"></i> Mẹo đặt mật khẩu mạnh
                    </strong>
                    <ul style="margin: 0; padding-left: 18px; font-size: 0.88rem; color: var(--muted);">
                        <li>Ít nhất 6 ký tự</li>
                        <li>Kết hợp chữ hoa, chữ thường, số</li>
                        <li>Không dùng thông tin cá nhân</li>
                        <li>Không trùng với mật khẩu cũ</li>
                    </ul>
                </div>

                <div class="auth-links" style="flex-direction: column; align-items: flex-start; gap: 10px; margin-top: 20px;">
                    <a href="index.php?page=home">
                        <i class="fa-solid fa-house"></i> Về trang chủ
                    </a>
                    <a href="index.php?page=logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- PHẦN FORM ĐỔI MẬT KHẨU (BÊN PHẢI) -->
            <!-- ========================================================= -->
            <div>
                <div style="margin-bottom: 18px;">
                    <span class="eyebrow">Bảo mật</span>
                    <h2 style="margin: 6px 0 4px; font-family: var(--font-head); font-size: 2rem;">
                        Cập nhật mật khẩu mới
                    </h2>
                    <p style="color: var(--muted); margin: 0;">
                        Đảm bảo bạn nhớ mật khẩu cũ trước khi đổi sang mật khẩu mới.
                    </p>
                </div>

                <form class="auth-form" method="post" action="index.php?page=change_password" autocomplete="off">
                    <label>
                        Mật khẩu cũ <span style="color: var(--accent);">*</span>
                        <input type="password"
                               name="old_password"
                               required
                               placeholder="Nhập mật khẩu hiện tại"
                               autocomplete="current-password">
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
                        <i class="fa-solid fa-key"></i> Đổi mật khẩu
                    </button>
                </form>

                <div class="auth-links" style="margin-top: 16px; justify-content: center;">
                    <a href="index.php?page=home">Hủy bỏ</a>
                    <a href="index.php?page=forgot">Quên mật khẩu cũ?</a>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>