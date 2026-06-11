<?php
$currentPage = $data['page'] ?? 'home';
$cartCount = (int)($data['cartCount'] ?? 0);
$currentUser = $data['currentUser'] ?? null;
$searchValue = $_GET['q'] ?? '';
?>
<header class="site-header">
    <div class="topbar">
        <div class="container topbar-inner">
            <span><i class="fa-solid fa-shield-halved"></i> Bảo hành 12 tháng, kiểm tra máy trực tiếp</span>
            <span><i class="fa-solid fa-truck-fast"></i> Giao nhanh nội thành trong 2 giờ</span>
        </div>
    </div>
    <div class="container header-main">
        <a href="index.php?page=home" class="brand-logo" aria-label="Gaming Phone Store">
            <span class="brand-mark"><i class="fa-solid fa-gamepad"></i></span>
            <span>Gaming<span>Phone</span></span>
        </a>

        <form class="header-search" action="index.php" method="get">
            <input type="hidden" name="page" value="shop">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="q" value="<?= e($searchValue) ?>" placeholder="Tìm theo tên sản phẩm, hãng, chip...">
            <button type="submit">Tìm</button>
        </form>

        <nav class="main-nav" aria-label="Menu chính">
            <a class="<?= $currentPage === 'home' ? 'active' : '' ?>" href="index.php?page=home">Trang chủ</a>
            <a class="<?= $currentPage === 'shop' ? 'active' : '' ?>" href="index.php?page=shop">Cửa hàng</a>
            <a href="#news">Tin tức</a>
            <a href="#contact">Liên hệ</a>
            <a href="#support">Hỗ trợ</a>
        </nav>

        <div class="header-actions">
            <button id="theme-toggle" class="icon-button" type="button" title="Đổi giao diện">
                <i class="fa-solid fa-moon"></i>
            </button>
            <a class="icon-button" href="index.php?page=cart" title="Giỏ hàng">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-count"><?= $cartCount ?></span>
            </a>
            <div class="account-menu">
                <button class="icon-button" type="button" title="Tài khoản">
                    <i class="fa-solid fa-user"></i>
                </button>
                <div class="account-dropdown">
                    <?php if ($currentUser): ?>
                        <strong><?= e($currentUser['name']) ?></strong>
                        <a href="index.php?page=change_password">Đổi mật khẩu</a>
                        <a href="index.php?page=logout">Đăng xuất</a>
                    <?php else: ?>
                        <a href="index.php?page=login">Đăng nhập</a>
                        <a href="index.php?page=register">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<?php if (!empty($data['flash'])): ?>
    <div class="container">
        <div class="flash flash-<?= e($data['flash']['type']) ?>">
            <?= e($data['flash']['message']) ?>
        </div>
    </div>
<?php endif; ?>
