<?php
// Hàm chuyển hướng trang
function redirect_to(string $url): void {
    header("Location: $url");
    exit();
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function build_query_url(array $query, string $base = 'index.php'): string
{
    $queryString = http_build_query($query);
    if ($queryString === '') {
        return $base;
    }
    return $base . '?' . $queryString;
}

function format_vnd(mixed $amount): string
{
    $number = (int)($amount ?? 0);
    return number_format($number, 0, ',', '.') . ' ₫';
}

function condition_label(mixed $condition): string
{
    return match ((string)$condition) {
        'new' => 'Máy mới',
        'used' => 'Like New',
        default => 'Khác',
    };
}

function rating_stars(mixed $rating): string
{
    $value = max(0, min(5, (int)($rating ?? 0)));
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $value
            ? '<i class="fa-solid fa-star"></i>'
            : '<i class="fa-regular fa-star"></i>';
    }
    return $html;
}

function render_product_card(array $product, string $returnUrl = ''): void
{
    $id = (int)($product['id'] ?? 0);
    $name = $product['name'] ?? '';
    $brand = $product['brand'] ?? '';
    $price = $product['price'] ?? 0;
    $image = $product['image'] ?? '';
    $description = $product['description'] ?? '';
    $cpu = $product['cpu'] ?? '';
    $screen = $product['screen'] ?? '';
    $battery = $product['battery'] ?? '';
    $charger = $product['charger'] ?? '';
    $condition = $product['condition'] ?? '';
    $rating = $product['rating'] ?? 0;
    $quantity = (int)($product['quantity'] ?? 0);
    $isOutOfStock = $quantity <= 0;
    $detailUrl = build_query_url(['page' => 'detail', 'id' => $id]);

    $tagline = trim((string)$description);
    if ($tagline === '') {
        $tagline = 'Gaming phone cấu hình mạnh, phù hợp chơi game lâu.';
    }

    if (mb_strlen($tagline) > 92) {
        $tagline = mb_substr($tagline, 0, 92) . '...';
    }
    ?>
    <article class="product-card">
        <div class="product-media">
            <span class="product-badge"><?= e(condition_label($condition)) ?></span>
            <a href="<?= e($detailUrl) ?>">
                <img src="<?= e($image) ?>" alt="<?= e($name) ?>">
            </a>
        </div>
        <div class="product-body">
            <div class="product-meta">
                <span><?= e($brand) ?></span>
                <span class="stars"><?= rating_stars($rating) ?></span>
            </div>
            <h3><a href="<?= e($detailUrl) ?>"><?= e($name) ?></a></h3>
            <p class="product-tagline"><?= e($tagline) ?></p>
            <div class="product-specs">
                <span><i class="fa-solid fa-microchip"></i><?= e($cpu) ?></span>
                <span><i class="fa-solid fa-display"></i><?= e($screen) ?></span>
                <span><i class="fa-solid fa-battery-full"></i><?= e($battery) ?></span>
                <span><i class="fa-solid fa-bolt"></i><?= e($charger) ?></span>
            </div>
            <div class="product-bottom">
                <strong ><?= e(format_vnd($price)) ?></strong>
                <?php if ($isOutOfStock): ?>
                    <span class="stock-state stock-out">Hết hàng</span>
                <?php else: ?>
                    <span class="stock-state"><?= (int)$quantity ?> máy</span>
                <?php endif; ?>
            </div>
            <div class="product-actions">
                <a class="btn btn-primary" href="<?= e($detailUrl) ?>">Mua ngay</a>
                <form class="product-quick-form" method="post" action="index.php?page=cart&action=cart_add">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="<?= e($returnUrl) ?>">
                    <button class="btn btn-soft" type="submit" <?= $isOutOfStock ? 'disabled' : '' ?> title="Thêm vào giỏ">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </form>
            </div>
        </div>
    </article>
    <?php
}

// Hàm tạo thông báo Flash Session
function flash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

// Hàm yêu cầu đăng nhập
function require_user_login(): void {
    if (empty($_SESSION['user'])) {
        flash('error', 'Vui lòng đăng nhập để tiếp tục.');
        redirect_to('index.php?page=login');
    }
}

// Hàm giả lập tính toán giỏ hàng
function build_cart_rows($productModel): array {
    $cart = $_SESSION['cart'] ?? [];
    $items = [];
    foreach ($cart as $id => $quantity) {
        $product = $productModel->getById($id);
        if ($product) {
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'lineTotal' => $product['price'] * $quantity
            ];
        }
    }
    return $items;
}
?>
