<?php

declare(strict_types=1);

// KHỞI TẠO HỆ THỐNG & SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

// Cơ chế Autoload: Tự động phát hiện và nạp file khi Class được gọi đến (Không cần require thủ công)
spl_autoload_register(function (string $className) {
    $directories = ['Models', 'Controllers'];
    foreach ($directories as $dir) {
        $file = __DIR__ . "/{$dir}/{$className}.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

function render_view(string $view, array $data = []): void
{
    $path = __DIR__ . '/Views/' . $view;

    if (!file_exists($path)) {
        http_response_code(404);
        echo '<h3>Không tìm thấy file giao diện: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8') . '</h3>';
        exit;
    }
    include $path;
    exit;
}

// =========================================================================
// KHAI BÁO BẢN ĐỒ ĐƯỜNG DẪN (ROUTING MAPS)
// =========================================================================
$page = $_GET['page'] ?? 'home';
$productModel = new Product();
$result = null;

// Cấu trúc phẳng: 'tên_trang_trên_url' => ['Tên_Lớp_Controller', 'Tên_Phương_Thức']
$routes = [
    // --- Nhóm Frontend công khai ---
    'home'                 => ['FrontendController', 'home'],
    'shop'                 => ['FrontendController', 'shop'],
    'detail'               => ['FrontendController', 'detail'],

    // --- Nhóm Giỏ hàng ---
    'cart'                 => ['CartController', 'cart'],

    // --- Nhóm Quản trị Admin ---
    'admin_add_products'   => ['AdminController', 'addProduct'],
    'admin_products'       => ['AdminController', 'products'],
    'admin_product_delete' => ['AdminController', 'deleteProducts'],
    'admin_product_update' => ['AdminController', 'updateProduct'],
];

/**
 * Mảng chứa các trang giao diện tĩnh (Chưa viết hoặc chưa cần xử lý logic Controller)
 */
$simpleViews = [
    'login'           => 'frontend/login.php',
    'register'        => 'frontend/register.php',
    'forgot'          => 'frontend/forgot.php',
    'change_password' => 'frontend/change_password.php',
    'checkout'        => 'frontend/checkout.php',
    'admin_login'     => 'backend/login.php',
    'admin_dashboard' => 'backend/dashboard.php',
    'admin_orders'    => 'backend/orders.php',
    'admin_users'     => 'backend/users.php',
];

if (isset($routes[$page])) {
    [$controllerClass, $methodName] = $routes[$page];

    // Kiểm tra tính hợp lệ xem file Class có tồn tại nhờ Autoload không
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass($productModel);

        // Kiểm tra phương thức có tồn tại trong Controller không trước khi chạy
        if (method_exists($controllerInstance, $methodName)) {

            // Thực thi hàm động. Riêng trang detail cần bóc tách ID từ URL, các trang khác chạy rỗng
            $result = ($page === 'detail')
                ? $controllerInstance->$methodName((int)($_GET['id'] ?? 0))
                : $controllerInstance->$methodName();
        } else {
            http_response_code(500);
            die("<h3>Lỗi hệ thống: Phương thức '{$methodName}' không tồn tại trong lớp '{$controllerClass}'.</h3>");
        }
    } else {
        http_response_code(500);
        die("<h3>Lỗi hệ thống: Lớp '{$controllerClass}' không tồn tại trên hệ thống.</h3>");
    }
} elseif (isset($simpleViews[$page])) {
    // Nếu thuộc danh sách các trang view tĩnh, gán trực tiếp mảng rỗng dữ liệu
    $result = ['view' => $simpleViews[$page], 'data' => []];
} else {
    // Phản hồi lỗi không tìm thấy trang
    http_response_code(404);
    echo '<h3>Trang bạn tìm không tồn tại (404).</h3>';
    exit;
}

// Kiểm tra tính chuẩn mực của dữ liệu trả về từ các hàm nghiệp vụ
if (!is_array($result) || empty($result['view'])) {
    http_response_code(500);
    echo '<h3>Lỗi cấu trúc: Dữ liệu trả về từ Controller không đúng định dạng mảng MVC mẫu.</h3>';
    exit;
}

// Đảm bảo khóa 'data' luôn tồn tại dưới dạng mảng để tránh lỗi không mong muốn
$result['data'] ??= [];

// Trích xuất thông báo nhanh (Flash Message) nếu có từ Session sang View, sau đó xóa ngay để giải phóng
if (!empty($_SESSION['flash']) && is_array($_SESSION['flash'])) {
    $type = array_key_first($_SESSION['flash']);
    if ($type !== null) {
        $result['data']['flash'] = [
            'type'    => (string)$type,
            'message' => (string)($_SESSION['flash'][$type] ?? ''),
        ];
    }
    unset($_SESSION['flash']);
}

// Đóng gói mảng biến dùng chung truyền trực tiếp vào Header của toàn bộ các trang giao diện
$result['data']['page']        = $page;
$result['data']['currentUser'] = $_SESSION['user'] ?? null;
$result['data']['cartCount']   = function_exists('cart_items_count') ? cart_items_count() : 0;

// KÍCH HOẠT HÀM HIỂN THỊ GIAO DIỆN CHÍNH THỨC
render_view($result['view'], $result['data']);
