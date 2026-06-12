<?php

$product = $data['product'] ?? '';
$relatedProducts = array_filter($data['relatedProducts'] ?? [], fn($item) => (int)$item['id'] !== (int)$product['id']);
$hotlineLabel = '1900 9999';
$hotlineTel = '19009999';
$isOutOfStock = (int)($product['quantity'] ?? 0) <= 0;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product['name']) ?> - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="detail-section">
            <div class="container detail-grid">
                <div class="detail-gallery">
                    <span class="product-badge"><?= e(condition_label($product['condition'])) ?></span>
                    <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                </div>
                <div class="detail-info">
                    <a href="javascript:history.back()" class="text-link"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                    <span class="eyebrow"><?= e($product['brand']) ?></span>
                    <h1><?= e($product['name']) ?></h1>
                    <div class="detail-rating">
                        <span class="stars"><?= rating_stars($product['rating']) ?></span>
                        <span>Đã bán <?= number_format((int)$product['sales']) ?> máy</span>
                    </div>
                    <div class="detail-price"><?= e(format_vnd($product['price'])) ?></div>
                    <p><?= e($product['description']) ?></p>

                    <div class="spec-grid">
                        <div><i class="fa-solid fa-microchip"></i><span>CPU</span><strong><?= e($product['cpu']) ?></strong></div>
                        <div><i class="fa-solid fa-display"></i><span>Màn hình</span><strong><?= e($product['screen']) ?></strong></div>
                        <div><i class="fa-solid fa-battery-full"></i><span>Pin</span><strong><?= e($product['battery']) ?></strong></div>
                        <div><i class="fa-solid fa-bolt"></i><span>Sạc nhanh</span><strong><?= e($product['charger']) ?></strong></div>
                        <div><i class="fa-solid fa-boxes-stacked"></i><span>Tồn kho</span><strong><?= (int)$product['quantity'] ?> máy</strong></div>
                        <div><i class="fa-solid fa-shield"></i><span>Bảo hành</span><strong>12 tháng</strong></div>
                    </div>

                    <?php if ($isOutOfStock): ?>
                        <div class="stock-alert">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Sản phẩm này đang hết hàng. Bạn có thể xem mẫu khác hoặc chờ admin cập nhật lại tồn kho.</span>
                        </div>
                    <?php else: ?>
                        <form method="post" action="index.php?page=cart&action=cart_add" class="buy-box">
                            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                            <input type="hidden" name="redirect" value="index.php?page=detail&id=<?= (int)$product['id'] ?>">
                            <label>
                                Số lượng
                                <input type="number" name="quantity" min="1" max="<?= (int)$product['quantity'] ?>" value="1">
                            </label>
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng</button>
                            <a class="btn btn-soft" href="index.php?page=cart">Tới giỏ hàng</a>
                        </form>
                    <?php endif; ?>

                    <div class="detail-actions">
                        <?php if ($isOutOfStock): ?>
                            <span class="btn btn-disabled">
                                <i class="fa-solid fa-ban"></i> Tạm hết hàng
                            </span>
                        <?php else: ?>
                            <a class="btn btn-primary" href="index.php?page=checkout&id=<?= (int)$product['id'] ?>">
                                <i class="fa-solid fa-bag-shopping"></i> Mua ngay
                            </a>
                        <?php endif; ?>
                        <a class="btn btn-soft" href="tel:<?= e($hotlineTel) ?>">
                            <i class="fa-solid fa-phone"></i> Tư vấn: <?= e($hotlineLabel) ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <?php if ($relatedProducts): ?>
            <section class="section-block tinted">
                <div class="container">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">Cùng thương hiệu</span>
                            <h2>Sản phẩm liên quan</h2>
                        </div>
                    </div>
                    <div class="product-grid">
                        <?php foreach (array_slice($relatedProducts, 0, 4) as $related): ?>
                            <?php render_product_card($related, 'index.php?page=detail&id=' . (int)$product['id']); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>