<?php
$deals = $data['deals'] ?? [];
$bestSellers = $data['bestSellers'] ?? [];
$hotProducts = $data['hotProducts'] ?? [];
$products = $data['products'] ?? [];
$pagination = $data['pagination'] ?? ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop'];
$hero = $deals[0] ?? $products[0] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Phone Store - Trang chủ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <section class="hero-banner">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">Gaming phone chính hãng</span>
                <h1>Chọn máy chiến game theo hiệu năng thật.</h1>
                <p>Lọc nhanh theo hãng, chip, giá và tình trạng máy. Danh sách sản phẩm bên dưới được lấy trực tiếp từ database của bạn.</p>
                <div class="hero-actions">
                    <a href="index.php?page=shop" class="btn btn-primary"><i class="fa-solid fa-store"></i> Vào cửa hàng</a>
                    <?php if ($hero): ?>
                        <a href="index.php?page=detail&id=<?= (int)$hero['id'] ?>" class="btn btn-soft"><i class="fa-solid fa-bolt"></i> Xem máy nổi bật</a>
                    <?php endif; ?>
                </div>
                <div class="trust-strip">
                    <span><strong>35+</strong> mẫu gaming</span>
                    <span><strong>5</strong> hãng chủ lực</span>
                    <span><strong>12T</strong> bảo hành</span>
                </div>
            </div>
            <?php if ($hero): ?>
                <div class="hero-product">
                    <span class="deal-chip">Deal đáng chú ý</span>
                    <img src="<?= e($hero['image']) ?>" alt="<?= e($hero['name']) ?>">
                    <div>
                        <span><?= e($hero['brand']) ?></span>
                        <h2><?= e($hero['name']) ?></h2>
                        <strong><?= e(format_vnd($hero['price'])) ?></strong>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($deals): ?>
        <section class="section-block">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Sản phẩm giá tốt</span>
                        <h2>Lựa chọn đang có giá tốt</h2>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-btn" type="button" data-slider-prev title="Trước"><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="slider-btn" type="button" data-slider-next title="Sau"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>

                <div class="deal-slider" data-slider>
                    <?php foreach ($deals as $index => $product): ?>
                        <article class="deal-slide <?= $index === 0 ? 'active' : '' ?>">
                            <div>
                                <span class="deal-chip"><?= e(condition_label($product['condition'])) ?></span>
                                <h3><?= e($product['name']) ?></h3>
                                <p><?= e($product['description']) ?></p>
                                <div class="deal-specs">
                                    <span><i class="fa-solid fa-microchip"></i><?= e($product['cpu']) ?></span>
                                    <span><i class="fa-solid fa-battery-full"></i><?= e($product['battery']) ?></span>
                                    <span><i class="fa-solid fa-bolt"></i><?= e($product['charger']) ?></span>
                                </div>
                                <a class="btn btn-primary" href="index.php?page=detail&id=<?= (int)$product['id'] ?>">Xem chi tiết</a>
                            </div>
                            <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<section class="section-block compact">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Bán chạy</span>
                <h2>Sản phẩm được mua nhiều</h2>
            </div>
            <a href="index.php?page=shop&sort=sales" class="text-link">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="product-grid">
            <?php foreach ($bestSellers as $product): ?>
                <?php render_product_card($product, 'index.php?page=home'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

    <section class="section-block tinted">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Đang hot</span>
                    <h2>Máy có đánh giá và doanh số cao</h2>
                </div>
                <a href="index.php?page=shop&sort=rating" class="text-link">Mở cửa hàng <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="product-grid">
                <?php foreach ($hotProducts as $product): ?>
                    <?php render_product_card($product, 'index.php?page=home'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section-block" id="product-list">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Danh sách sản phẩm</span>
                    <h2>Mới nhất trong kho</h2>
                </div>
                <a href="index.php?page=shop" class="btn btn-soft"><i class="fa-solid fa-sliders"></i> Lọc nâng cao</a>
            </div>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <?php render_product_card($product, 'index.php?page=home'); ?>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                    <a class="<?= $i === $pagination['current'] ? 'active' : '' ?>" href="index.php?page=<?= e($pagination['basePage']) ?>&p=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <section class="news-strip" id="news">
        <div class="container news-grid">
            <div>
                <span class="eyebrow">Tin tức</span>
                <h2>Cập nhật mẹo chọn gaming phone</h2>
            </div>
            <p>So sánh chip, tản nhiệt, pin và màn hình tần số quét cao sẽ được cập nhật trong module tin tức ở giai đoạn tiếp theo.</p>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>


