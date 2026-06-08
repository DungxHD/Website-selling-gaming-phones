<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng - Gaming Phone Store</title>
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
                
                <div class="search-bar">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Tìm kiếm điện thoại gaming..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </div>
                    <a href="../index.php?controller=home&action=cart" class="action-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                    </a>
                </div>
            </div>
        </div>
        
        <nav>
            <ul>
                <li><a href="../index.php">Trang chủ</a></li>
                <li><a href="" class="active">Cửa hàng</a></li>
                <li><a href="../index.php#featured">Sản phẩm nổi bật</a></li>
                <li><a href="../index.php#bestselling">Bán chạy nhất</a></li>
            </ul>
        </nav>
    </header>

    <!-- Shop Container -->
    <div class="shop-container">
        <!-- Filters Panel -->
        <aside class="filters-panel">
            <form id="filterForm" action="" method="GET">
                <div class="filter-section">
                    <h3><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h3>
                    
                    <div class="filter-option">
                        <label for="searchFilter">
                            <i class="fas fa-search"></i> Tìm theo tên
                        </label>
                        <input type="text" id="searchFilter" name="search" placeholder="Nhập tên sản phẩm..." style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 8px; margin-top: 5px;" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3><i class="fas fa-tag"></i> Khoảng giá</h3>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Từ" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>" min="0">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Đến" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" min="0">
                    </div>
                    <div style="margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                            <input type="checkbox" name="price_range" value="under_10m" <?php echo isset($_GET['price_range']) && $_GET['price_range'] === 'under_10m' ? 'checked' : ''; ?>>
                            Dưới 10 triệu
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; margin-top: 5px;">
                            <input type="checkbox" name="price_range" value="10m_20m" <?php echo isset($_GET['price_range']) && $_GET['price_range'] === '10m_20m' ? 'checked' : ''; ?>>
                            10 - 20 triệu
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; margin-top: 5px;">
                            <input type="checkbox" name="price_range" value="over_20m" <?php echo isset($_GET['price_range']) && $_GET['price_range'] === 'over_20m' ? 'checked' : ''; ?>>
                            Trên 20 triệu
                        </label>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3><i class="fas fa-box"></i> Tình trạng</h3>
                    <div class="filter-option">
                        <label>
                            <input type="radio" name="condition" value="" <?php echo !isset($_GET['condition']) || $_GET['condition'] === '' ? 'checked' : ''; ?>>
                            Tất cả
                        </label>
                    </div>
                    <div class="filter-option">
                        <label>
                            <input type="radio" name="condition" value="new" <?php echo isset($_GET['condition']) && $_GET['condition'] === 'new' ? 'checked' : ''; ?>>
                            Máy mới (New)
                        </label>
                    </div>
                    <div class="filter-option">
                        <label>
                            <input type="radio" name="condition" value="used" <?php echo isset($_GET['condition']) && $_GET['condition'] === 'used' ? 'checked' : ''; ?>>
                            Máy cũ (Like New)
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn" style="width: 100%;">
                    <i class="fas fa-check"></i> Áp dụng bộ lọc
                </button>
                <a href="" class="btn btn-outline" style="width: 100%; margin-top: 10px; text-align: center; display: block;">
                    <i class="fas fa-redo"></i> Đặt lại
                </a>
            </form>
        </aside>

        <!-- Products Area -->
        <main>
            <!-- Sort Bar -->
            <div class="sort-bar">
                <div class="results-count">
                    <strong><?php echo count($products); ?></strong> sản phẩm được tìm thấy
                </div>
                <div class="sort-options">
                    <label>Sắp xếp theo:</label>
                    <select onchange="sortProducts(this.value)">
                        <option value="best_selling" <?php echo (!isset($_GET['sort']) || $_GET['sort'] === 'best_selling') ? 'selected' : ''; ?>>Bán chạy nhất</option>
                        <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : ''; ?>>Giá: Thấp đến Cao</option>
                        <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : ''; ?>>Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                <?php 
                // Sample products data - Replace with database query when DB is ready
                $sampleProducts = [
                    ['id' => 1, 'name' => 'ASUS ROG Phone 7 Ultimate', 'brand' => 'ASUS', 'price' => 29990000, 'cpu' => 'Snapdragon 8 Gen 2', 'screen' => '6.78" AMOLED 165Hz', 'battery' => '6000mAh', 'charging_power' => '65W', 'image' => 'rog-phone-7.jpg', 'is_featured' => 1],
                    ['id' => 2, 'name' => 'RedMagic 8S Pro+', 'brand' => 'RedMagic', 'price' => 19990000, 'cpu' => 'Snapdragon 8 Gen 2', 'screen' => '6.8" AMOLED 120Hz', 'battery' => '6000mAh', 'charging_power' => '80W', 'image' => 'redmagic-8s.jpg', 'is_featured' => 1],
                    ['id' => 3, 'name' => 'Black Shark 5 Pro', 'brand' => 'Black Shark', 'price' => 14990000, 'cpu' => 'Snapdragon 8 Gen 1', 'screen' => '6.67" AMOLED 144Hz', 'battery' => '4650mAh', 'charging_power' => '120W', 'image' => 'black-shark-5.jpg', 'is_featured' => 1],
                    ['id' => 4, 'name' => 'Lenovo Legion Y90', 'brand' => 'Lenovo', 'price' => 17990000, 'cpu' => 'Snapdragon 8 Gen 1', 'screen' => '6.92" AMOLED 144Hz', 'battery' => '5600mAh', 'charging_power' => '68W', 'image' => 'legion-y90.jpg', 'is_featured' => 1],
                    ['id' => 5, 'name' => 'ASUS ROG Phone 6D Ultimate', 'brand' => 'ASUS', 'price' => 24990000, 'cpu' => 'Dimensity 9000+', 'screen' => '6.78" AMOLED 165Hz', 'battery' => '6000mAh', 'charging_power' => '65W', 'image' => 'rog-phone-6d.jpg', 'sold_count' => 1500],
                    ['id' => 6, 'name' => 'RedMagic 7S Pro', 'brand' => 'RedMagic', 'price' => 16990000, 'cpu' => 'Snapdragon 8+ Gen 1', 'screen' => '6.8" AMOLED 120Hz', 'battery' => '5000mAh', 'charging_power' => '135W', 'image' => 'redmagic-7s.jpg', 'sold_count' => 1200],
                ];
                
                // Use products from controller or sample data
                $displayProducts = !empty($products) ? $products : $sampleProducts;
                
                if (empty($displayProducts)): 
                ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-search" style="font-size: 80px; color: var(--text-light); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--text-primary); margin-bottom: 10px;">Không tìm thấy sản phẩm</h3>
                    <p style="color: var(--text-secondary);">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn</p>
                </div>
                <?php else: ?>
                    <?php foreach ($displayProducts as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                        <div class="product-image">
                            <?php if (isset($product['is_featured']) && $product['is_featured']): ?>
                                <span class="product-badge">HOT</span>
                            <?php elseif (isset($product['sold_count']) && $product['sold_count'] > 1000): ?>
                                <span class="product-badge" style="background: var(--success-color);">BESTSELLER</span>
                            <?php endif; ?>
                            <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/0066cc/ffffff?text=<?php echo urlencode($product['name']); ?>'">
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
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-gamepad"></i> GAMING PHONE</h3>
                    <p>Chuyên cung cấp điện thoại gaming chính hãng uy tín hàng đầu Việt Nam.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Liên kết nhanh</h3>
                    <ul>
                        <li><a href="../index.php">Trang chủ</a></li>
                        <li><a href="">Cửa hàng</a></li>
                        <li><a href="../index.php?controller=user&action=auth">Đăng nhập</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Hỗ trợ khách hàng</h3>
                    <ul>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Đổi trả & hoàn tiền</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Liên hệ</h3>
                    <ul>
                        <li><i class="fas fa-phone"></i> 1900 xxxx</li>
                        <li><i class="fas fa-envelope"></i> support@gamingphone.vn</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Gaming Phone Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Product Detail Modal -->
    <div class="modal" id="productModal">
        <div class="modal-content" id="modalContent"></div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
