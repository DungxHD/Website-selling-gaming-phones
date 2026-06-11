<?php
$checkoutItems = $data['checkoutItems'] ?? [];
$checkoutTotal = (int)($data['checkoutTotal'] ?? 0);
$checkoutMode = $data['checkoutMode'] ?? 'cart';
$checkoutProductId = (int)($data['checkoutProductId'] ?? 0);
$currentUser = $data['currentUser'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <section class="page-hero">
        <div class="container page-hero-inner">
            <div>
                <span class="eyebrow">Thanh toán</span>
                <h1>Hoàn tất đơn hàng</h1>
                <p>Form này được xử lý bằng PHP và sẽ lưu thông tin vào bảng <code>orders</code> và <code>order_items</code>.</p>
            </div>
            <a class="btn btn-soft" href="<?= $checkoutMode === 'single' ? 'index.php?page=detail&id=' . $checkoutProductId : 'index.php?page=cart' ?>">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </section>

    <section class="section-block compact">
        <div class="container checkout-grid">
            <article class="checkout-summary reveal-on-scroll">
                <h2>Tóm tắt đơn hàng</h2>
                <?php foreach ($checkoutItems as $row): ?>
                    <?php $product = $row['product']; ?>
                    <div class="checkout-product">
                        <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                        <div>
                            <span class="eyebrow"><?= e($product['brand']) ?></span>
                            <h3><?= e($product['name']) ?></h3>
                            <p><?= e(product_tagline($product)) ?></p>
                            <div class="checkout-product-meta">
                                <span>SL: <?= (int)$row['quantity'] ?></span>
                                <strong><?= e(format_vnd($row['lineTotal'])) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="checkout-hint">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Đã nâng cấp ASM</strong>
                        <p>Đơn hàng sau khi thanh toán sẽ được admin xem và cập nhật trạng thái giao hàng trong backend.</p>
                    </div>
                </div>

                <div class="summary-total checkout-grand-total">
                    <span>Tổng cộng</span>
                    <strong><?= e(format_vnd($checkoutTotal)) ?></strong>
                </div>
            </article>

            <article class="checkout-form-card reveal-on-scroll">
                <h2>Thông tin giao hàng</h2>
                <form class="checkout-form" method="post" action="index.php?page=checkout&action=checkout_pay">
                    <input type="hidden" name="product_id" value="<?= $checkoutMode === 'single' ? $checkoutProductId : 0 ?>">

                    <label>Họ và tên
                        <input class="form-control" type="text" name="full_name" required value="<?= e($currentUser['name'] ?? '') ?>" placeholder="Nguyễn Văn A">
                    </label>

                    <label>Số điện thoại
                        <input class="form-control" type="tel" name="phone" required value="<?= e($currentUser['contact'] ?? '') ?>" placeholder="0xxxxxxxxx">
                    </label>

                    <label>Địa chỉ nhận hàng
                        <input class="form-control" type="text" name="address" required placeholder="Số nhà, đường, quận/huyện...">
                    </label>

                    <label>Phương thức thanh toán
                        <select class="form-select" name="payment_method" required>
                            <option value="cod">COD - Thanh toán khi nhận hàng</option>
                            <option value="bank">Chuyển khoản ngân hàng</option>
                            <option value="momo">Ví MoMo</option>
                        </select>
                    </label>

                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa-solid fa-credit-card"></i> Đặt hàng ngay
                    </button>
                </form>
            </article>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
