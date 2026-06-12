<?php
$filters = $data['filters'] ?? [];
$brands = $data['brands'] ?? [];
$products = $data['products'] ?? [];
$pagination = $data['pagination'] ?? ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop', 'query' => []];
$isFilterOpen = !empty(array_filter($filters, fn($value) => $value !== '' && $value !== null));
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng - Gaming Phone Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="page-hero shop-hero">
            <div class="container page-hero-inner">
                <div>
                    <span class="eyebrow">Cửa hàng</span>
                    <h1>Danh sách gaming phone</h1>
                    <p>Bộ lọc chỉ hiển thị trong trang cửa hàng và khi người dùng chuyển sang trang danh sách sản phẩm.</p>
                </div>
                <button class="btn btn-primary" type="button" data-filter-toggle>
                    <i class="fa-solid fa-sliders"></i> Tìm kiếm nâng cao
                </button>
            </div>
        </section>

        <section class="filter-panel <?= $isFilterOpen ? 'open' : '' ?>" data-filter-panel>
            <div class="container">
                <form class="filter-form" action="index.php" method="get">
                    <input type="hidden" name="page" value="shop">
                    <input type="hidden" name="action" value="advanced_search">

                    <div class="field wide">
                        <label>Tên sản phẩm</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="search" name="name" value="<?= e($filters['name'] ?? '') ?>" placeholder="VD: ROG Phone, Snapdragon 8 Gen 3">
                        </div>
                    </div>

                    <div class="field">
                        <label>Hãng</label>
                        <select name="company">
                            <option value="">Tất cả hãng</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= e($brand) ?>" <?= ($filters['company'] ?? '') === $brand ? 'selected' : '' ?>><?= e($brand) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label>Tình trạng</label>
                        <select name="status">
                            <option value="">Tất cả</option>
                            <option value="new" <?= ($filters['status'] ?? '') === 'new' ? 'selected' : '' ?>>Máy mới</option>
                            <option value="used" <?= ($filters['status'] ?? '') === 'used' ? 'selected' : '' ?>>Like New</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Giá từ</label>
                        <input type="number" name="price_min" min="0" step="500000" value="<?= e($filters['price_min'] ?? '') ?>" placeholder="0">
                    </div>

                    <div class="field">
                        <label>Giá đến</label>
                        <input type="number" name="price_max" min="0" step="500000" value="<?= e($filters['price_max'] ?? '') ?>" placeholder="30000000">
                    </div>

                    <div class="field">
                        <label>Sắp xếp</label>
                        <select name="arrange">
                            <option value="">Mới nhất</option>
                            <option value="sales" <?= ($filters['arrange'] ?? '') === 'sales' ? 'selected' : '' ?>>Bán chạy</option>
                            <option value="rating" <?= ($filters['arrange'] ?? '') === 'rating' ? 'selected' : '' ?>>Đánh giá cao</option>
                            <option value="price_asc" <?= ($filters['arrange'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                            <option value="price_desc" <?= ($filters['arrange'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Áp dụng</button>
                        <a href="index.php?page=shop" class="btn btn-soft">Xóa lọc</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="section-block">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Kết quả</span>
                        <h2><?= count($products) ?> sản phẩm đang hiển thị</h2>
                    </div>
                    <a href="index.php?page=cart" class="text-link">Xem giỏ hàng <i class="fa-solid fa-cart-shopping"></i></a>
                </div>

                <?php if ($products): ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <?php render_product_card($product, build_query_url(['page' => 'shop'] + ($_GET ?? []))); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <h3>Không tìm thấy sản phẩm phù hợp</h3>
                        <p>Hãy thử bộ lọc khác hoặc quay lại danh sách sản phẩm đầy đủ.</p>
                        <a class="btn btn-primary" href="index.php?page=shop">Xem tất cả sản phẩm</a>
                    </div>
                <?php endif; ?>

                <div class="pagination">
                    <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                        <?php
                        $query = $pagination['query'] ?? [];
                        $query['page'] = 'shop';
                        $query['p'] = $i;
                        ?>
                        <a class="<?= $i === $pagination['current'] ? 'active' : '' ?>" href="<?= e(build_query_url($query)) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>