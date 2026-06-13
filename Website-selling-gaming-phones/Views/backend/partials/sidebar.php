<?php
$adminPage = $data['page'] ?? 'admin_dashboard';
$admin = $data['currentAdmin'] ?? null;
?>
<aside class="admin-sidebar">
    <a class="admin-brand" href="index.php?page=admin_dashboard">
        <span><i class="fa-solid fa-shield-halved"></i></span>
        Bảng quản trị
    </a>
    <div class="admin-profile">
        <strong><?= $admin ? e($admin['name']) : 'Khách' ?></strong>
        <span><?= $admin ? e($admin['username']) : 'Chưa đăng nhập' ?></span>
    </div>
    <nav>
        <a class="<?= $adminPage === 'admin_dashboard' ? 'active' : '' ?>" href="index.php?page=admin_dashboard">
            <i class="fa-solid fa-chart-pie"></i> Tổng quan
        </a>
        <a class="<?= $adminPage === 'admin_products' ? 'active' : '' ?>" href="index.php?page=admin_products">
            <i class="fa-solid fa-box"></i> Sản phẩm
        </a>
        <a class="<?= $adminPage === 'admin_orders' ? 'active' : '' ?>" href="index.php?page=admin_orders">
            <i class="fa-solid fa-clipboard-list"></i> Đơn hàng
        </a>
        <a class="<?= $adminPage === 'admin_users' ? 'active' : '' ?>" href="index.php?page=admin_users">
            <i class="fa-solid fa-users"></i> Người dùng
        </a>
    </nav>
    <div class="admin-sidebar-footer">
        <a href="index.php?page=home"><i class="fa-solid fa-house"></i> Về website</a>
        <a href="index.php?page=admin_logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>
</aside>