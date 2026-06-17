<?php
$product = $data['product'] ?? [];
$relatedProducts = array_filter($data['relatedProducts'] ?? [], fn($item) => (int)$item['id'] !== (int)($product['id'] ?? 0));
$hotlineLabel = '1900 9999';
$hotlineTel = '19009999';
$isOutOfStock = (int)($product['quantity'] ?? 0) <= 0;

// Hàm kiểm tra 1 giá trị có thực sự có dữ liệu để hiển thị hay không
$hasValue = static function (mixed $value): bool {
    if ($value === null) {
        return false;
    }

    $value = trim((string)$value);
    return $value !== '' && $value !== '0';
};

$highlightSpecs = array_values(array_filter([
    ['icon' => 'fa-solid fa-microchip', 'label' => 'CPU', 'value' => $product['cpu'] ?? ''],
    ['icon' => 'fa-solid fa-memory', 'label' => 'RAM', 'value' => $product['ram'] ?? ''],
    ['icon' => 'fa-solid fa-hard-drive', 'label' => 'ROM', 'value' => $product['rom'] ?? ''],
    ['icon' => 'fa-solid fa-display', 'label' => 'Màn hình', 'value' => $product['screen'] ?? ''],
    ['icon' => 'fa-solid fa-camera', 'label' => 'Camera', 'value' => $product['camera'] ?? ''],
    ['icon' => 'fa-solid fa-battery-full', 'label' => 'Pin', 'value' => $product['battery'] ?? ''],
    ['icon' => 'fa-solid fa-bolt', 'label' => 'Sạc nhanh', 'value' => $product['charger'] ?? ''],
    ['icon' => 'fa-solid fa-boxes-stacked', 'label' => 'Tồn kho', 'value' => (int)($product['quantity'] ?? 0) . ' máy'],
], fn($item) => $hasValue($item['value'])));

$overviewSpecs = array_values(array_filter([
    ['label' => 'Mã sản phẩm', 'value' => '#' . (int)($product['id'] ?? 0)],
    ['label' => 'Thương hiệu', 'value' => $product['brand'] ?? ''],
    ['label' => 'Tình trạng', 'value' => condition_label($product['condition'] ?? '')],
    ['label' => 'Giá bán', 'value' => format_vnd($product['price'] ?? 0)],
    ['label' => 'Đánh giá', 'value' => (int)($product['rating'] ?? 0) . '/5 sao'],
    ['label' => 'Đã bán', 'value' => number_format((int)($product['sales'] ?? 0)) . ' máy'],
    ['label' => 'Tồn kho', 'value' => (int)($product['quantity'] ?? 0) . ' máy'],
], fn($item) => $hasValue($item['value'])));

// Chia nhóm thông số đúng theo dữ liệu hiện có trong database để trang chi tiết hiển thị đầy đủ hơn
$specGroups = [
    'Thiết kế & màn hình' => array_values(array_filter([
        ['label' => 'Màn hình', 'value' => $product['screen'] ?? ''],
        ['label' => 'Tỷ lệ màn hình', 'value' => $product['screen_ratio'] ?? ''],
        ['label' => 'Công nghệ màn hình', 'value' => $product['screen_tech'] ?? ''],
        ['label' => 'Độ phân giải', 'value' => $product['screen_resolution'] ?? ''],
        ['label' => 'Kính bảo vệ', 'value' => $product['screen_glass'] ?? ''],
        ['label' => 'Vật liệu hoàn thiện', 'value' => $product['design_material'] ?? ''],
        ['label' => 'Kích thước', 'value' => $product['dimensions'] ?? ''],
        ['label' => 'Trọng lượng', 'value' => $product['weight'] ?? ''],
    ], fn($item) => $hasValue($item['value']))),
    'Camera' => array_values(array_filter([
        ['label' => 'Camera chính', 'value' => $product['camera'] ?? ''],
        ['label' => 'Số camera sau', 'value' => $hasValue($product['cam_rear_count'] ?? null) ? (string)$product['cam_rear_count'] . ' ống kính' : ''],
        ['label' => 'Tính năng camera sau', 'value' => $product['cam_rear_features'] ?? ''],
        ['label' => 'Quay phim camera sau', 'value' => $product['cam_rear_video'] ?? ''],
        ['label' => 'Camera trước', 'value' => $product['cam_front_specs'] ?? ''],
        ['label' => 'Quay phim camera trước', 'value' => $product['cam_front_video'] ?? ''],
        ['label' => 'Tính năng camera trước', 'value' => $product['cam_front_features'] ?? ''],
    ], fn($item) => $hasValue($item['value']))),
    'Hiệu năng & hệ điều hành' => array_values(array_filter([
        ['label' => 'CPU', 'value' => $product['cpu'] ?? ''],
        ['label' => 'Xung nhịp CPU', 'value' => $product['cpu_speed'] ?? ''],
        ['label' => 'GPU', 'value' => $product['gpu'] ?? ''],
        ['label' => 'RAM', 'value' => $product['ram'] ?? ''],
        ['label' => 'ROM', 'value' => $product['rom'] ?? ''],
        ['label' => 'Hệ điều hành', 'value' => $product['os'] ?? ''],
    ], fn($item) => $hasValue($item['value']))),
    'Pin, kết nối & tính năng' => array_values(array_filter([
        ['label' => 'Dung lượng pin', 'value' => $product['battery'] ?? ''],
        ['label' => 'Sạc nhanh', 'value' => $product['charger'] ?? ''],
        ['label' => 'Công nghệ sạc chi tiết', 'value' => $product['charging_tech'] ?? ''],
        ['label' => 'Mạng di động', 'value' => $product['network'] ?? ''],
        ['label' => 'SIM', 'value' => $product['sim'] ?? ''],
        ['label' => 'WiFi', 'value' => $product['wifi'] ?? ''],
        ['label' => 'Bluetooth', 'value' => $product['bluetooth'] ?? ''],
        ['label' => 'Cổng sạc / Kết nối', 'value' => $product['port_charging'] ?? ''],
        ['label' => 'Cổng tai nghe', 'value' => $product['port_audio'] ?? ''],
        ['label' => 'GPS', 'value' => $product['gps'] ?? ''],
        ['label' => 'Hỗ trợ thẻ nhớ', 'value' => $product['memory_card'] ?? ''],
        ['label' => 'Bảo mật', 'value' => $product['security'] ?? ''],
        ['label' => 'Chống nước / Bụi', 'value' => $product['water_resistance'] ?? ''],
        ['label' => 'Tính năng khác', 'value' => $product['extra_features'] ?? ''],
    ], fn($item) => $hasValue($item['value']))),
];

$visibleSpecGroups = array_filter($specGroups, fn($items) => !empty($items));
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product['name'] ?? 'Chi tiết sản phẩm') ?> - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="detail-section">
            <div class="container">
                <div class="detail-grid">
                    <div class="detail-gallery">
                        <div class="detail-gallery-top">
                            <span class="product-badge"><?= e(condition_label($product['condition'] ?? '')) ?></span>
                            <span class="detail-stock-chip <?= $isOutOfStock ? 'out' : '' ?>">
                                <?= $isOutOfStock ? 'Hết hàng' : 'Còn ' . (int)($product['quantity'] ?? 0) . ' máy' ?>
                            </span>
                        </div>

                        <img src="<?= e($product['image'] ?? '') ?>" alt="<?= e($product['name'] ?? '') ?>">

                        <div class="detail-gallery-stats">
                            <div>
                                <span>Đánh giá</span>
                                <strong><?= (int)($product['rating'] ?? 0) ?>/5</strong>
                            </div>
                            <div>
                                <span>Đã bán</span>
                                <strong><?= number_format((int)($product['sales'] ?? 0)) ?> máy</strong>
                            </div>
                            <div>
                                <span>Thương hiệu</span>
                                <strong><?= e($product['brand'] ?? '') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="detail-info">
                        <a href="javascript:history.back()" class="text-link"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                        <span class="eyebrow"><?= e($product['brand'] ?? '') ?></span>
                        <h1><?= e($product['name'] ?? '') ?></h1>

                        <div class="detail-rating">
                            <span class="stars"><?= rating_stars($product['rating'] ?? 0) ?></span>
                            <span><?= (int)($product['rating'] ?? 0) ?>/5 sao</span>
                            <span>Đã bán <?= number_format((int)($product['sales'] ?? 0)) ?> máy</span>
                        </div>

                        <div class="detail-price"><?= e(format_vnd($product['price'] ?? 0)) ?></div>

                        <?php if ($hasValue($product['description'] ?? '')): ?>
                            <div class="detail-description-box">
                                <h2>Mô tả sản phẩm</h2>
                                <p><?= e($product['description'] ?? '') ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($highlightSpecs): ?>
                            <div class="detail-highlight-grid">
                                <?php foreach ($highlightSpecs as $item): ?>
                                    <div class="detail-highlight-card">
                                        <i class="<?= e($item['icon']) ?>"></i>
                                        <span><?= e($item['label']) ?></span>
                                        <strong><?= e($item['value']) ?></strong>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($isOutOfStock): ?>
                            <div class="stock-alert">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>Sản phẩm này đang hết hàng. Bạn có thể xem mẫu khác cùng thương hiệu hoặc liên hệ cửa hàng để được báo lại khi có hàng.</span>
                            </div>
                        <?php else: ?>
                            <form method="post" action="index.php?page=cart_add" class="buy-box">
                                <input type="hidden" name="product_id" value="<?= (int)($product['id'] ?? 0) ?>">
                                <input type="hidden" name="redirect" value="index.php?page=detail&id=<?= (int)($product['id'] ?? 0) ?>">
                                <label>
                                    Số lượng
                                    <input type="number" name="quantity" min="1" max="<?= (int)($product['quantity'] ?? 0) ?>" value="1">
                                </label>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                                </button>
                                <a class="btn btn-soft" href="index.php?page=cart">Tới giỏ hàng</a>
                            </form>
                        <?php endif; ?>

                        <div class="detail-actions">
                            <?php if ($isOutOfStock): ?>
                                <span class="btn btn-disabled">
                                    <i class="fa-solid fa-ban"></i> Tạm hết hàng
                                </span>
                            <?php else: ?>
                                <a class="btn btn-primary" href="index.php?page=checkout&id=<?= (int)($product['id'] ?? 0) ?>">
                                    <i class="fa-solid fa-bag-shopping"></i> Mua ngay
                                </a>
                            <?php endif; ?>
                            <a class="btn btn-soft" href="tel:<?= e($hotlineTel) ?>">
                                <i class="fa-solid fa-phone"></i> Tư vấn: <?= e($hotlineLabel) ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="detail-data-grid">
                    <section class="detail-data-card">
                        <div class="section-heading detail-data-head">
                            <div>
                                <span class="eyebrow">Tổng quan</span>
                                <h2>Thông tin chung</h2>
                            </div>
                        </div>

                        <div class="detail-info-list">
                            <?php foreach ($overviewSpecs as $item): ?>
                                <div class="detail-info-row">
                                    <span><?= e($item['label']) ?></span>
                                    <strong><?= e($item['value']) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <?php if ($visibleSpecGroups): ?>
                        <section class="detail-data-card detail-data-card-wide">
                            <div class="section-heading detail-data-head">
                                <div>
                                    <span class="eyebrow">Chi tiết cấu hình</span>
                                    <h2>Toàn bộ thông số sản phẩm</h2>
                                </div>
                            </div>

                            <div class="detail-spec-groups">
                                <?php foreach ($visibleSpecGroups as $groupTitle => $items): ?>
                                    <article class="detail-spec-card">
                                        <h3><?= e($groupTitle) ?></h3>

                                        <div class="detail-info-list">
                                            <?php foreach ($items as $item): ?>
                                                <div class="detail-info-row">
                                                    <span><?= e($item['label']) ?></span>
                                                    <strong><?= e($item['value']) ?></strong>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
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
                            <?php render_product_card($related, 'index.php?page=detail&id=' . (int)($product['id'] ?? 0)); ?>
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
