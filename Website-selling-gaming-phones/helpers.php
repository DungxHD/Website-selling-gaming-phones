<?php

declare(strict_types=1);

// =========================================================================
// PHẦN 1: TIỆN ÍCH HỆ THỐNG & BẢO MẬT (SYSTEM & SECURITY)
// =========================================================================

/**
 * Hàm chống XSS (Cross-Site Scripting) tuyệt đối.
 * Bắt buộc bọc mọi biến in ra View bằng hàm này: e($biến)
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Chuyển hướng trình duyệt và dừng thực thi code ngay lập tức.
 */
function redirect_to(string $url): never
{
    header("Location: $url");
    exit();
}

/**
 * Tạo nhanh thông báo Flash Session lưu trữ tạm thời 1 lần.
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

/**
 * Cửa trạm kiểm soát người dùng: Yêu cầu đăng nhập.
 */
function require_user_login(): void
{
    if (empty($_SESSION['user'])) {
        flash('error', 'Vui lòng đăng nhập để tiếp tục thao tác.');
        redirect_to('index.php?page=login');
    }
}

// =========================================================================
// PHẦN 2: XỬ LÝ ĐỊNH DẠNG & LOGIC HIỂN THỊ (FORMATTING & LOGIC)
// =========================================================================

/**
 * Định dạng số tiền sang chuẩn Việt Nam Đồng.
 * VD: 1500000 -> 1.500.000 ₫
 */
function format_vnd(mixed $amount): string
{
    return number_format((int)($amount ?? 0), 0, ',', '.') . ' ₫';
}

/**
 * Build chuỗi URL với mảng tham số GET sạch sẽ, tự động xử lý ký tự đặc biệt.
 */
function build_query_url(array $query, string $base = 'index.php'): string
{
    $queryString = http_build_query($query);
    return $queryString === '' ? $base : "{$base}?{$queryString}";
}

/**
 * Dịch trạng thái tình trạng máy sang tiếng Việt.
 */
function condition_label(mixed $condition): string
{
    return match ((string)$condition) {
        'new'  => 'Máy mới',
        'used' => 'Like New',
        default => 'Khác',
    };
}

/**
 * Dịch trạng thái đơn hàng dùng cho trang Quản trị (Admin)
 */
function order_status_label(string $status): string
{
    return match ($status) {
        'pending'   => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'shipping'  => 'Đang giao hàng',
        'completed' => 'Hoàn tất',
        'cancelled' => 'Đã hủy',
        default     => 'Không xác định',
    };
}

/**
 * Lấy class CSS tương ứng màu sắc với từng trạng thái đơn hàng.
 */
function order_status_color(string $status): string
{
    return match ($status) {
        'completed'           => 'green',
        'shipping', 'confirmed' => 'blue',
        'cancelled'           => 'red',
        default               => 'amber',
    };
}

// =========================================================================
// PHẦN 3: XỬ LÝ NGHIỆP VỤ GIỎ HÀNG (CART BUSINESS LOGIC)
// =========================================================================

/**
 * Tính tổng số lượng máy đang nằm trong giỏ hàng.
 */
function cart_items_count(): int
{
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        return (int)array_sum($_SESSION['cart']);
    }
    return 0;
}

/**
 * Giả lập kết nối lấy chi tiết giỏ hàng.
 * (Lưu ý: Nếu CartController đã xử lý, hàm này có thể được gỡ bỏ để code gọn hơn).
 */
function build_cart_rows(object $productModel): array
{
    $cart = $_SESSION['cart'] ?? [];
    $items = [];

    foreach ($cart as $id => $quantity) {
        $product = $productModel->getById((int)$id);
        if ($product) {
            $items[] = [
                'product'   => $product,
                'quantity'  => $quantity,
                'lineTotal' => $product['price'] * $quantity
            ];
        }
    }
    return $items;
}

// =========================================================================
// PHẦN 4: GIAO DIỆN COMPONENT (UI COMPONENTS & RENDERERS)
// =========================================================================

/**
 * Tạo dải sao đánh giá tự động mà KHÔNG CẦN dùng vòng lặp FOR.
 * Hàm str_repeat của PHP chạy nhanh hơn nhiều so với việc lặp thủ công.
 */
function rating_stars(mixed $rating): string
{
    $value = max(0, min(5, (int)($rating ?? 0)));
    $emptyValue = 5 - $value;

    return str_repeat('<i class="fa-solid fa-star"></i>', $value) .
        str_repeat('<i class="fa-regular fa-star"></i>', $emptyValue);
}

 /* Hàm dịch phương thức thanh toán từ tiếng Anh sang tiếng Việt
 */
function payment_method_label(string $method): string
{
    return match ($method) {
        'cod'    => 'Thanh toán khi nhận hàng (COD)',
        'bank'   => 'Chuyển khoản ngân hàng',
        'momo'   => 'Ví MoMo',
        'zalopay'=> 'ZaloPay',
        default  => 'Phương thức khác',
    };
}
/**
 * Khối component thẻ sản phẩm (Tái sử dụng ở Trang chủ, Cửa hàng, Chi tiết).
 */
function render_product_card(array $product, string $returnUrl = ''): void
{
    // Bóc tách dữ liệu an toàn bằng Null Coalescing Operator
    $id        = (int)($product['id'] ?? 0);
    $price     = (int)($product['price'] ?? 0);
    $quantity  = (int)($product['quantity'] ?? 0);

    // Các chuỗi văn bản
    $name      = (string)($product['name'] ?? 'Sản phẩm');
    $brand     = (string)($product['brand'] ?? 'Khác');
    $image     = (string)($product['image'] ?? 'assets/images/default.jpg');
    $cpu       = (string)($product['cpu'] ?? '-');
    $screen    = (string)($product['screen'] ?? '-');
    $battery   = (string)($product['battery'] ?? '-');
    $charger   = (string)($product['charger'] ?? '-');
    $condition = (string)($product['condition'] ?? '');
    $rating    = (int)($product['rating'] ?? 0);

    $isOutOfStock = $quantity <= 0;
    $detailUrl    = build_query_url(['page' => 'detail', 'id' => $id]);

    // Xử lý mô tả rút gọn (Cắt chuỗi thông minh không làm vỡ từ)
    $tagline = trim((string)($product['description'] ?? ''));
    $tagline = $tagline === '' ? 'Gaming phone cấu hình mạnh, phù hợp chơi game lâu.' : $tagline;
    $tagline = mb_strlen($tagline) > 92 ? mb_substr($tagline, 0, 92) . '...' : $tagline;

    // Ép thoát HTML 1 lần trước khi in ra View (Tránh gọi hàm e() quá nhiều lần trong HTML)
    $safeName    = e($name);
    $safeImage   = e($image);
    $safeUrl     = e($detailUrl);
?>
    <article class="product-card">
        <div class="product-media">
            <span class="product-badge"><?= e(condition_label($condition)) ?></span>
            <a href="<?= $safeUrl ?>">
                <img src="<?= $safeImage ?>" alt="<?= $safeName ?>" loading="lazy">
            </a>
        </div>

        <div class="product-body">
            <div class="product-meta">
                <span><?= e($brand) ?></span>
                <span class="stars"><?= rating_stars($rating) ?></span>
            </div>

            <h3><a href="<?= $safeUrl ?>"><?= $safeName ?></a></h3>
            <p class="product-tagline"><?= e($tagline) ?></p>

            <div class="product-specs">
                <span><i class="fa-solid fa-microchip"></i> <?= e($cpu) ?></span>
                <span><i class="fa-solid fa-display"></i> <?= e($screen) ?></span>
                <span><i class="fa-solid fa-battery-full"></i> <?= e($battery) ?></span>
                <span><i class="fa-solid fa-bolt"></i> <?= e($charger) ?></span>
            </div>

            <div class="product-bottom">
                <strong><?= format_vnd($price) ?></strong>
                <?php if ($isOutOfStock): ?>
                    <span class="stock-state stock-out">Hết hàng</span>
                <?php else: ?>
                    <span class="stock-state"><?= $quantity ?> máy</span>
                <?php endif; ?>
            </div>

            <div class="product-actions">
                <a class="btn btn-primary" href="<?= $safeUrl ?>">Mua ngay</a>
                <form class="product-quick-form" method="post" action="index.php?page=cart&action=cart_add">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="<?= e($returnUrl) ?>">
                    <button class="btn btn-soft" type="submit" <?= $isOutOfStock ? 'disabled' : '' ?> title="Thêm vào giỏ hàng">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </form>
            </div>
        </div>
    </article>
<?php
}

// =========================================================================
// PHẦN 5: XỬ LÝ UPLOAD FILE (FILE SYSTEM)
// =========================================================================

/**
 * Xử lý Upload ảnh sản phẩm an toàn với cơ chế chặn Malware cơ bản.
 * Trả về đường dẫn lưu file hoặc false nếu thất bại.
 */
function upload_product_image(string $inputName = 'image'): string|false
{
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return false;
    }

    if ($_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash']['error'] = 'Có lỗi xảy ra trong quá trình truyền tải file!';
        return false;
    }

    $file = $_FILES[$inputName];

    // Giới hạn 5MB
    if ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['flash']['error'] = 'Ảnh quá lớn! Hệ thống chỉ cho phép tối đa 5MB.';
        return false;
    }

    // Kiểm tra MIME Type thực tế (Chống file hack đổi đuôi thành .jpg)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedTypes, true)) {
        $_SESSION['flash']['error'] = 'Định dạng ảnh không hợp lệ! Chỉ chấp nhận JPG, PNG, GIF, WEBP.';
        return false;
    }

    // Xử lý tạo tên file duy nhất tránh trùng lặp
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = sprintf('product_%s_%s.%s', time(), bin2hex(random_bytes(4)), $extension);

    $uploadDir = __DIR__ . '/uploads/products/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        $_SESSION['flash']['error'] = 'Hệ thống không thể tạo thư mục lưu trữ!';
        return false;
    }

    $targetPath = $uploadDir . $newFileName;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'uploads/products/' . $newFileName; // Trả về chuỗi lưu vào Database
    }

    $_SESSION['flash']['error'] = 'Không thể di chuyển file vào hệ thống máy chủ!';
    return false;
}
