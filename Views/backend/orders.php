<?php
$orders = $data['orders'] ?? [];
$orderStats = $data['orderStats'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
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
                <span class="eyebrow">Thanh toán</span>
                <h1>Quản lý đơn hàng</h1>
            </div>
            <button class="btn btn-soft" type="button"><i class="fa-solid fa-receipt"></i> Tổng đơn: <?= number_format($orderStats['orderCount'] ?? 0) ?></button>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <section class="order-kanban">
            <article><span class="status-dot amber"></span><strong>Đơn mới</strong><p><?= number_format($orderStats['newOrderCount'] ?? 0) ?> đơn</p></article>
            <article><span class="status-dot blue"></span><strong>Đang giao</strong><p><?= number_format($orderStats['shippingOrderCount'] ?? 0) ?> đơn</p></article>
            <article><span class="status-dot green"></span><strong>Hoàn tất</strong><p><?= number_format($orderStats['completedOrderCount'] ?? 0) ?> đơn</p></article>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Danh sách đơn hàng</h2>
                <span><?= count($orders) ?> đơn</span>
            </div>
            <?php if ($orders): ?>
                <div class="admin-table-wrap table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Liên hệ</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Cập nhật</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= (int)$order['id'] ?></td>
                                <td>
                                    <strong><?= e($order['customer_name']) ?></strong>
                                    <span class="d-block text-muted small"><?= e($order['address']) ?></span>
                                </td>
                                <td><?= e($order['phone']) ?></td>
                                <td><?= (int)$order['item_count'] ?> sản phẩm</td>
                                <td><?= e(format_vnd($order['total_amount'])) ?></td>
                                <td><?= e(payment_method_label($order['payment_method'])) ?></td>
                                <td><span class="status-pill order-status <?= e($order['status']) ?>"><?= e(order_status_label($order['status'])) ?></span></td>
                                <td>
                                    <form class="inline-order-form" method="post" action="index.php?page=admin_orders&action=admin_order_update">
                                        <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                                        <select class="form-select form-select-sm" name="status">
                                            <?php foreach (['new', 'confirmed', 'shipping', 'completed', 'cancelled'] as $status): ?>
                                                <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= e(order_status_label($status)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-soft btn-sm" type="submit">Lưu</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-admin">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <h3>Chưa có đơn hàng trong hệ thống</h3>
                    <p>Khi người dùng thanh toán ở frontend, đơn hàng sẽ được lưu vào bảng orders và hiển thị tại đây để admin cập nhật giao hàng.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
