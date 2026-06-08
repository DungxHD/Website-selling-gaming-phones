<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gaming Phone Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0066cc, #004c99);
            min-height: 100vh;
        }
        .admin-login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .admin-login-box {
            background: var(--bg-card);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
        }
        .admin-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-logo i {
            font-size: 60px;
            color: var(--primary-color);
        }
        .admin-logo h1 {
            color: var(--text-primary);
            margin-top: 15px;
            font-size: 24px;
        }
        .admin-logo p {
            color: var(--text-secondary);
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-box">
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                <h1>ADMIN PORTAL</h1>
                <p>Đăng nhập quản trị hệ thống</p>
            </div>
            
            <form id="adminLoginForm" onsubmit="handleAdminLogin(event)">
                <div class="form-group">
                    <label for="adminEmail">
                        <i class="fas fa-envelope"></i> Email Admin
                    </label>
                    <input type="email" id="adminEmail" name="email" required placeholder="admin@gamingphone.vn">
                </div>
                
                <div class="form-group">
                    <label for="adminPassword">
                        <i class="fas fa-lock"></i> Mật khẩu
                    </label>
                    <input type="password" id="adminPassword" name="password" required placeholder="Nhập mật khẩu admin">
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="../index.php" style="color: var(--primary-color); font-size: 14px;">
                    <i class="fas fa-arrow-left"></i> Về trang chủ
                </a>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 13px;">
                <p>Demo credentials:</p>
                <p style="color: var(--text-secondary);">Email: admin@gamingphone.vn</p>
                <p style="color: var(--text-secondary);">Password: admin123</p>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        async function handleAdminLogin(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch('../index.php?controller=admin&action=processLogin', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Đăng nhập thành công!', 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect || '../index.php?controller=admin&action=dashboard';
                    }, 1000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Admin login error:', error);
                showNotification('Có lỗi xảy ra khi đăng nhập', 'error');
            }
        }
    </script>
</body>
</html>
