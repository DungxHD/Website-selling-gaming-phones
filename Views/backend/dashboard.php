<?php
$stats = $data['stats'] ?? [];
$topProducts = $data['topProducts'] ?? [];
$latestOrders = $data['latestOrders'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
<div class="admin-layout">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-top">
            <div>
                <span class="eyebrow">Bảng điều khiển</span>
                <h1>Thống kê tổng quan</h1>
            </div>
            <a href="index.php?page=admin_products" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Quản lý sản phẩm</a>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <section class="admin-stats">
            <article><span><i class="fa-solid fa-box"></i></span><div><strong><?= number_format($stats['productCount'] ?? 0) ?></strong><p>Sản phẩm</p></div></article>
            <article><span><i class="fa-solid fa-warehouse"></i></span><div><strong><?= number_format($stats['totalStock'] ?? 0) ?></strong><p>Tổng tồn kho</p></div></article>
            <article><span><i class="fa-solid fa-users"></i></span><div><strong><?= number_format($stats['userCount'] ?? 0) ?></strong><p>Người dùng</p></div></article>
            <article><span><i class="fa-solid fa-cart-flatbed-suitcase"></i></span><div><strong><?= number_format($stats['orderCount'] ?? 0) ?></strong><p>Đơn hàng</p></div></article>
            <article><span><i class="fa-solid fa-truck"></i></span><div><strong><?= number_format($stats['shippingOrderCount'] ?? 0) ?></strong><p>Đang giao</p></div></article>
            <article><span><i class="fa-solid fa-circle-check"></i></span><div><strong><?= number_format($stats['completedOrderCount'] ?? 0) ?></strong><p>Hoàn tất</p></div></article>
            <article><span><i class="fa-solid fa-user-lock"></i></span><div><strong><?= number_format($stats['inactiveUserCount'] ?? 0) ?></strong><p>Tài khoản khóa</p></div></article>
            <article><span><i class="fa-solid fa-coins"></i></span><div><strong><?= e(format_vnd($stats['revenue'] ?? 0)) ?></strong><p>Doanh thu</p></div></article>
        </section>

        <section class="admin-grid">
            <div class="admin-card">
                <div class="admin-card-head">
                    <h2>Sản phẩm bán chạy</h2>
                    <a href="index.php?page=shop&sort=sales">Xem ngoài website</a>
                </div>
                <div class="admin-table-wrap table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>Sản phẩm</th><th>Hãng</th><th>Giá</th><th>Đã bán</th><th>Kho</th></tr></thead>
                        <tbody>
                        <?php foreach ($topProducts as $product): ?>
                            <tr>
                                <td>
                                    <div class="admin-product-cell">
                                        <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                                        <strong><?= e($product['name']) ?></strong>
                                    </div>
                                </td>
                                <td><?= e($product['brand']) ?></td>
                                <td><?= e(format_vnd($product['price'])) ?></td>
                                <td><?= number_format((int)$product['sales']) ?></td>
                                <td><?= (int)$product['quantity'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="admin-card">
                <div class="admin-card-head">
                    <h2>Đơn hàng mới nhất</h2>
                    <a href="index.php?page=admin_orders">Mở danh sách</a>
                </div>
                <div class="status-list">
                    <?php foreach ($latestOrders as $order): ?>
                        <div>
                            <span class="status-dot <?= in_array($order['status'], ['completed'], true) ? 'green' : (in_array($order['status'], ['shipping', 'confirmed'], true) ? 'blue' : 'amber') ?>"></span>
                            <strong>DH#<?= (int)$order['id'] ?> - <?= e($order['customer_name']) ?></strong>
                            <p><?= (int)$order['item_count'] ?> sản phẩm | <?= e(format_vnd($order['total_amount'])) ?> | <?= e(order_status_label($order['status'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$latestOrders): ?>
                        <div>
                            <span class="status-dot amber"></span>
                            <strong>Chưa có đơn hàng</strong>
                            <p>Khi người dùng thanh toán, đơn hàng sẽ hiển thị tại đây.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
