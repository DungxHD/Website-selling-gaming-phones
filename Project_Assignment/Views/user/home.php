<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Phone Store - Trang chủ</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <?php if (isset($_SESSION['user'])): ?>
                        <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></strong></span>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            | <a href="index.php?controller=admin&action=dashboard" style="color: var(--accent-color);">Admin</a>
                        <?php endif; ?>
                        | <a href="index.php?controller=user&action=logout">Đăng xuất</a>
                    <?php else: ?>
                        <a href="index.php?controller=user&action=auth"><i class="fas fa-user"></i> Đăng nhập</a>
                        | <a href="index.php?controller=user&action=auth">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="main-header">
            <div class="container">
                <a href="index.php" class="logo">
                    <i class="fas fa-gamepad"></i>
                    <span>GAMING PHONE</span>
                </a>
                
                <div class="search-bar">
                    <form action="index.php?controller=home&action=shop" method="GET">
                        <input type="text" name="search" placeholder="Tìm kiếm điện thoại gaming...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </div>
                    <a href="index.php?controller=home&action=cart" class="action-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                    </a>
                </div>
            </div>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php" class="active">Trang chủ</a></li>
                <li><a href="index.php?controller=home&action=shop">Cửa hàng</a></li>
                <li><a href="#featured">Sản phẩm nổi bật</a></li>
                <li><a href="#bestselling">Bán chạy nhất</a></li>
                <li><a href="#newest">Mới nhất</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>ĐIỆN THOẠI GAMING<br>CHÍNH HÃNG GIÁ TỐT</h1>
                <p>Trải nghiệm chơi game đỉnh cao với những chiếc điện thoại gaming mạnh mẽ nhất. Cấu hình khủng, làm mát tối ưu, pin trâu suốt ngày dài.</p>
                <div class="hero-buttons">
                    <a href="index.php?controller=home&action=shop" class="btn btn-primary">
                        <i class="fas fa-store"></i> Mua ngay
                    </a>
                    <a href="#featured" class="btn btn-secondary">
                        <i class="fas fa-fire"></i> Xem sản phẩm hot
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero-phone.png" alt="Gaming Phone" onerror="this.src='https://via.placeholder.com/500x400/0066cc/ffffff?text=Gaming+Phone'">
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="section" id="featured">
        <div class="container">
            <div class="section-title">
                <h2>SẢN PHẨM NỔI BẬT</h2>
                <p>Những mẫu điện thoại gaming được yêu thích nhất</p>
            </div>
            
            <div class="products-grid">
                <?php 
                // Sample products data - Replace with database query when DB is ready
                $sampleProducts = [
                    ['id' => 1, 'name' => 'ASUS ROG Phone 7 Ultimate', 'brand' => 'ASUS', 'price' => 29990000, 'cpu' => 'Snapdragon 8 Gen 2', 'screen' => '6.78" AMOLED 165Hz', 'battery' => '6000mAh', 'charging_power' => '65W', 'image' => 'rog-phone-7.jpg', 'is_featured' => 1],
                    ['id' => 2, 'name' => 'RedMagic 8S Pro+', 'brand' => 'RedMagic', 'price' => 19990000, 'cpu' => 'Snapdragon 8 Gen 2', 'screen' => '6.8" AMOLED 120Hz', 'battery' => '6000mAh', 'charging_power' => '80W', 'image' => 'redmagic-8s.jpg', 'is_featured' => 1],
                    ['id' => 3, 'name' => 'Black Shark 5 Pro', 'brand' => 'Black Shark', 'price' => 14990000, 'cpu' => 'Snapdragon 8 Gen 1', 'screen' => '6.67" AMOLED 144Hz', 'battery' => '4650mAh', 'charging_power' => '120W', 'image' => 'black-shark-5.jpg', 'is_featured' => 1],
                    ['id' => 4, 'name' => 'Lenovo Legion Y90', 'brand' => 'Lenovo', 'price' => 17990000, 'cpu' => 'Snapdragon 8 Gen 1', 'screen' => '6.92" AMOLED 144Hz', 'battery' => '5600mAh', 'charging_power' => '68W', 'image' => 'legion-y90.jpg', 'is_featured' => 1],
                ];
                
                // Use sample data for demonstration (replace with $featuredProducts from controller when DB is ready)
                $products = isset($featuredProducts) && !empty($featuredProducts) ? $featuredProducts : $sampleProducts;
                
                foreach ($products as $product): 
                ?>
                <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                    <div class="product-image">
                        <span class="product-badge">HOT</span>
                        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/0066cc/ffffff?text=<?php echo urlencode($product['name']); ?>'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-specs">
                            <div class="spec-item">
                                <i class="fas fa-microchip"></i>
                                <span><?php echo htmlspecialchars($product['cpu']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-desktop"></i>
                                <span><?php echo htmlspecialchars($product['screen']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-battery-full"></i>
                                <span><?php echo htmlspecialchars($product['battery']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bolt"></i>
                                <span><?php echo htmlspecialchars($product['charging_power']); ?></span>
                            </div>
                        </div>
                        <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="event.stopPropagation(); cartManager.addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> Thêm
                            </button>
                            <button class="btn-buy-now" onclick="event.stopPropagation(); buyNow(<?php echo $product['id']; ?>)">
                                <i class="fas fa-bolt"></i> Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Best Selling Products -->
    <section class="section" id="bestselling" style="background: var(--bg-color);">
        <div class="container">
            <div class="section-title">
                <h2>SẢN PHẨM BÁN CHẠY NHẤT</h2>
                <p>Được khách hàng tin dùng và đánh giá cao</p>
            </div>
            
            <div class="products-grid">
                <?php 
                $bestSellingSample = [
                    ['id' => 5, 'name' => 'ASUS ROG Phone 6D Ultimate', 'brand' => 'ASUS', 'price' => 24990000, 'cpu' => 'Dimensity 9000+', 'screen' => '6.78" AMOLED 165Hz', 'battery' => '6000mAh', 'charging_power' => '65W', 'image' => 'rog-phone-6d.jpg', 'sold_count' => 1500],
                    ['id' => 6, 'name' => 'RedMagic 7S Pro', 'brand' => 'RedMagic', 'price' => 16990000, 'cpu' => 'Snapdragon 8+ Gen 1', 'screen' => '6.8" AMOLED 120Hz', 'battery' => '5000mAh', 'charging_power' => '135W', 'image' => 'redmagic-7s.jpg', 'sold_count' => 1200],
                    ['id' => 7, 'name' => 'Xiaomi Black Shark 4 Pro', 'brand' => 'Black Shark', 'price' => 12990000, 'cpu' => 'Snapdragon 888', 'screen' => '6.67" AMOLED 144Hz', 'battery' => '4500mAh', 'charging_power' => '120W', 'image' => 'black-shark-4.jpg', 'sold_count' => 980],
                    ['id' => 8, 'name' => 'Nubia RedMagic 6R', 'brand' => 'RedMagic', 'price' => 11990000, 'cpu' => 'Snapdragon 888', 'screen' => '6.67" AMOLED 144Hz', 'battery' => '4200mAh', 'charging_power' => '55W', 'image' => 'redmagic-6r.jpg', 'sold_count' => 850],
                ];
                
                $products = isset($bestSellingProducts) && !empty($bestSellingProducts) ? $bestSellingProducts : $bestSellingSample;
                
                foreach ($products as $product): 
                ?>
                <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                    <div class="product-image">
                        <span class="product-badge" style="background: var(--success-color);">BESTSELLER</span>
                        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/00cc66/ffffff?text=<?php echo urlencode($product['name']); ?>'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-specs">
                            <div class="spec-item">
                                <i class="fas fa-microchip"></i>
                                <span><?php echo htmlspecialchars($product['cpu']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-desktop"></i>
                                <span><?php echo htmlspecialchars($product['screen']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-battery-full"></i>
                                <span><?php echo htmlspecialchars($product['battery']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bolt"></i>
                                <span><?php echo htmlspecialchars($product['charging_power']); ?></span>
                            </div>
                        </div>
                        <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="event.stopPropagation(); cartManager.addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> Thêm
                            </button>
                            <button class="btn-buy-now" onclick="event.stopPropagation(); buyNow(<?php echo $product['id']; ?>)">
                                <i class="fas fa-bolt"></i> Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newest Products -->
    <section class="section" id="newest">
        <div class="container">
            <div class="section-title">
                <h2>SẢN PHẨM MỚI NHẤT</h2>
                <p>Cập nhật những mẫu điện thoại gaming mới ra mắt</p>
            </div>
            
            <div class="products-grid">
                <?php 
                $newestSample = [
                    ['id' => 9, 'name' => 'ASUS ROG Phone 8 Pro', 'brand' => 'ASUS', 'price' => 32990000, 'cpu' => 'Snapdragon 8 Gen 3', 'screen' => '6.78" LTPO AMOLED 165Hz', 'battery' => '5500mAh', 'charging_power' => '65W', 'image' => 'rog-phone-8.jpg', 'created_at' => '2024-01-15'],
                    ['id' => 10, 'name' => 'RedMagic 9 Pro+', 'brand' => 'RedMagic', 'price' => 21990000, 'cpu' => 'Snapdragon 8 Gen 3', 'screen' => '6.8" AMOLED 120Hz', 'battery' => '6500mAh', 'charging_power' => '165W', 'image' => 'redmagic-9.jpg', 'created_at' => '2024-01-10'],
                    ['id' => 11, 'name' => 'Lenovo Legion Y90 2024', 'brand' => 'Lenovo', 'price' => 19990000, 'cpu' => 'Snapdragon 8 Gen 3', 'screen' => '6.92" AMOLED 144Hz', 'battery' => '5600mAh', 'charging_power' => '68W', 'image' => 'legion-y90-2024.jpg', 'created_at' => '2024-01-05'],
                    ['id' => 12, 'name' => 'OnePlus 12 Gaming Edition', 'brand' => 'OnePlus', 'price' => 18990000, 'cpu' => 'Snapdragon 8 Gen 3', 'screen' => '6.82" LTPO AMOLED 120Hz', 'battery' => '5400mAh', 'charging_power' => '100W', 'image' => 'oneplus-12.jpg', 'created_at' => '2024-01-01'],
                ];
                
                $products = isset($newestProducts) && !empty($newestProducts) ? $newestProducts : $newestSample;
                
                foreach ($products as $product): 
                ?>
                <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                    <div class="product-image">
                        <span class="product-badge" style="background: var(--secondary-color);">NEW</span>
                        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/00ccff/ffffff?text=<?php echo urlencode($product['name']); ?>'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-specs">
                            <div class="spec-item">
                                <i class="fas fa-microchip"></i>
                                <span><?php echo htmlspecialchars($product['cpu']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-desktop"></i>
                                <span><?php echo htmlspecialchars($product['screen']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-battery-full"></i>
                                <span><?php echo htmlspecialchars($product['battery']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bolt"></i>
                                <span><?php echo htmlspecialchars($product['charging_power']); ?></span>
                            </div>
                        </div>
                        <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="event.stopPropagation(); cartManager.addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> Thêm
                            </button>
                            <button class="btn-buy-now" onclick="event.stopPropagation(); buyNow(<?php echo $product['id']; ?>)">
                                <i class="fas fa-bolt"></i> Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-gamepad"></i> GAMING PHONE</h3>
                    <p>Chuyên cung cấp điện thoại gaming chính hãng uy tín hàng đầu Việt Nam. Cam kết chất lượng, bảo hành đầy đủ.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Liên kết nhanh</h3>
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="index.php?controller=home&action=shop">Cửa hàng</a></li>
                        <li><a href="index.php?controller=user&action=auth">Đăng nhập</a></li>
                        <li><a href="index.php?controller=home&action=cart">Giỏ hàng</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Hỗ trợ khách hàng</h3>
                    <ul>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Đổi trả & hoàn tiền</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Liên hệ</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li><i class="fas fa-phone"></i> 1900 xxxx</li>
                        <li><i class="fas fa-envelope"></i> support@gamingphone.vn</li>
                        <li><i class="fas fa-clock"></i> 8:00 - 22:00 (Tất cả các ngày)</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Gaming Phone Store. All rights reserved. Designed with <i class="fas fa-heart" style="color: var(--danger-color);"></i> for gamers</p>
            </div>
        </div>
    </footer>

    <!-- Product Detail Modal -->
    <div class="modal" id="productModal">
        <div class="modal-content" id="modalContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
