<?php
$cartRows = $data['cartRows'] ?? [];
$cartTotal = (int)($data['cartTotal'] ?? 0);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Gaming Phone Store</title>
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
                <span class="eyebrow">Giỏ hàng PHP</span>
                <h1>Sản phẩm bạn đã chọn</h1>
            </div>
            <a class="btn btn-soft" href="index.php?page=shop"><i class="fa-solid fa-arrow-left"></i> Tiếp tục mua</a>
        </div>
    </section>

    <section class="section-block">
        <div class="container cart-layout">
            <?php if ($cartRows): ?>
                <form method="post" action="index.php?page=cart&action=cart_update" class="cart-panel">
                    <div class="cart-table-wrap table-responsive">
                        <table class="cart-table">
                            <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Tạm tính</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cartRows as $row): ?>
                                <?php $product = $row['product']; ?>
                                <tr>
                                    <td>
                                        <div class="cart-product">
                                            <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                                            <div>
                                                <strong><?= e($product['name']) ?></strong>
                                                <span><?= e($product['brand']) ?> - <?= e(condition_label($product['condition'])) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= e(format_vnd($product['price'])) ?></td>
                                    <td>
                                        <input class="qty-input form-control" type="number" name="quantities[<?= (int)$product['id'] ?>]" min="1" max="<?= (int)$product['quantity'] ?>" value="<?= (int)$row['quantity'] ?>">
                                    </td>
                                    <td><strong><?= e(format_vnd($row['lineTotal'])) ?></strong></td>
                                    <td>
                                        <button type="submit" form="remove-<?= (int)$product['id'] ?>" class="icon-action danger" title="Xóa sản phẩm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="cart-actions">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-rotate"></i> Cập nhật giỏ hàng</button>
                    </div>
                </form>

                <?php foreach ($cartRows as $row): ?>
                    <form id="remove-<?= (int)$row['product']['id'] ?>" method="post" action="index.php?page=cart&action=cart_remove">
                        <input type="hidden" name="product_id" value="<?= (int)$row['product']['id'] ?>">
                    </form>
                <?php endforeach; ?>

                <aside class="summary-panel reveal-on-scroll">
                    <h2>Tổng thanh toán</h2>
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <strong><?= e(format_vnd($cartTotal)) ?></strong>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <strong>Miễn phí</strong>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng</span>
                        <strong><?= e(format_vnd($cartTotal)) ?></strong>
                    </div>
                    <a class="btn btn-primary w-100" href="index.php?page=checkout"><i class="fa-solid fa-credit-card"></i> Thanh toán</a>
                    <form method="post" action="index.php?page=cart&action=cart_clear">
                        <button class="btn btn-soft w-100" type="submit">Làm trống giỏ hàng</button>
                    </form>
                </aside>
            <?php else: ?>
                <div class="empty-state wide">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h2>Giỏ hàng đang trống</h2>
                    <p>Hãy vào cửa hàng, chọn sản phẩm và thêm vào giỏ bằng nút giỏ hàng trên từng thẻ sản phẩm.</p>
                    <a class="btn btn-primary" href="index.php?page=shop">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>

