<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Gaming Phone Store</title>
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
                </div>
                <div>
                    <?php if (isset($_SESSION['user'])): ?>
                        <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></strong></span>
                        | <a href="../index.php?controller=user&action=logout">Đăng xuất</a>
                    <?php else: ?>
                        <a href="../index.php?controller=user&action=auth"><i class="fas fa-user"></i> Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="main-header">
            <div class="container">
                <a href="../index.php" class="logo">
                    <i class="fas fa-gamepad"></i>
                    <span>GAMING PHONE</span>
                </a>
                
                <div class="header-actions">
                    <a href="../index.php?controller=home&action=cart" class="action-btn" style="color: var(--primary-color);">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Cart Page -->
    <div class="cart-page">
        <div class="container">
            <h1 style="margin-bottom: 30px; color: var(--text-primary);">
                <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
            </h1>
            
            <?php if (isset($_SESSION['checkout_success']) && $_SESSION['checkout_success']): ?>
                <div class="notification success" style="position: static; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm.
                </div>
                <?php unset($_SESSION['checkout_success']); ?>
            <?php endif; ?>
            
            <?php if (empty($cartItems)): ?>
                <div class="cart-items">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Giỏ hàng trống</h3>
                        <p>Chưa có sản phẩm nào trong giỏ hàng của bạn</p>
                        <a href="../index.php?controller=home&action=shop" class="btn btn-primary">
                            <i class="fas fa-store"></i> Mua sắm ngay
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <?php foreach ($cartItems as $productId => $item): ?>
                        <div class="cart-item">
                            <img src="../assets/images/<?php echo $item['product']['image']; ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="cart-item-image" onerror="this.src='https://via.placeholder.com/100x100/0066cc/ffffff?text=Product'">
                            
                            <div class="cart-item-info">
                                <h4><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                                <div class="cart-item-price"><?php echo number_format($item['product']['price'], 0, ',', '.'); ?>₫</div>
                            </div>
                            
                            <div class="quantity-selector">
                                <button onclick="updateQuantity(<?php echo $productId; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['product']['stock']; ?>" onchange="updateQuantity(<?php echo $productId; ?>, this.value)">
                                <button onclick="updateQuantity(<?php echo $productId; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                            </div>
                            
                            <div class="cart-item-total">
                                <span class="total-price"><?php echo number_format($item['product']['price'] * $item['quantity'], 0, ',', '.'); ?>₫</span>
                            </div>
                            
                            <button class="remove-btn" onclick="removeFromCart(<?php echo $productId; ?>)" title="Xóa sản phẩm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3><i class="fas fa-receipt"></i> Tổng kết đơn hàng</h3>
                        
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Phí vận chuyển</span>
                            <span style="color: var(--success-color);">Miễn phí</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Giảm giá</span>
                            <span>0₫</span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Tổng cộng</span>
                            <span style="color: var(--primary-color);"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>
                        
                        <form action="../index.php?controller=home&action=checkout" method="POST">
                            <button type="submit" class="checkout-btn">
                                <i class="fas fa-check-circle"></i> Tiến hành thanh toán
                            </button>
                        </form>
                        
                        <div style="margin-top: 20px; text-align: center;">
                            <a href="../index.php?controller=home&action=shop" class="btn btn-outline" style="width: 100%;">
                                <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 Gaming Phone Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) {
                if (confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                    removeFromCart(productId);
                }
                return;
            }
            
            cartManager.updateCartItem(productId, quantity);
        }
        
        function removeFromCart(productId) {
            cartManager.removeFromCart(productId);
        }
    </script>
</body>
</html>
