<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập / Đăng ký - Gaming Phone Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="top-bar">
            <div class="container">
                <div>
                    <i class="fas fa-phone"></i> Hotline: 1900 xxxx
                    <span style="margin: 0 15px;">|</span>
                    <i class="fas fa-envelope"></i> support@gamingphone.vn
                </div>
                <div>
                    <a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a>
                </div>
            </div>
        </div>
        
        <div class="main-header">
            <div class="container">
                <a href="../index.php" class="logo">
                    <i class="fas fa-gamepad"></i>
                    <span>GAMING PHONE</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-tabs">
                <div class="auth-tab active" data-tab="login">Đăng nhập</div>
                <div class="auth-tab" data-tab="register">Đăng ký</div>
            </div>
            
            <!-- Login Form -->
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="loginEmail">Email <span style="color: var(--danger-color);">*</span></label>
                    <input type="email" id="loginEmail" name="email" required placeholder="nhap@email.com">
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Mật khẩu <span style="color: var(--danger-color);">*</span></label>
                    <input type="password" id="loginPassword" name="password" required placeholder="Nhập mật khẩu">
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
                
                <a href="#" class="forgot-link" onclick="showForgotPassword()">
                    <i class="fas fa-key"></i> Quên mật khẩu?
                </a>
            </form>
            
            <!-- Register Form -->
            <form id="registerForm" style="display: none;" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label for="registerName">Họ và tên <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" id="registerName" name="full_name" required placeholder="Nguyễn Văn A">
                </div>
                
                <div class="form-group">
                    <label for="registerEmail">Email <span style="color: var(--danger-color);">*</span></label>
                    <input type="email" id="registerEmail" name="email" required placeholder="nhap@email.com">
                </div>
                
                <div class="form-group">
                    <label for="registerPhone">Số điện thoại</label>
                    <input type="tel" id="registerPhone" name="phone" placeholder="0901234567">
                </div>
                
                <div class="form-group">
                    <label for="registerPassword">Mật khẩu <span style="color: var(--danger-color);">*</span></label>
                    <input type="password" id="registerPassword" name="password" required placeholder="Ít nhất 6 ký tự" minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="registerConfirmPassword">Xác nhận mật khẩu <span style="color: var(--danger-color);">*</span></label>
                    <input type="password" id="registerConfirmPassword" name="confirm_password" required placeholder="Nhập lại mật khẩu">
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>
            
            <div class="auth-divider">
                <span>HOẶC</span>
            </div>
            
            <div style="text-align: center; color: var(--text-secondary); font-size: 14px;">
                <p>Bằng việc đăng nhập/đăng ký, bạn đồng ý với <a href="#" style="color: var(--primary-color);">Điều khoản sử dụng</a> và <a href="#" style="color: var(--primary-color);">Chính sách bảo mật</a> của chúng tôi.</p>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal" id="forgotPasswordModal">
        <div class="modal-content" style="max-width: 450px; padding: 40px;">
            <button class="modal-close" onclick="document.getElementById('forgotPasswordModal').classList.remove('active')">×</button>
            <h2 style="text-align: center; margin-bottom: 20px; color: var(--primary-color);">
                <i class="fas fa-key"></i> Quên mật khẩu
            </h2>
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 30px;">
                Nhập email của bạn để nhận hướng dẫn đặt lại mật khẩu
            </p>
            
            <form onsubmit="handleForgotPassword(event)">
                <div class="form-group">
                    <label for="forgotEmail">Email</label>
                    <input type="email" id="forgotEmail" name="email" required placeholder="nhap@email.com">
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer style="margin-top: auto;">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 Gaming Phone Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
    <script>
        function showForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.add('active');
        }
        
        async function handleForgotPassword(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch('../index.php?controller=user&action=forgotPassword', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    setTimeout(() => {
                        document.getElementById('forgotPasswordModal').classList.remove('active');
                        form.reset();
                    }, 2000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Forgot password error:', error);
                showNotification('Có lỗi xảy ra', 'error');
            }
        }
    </script>
</body>
</html>
