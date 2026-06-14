<?php

declare(strict_types=1);

// =========================================================================
// 1. KHỞI TẠO HỆ THỐNG & CORE COMPONENTS
// =========================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

// Cơ chế Autoload: Khởi tạo lười biếng (Lazy loading) các Class Model/Controller
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

// =========================================================================
// 2. HÀM KẾT XUẤT GIAO DIỆN (VIEW RENDERER)
// =========================================================================
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
// 3. BẢN ĐỒ ĐỊNH TUYẾN (ROUTING MAPS)
// =========================================================================
$page = $_GET['page'] ?? $_GET['action'] ?? 'home';
$productModel = new Product();
$result = null;

// Cấu trúc: 'tên_trang_trên_url' => ['Tên_Controller', 'Tên_Phương_thức']
$routes = [
    // --- Frontend ---
    'home'                 => ['FrontendController', 'home'],
    'shop'                 => ['FrontendController', 'shop'],
    'detail'               => ['FrontendController', 'detail'],

    // --- Giỏ hàng ---
    'cart'                 => ['CartController', 'cart'],
    'cart_add'             => ['CartController', 'add'],

    // --- Xác thực User (Frontend) ---
    'login'                => ['AuthController', 'login'],
    'register'             => ['AuthController', 'register'],
    'logout'               => ['AuthController', 'logout'],
    'forgot'               => ['AuthController', 'forgotPassword'],
    'change_password'      => ['AuthController', 'changePassword'],

    // --- Xác thực (Auth Admin) ---
    'admin_login'          => ['AuthController', 'adminLogin'],
    'admin_logout'         => ['AuthController', 'adminLogout'],

    // --- Quản trị: Người dùng ---
    'admin_users'          => ['AuthController', 'adminUsers'],
    'admin_user_add'       => ['AuthController', 'adminUserAdd'],
    'admin_user_update'    => ['AuthController', 'adminUserUpdate'],

    // --- Quản trị: Sản phẩm & Dashboard ---
    'admin_dashboard'      => ['AdminController', 'dashboard'],
    'admin_add_products'   => ['AdminController', 'addProduct'],
    'admin_products'       => ['AdminController', 'products'],
    'admin_product_delete' => ['AdminController', 'deleteProducts'],
    'admin_product_update' => ['AdminController', 'updateProduct'],
];

// Các trang giao diện tĩnh (Chỉ hiển thị, không có Controller xử lý)
$simpleViews = [
    'checkout'        => 'frontend/checkout.php',
    'admin_orders'    => 'backend/orders.php',
];

// =========================================================================
// 4. TẦNG BẢO VỆ (MIDDLEWARE - ADMIN GUARD)
// =========================================================================
$adminProtectedPages = [
    'admin_dashboard',
    'admin_products',
    'admin_add_products',
    'admin_product_delete',
    'admin_product_update',
    'admin_users',
    'admin_user_add',
    'admin_user_update',
    'admin_orders',
    'admin_logout',
];
// =========================================================================
// 4.1. TẦNG BẢO VỆ CHO USER (USER GUARD)
// =========================================================================
// Các trang bắt buộc user phải đăng nhập mới được truy cập
$userProtectedPages = [
    'change_password',
    'checkout', // Nếu muốn bắt login trước khi thanh toán
];

if (in_array($page, $userProtectedPages, true) && empty($_SESSION['user'])) {
    // Lưu lại URL hiện tại để sau khi login sẽ quay lại
    $_SESSION['redirect_after_login'] = 'index.php?page=' . $page;
    flash('error', 'Vui lòng đăng nhập để tiếp tục!');
    redirect_to('index.php?page=login');
}
// Đẩy về trang đăng nhập nếu truy cập Admin mà không có session quyền Admin
if (in_array($page, $adminProtectedPages, true) && empty($_SESSION['admin'])) {
    $_SESSION['flash']['error'] = 'Vui lòng đăng nhập để truy cập trang quản trị!';
    header("Location: index.php?page=admin_login");
    exit;
}

// =========================================================================
// 5. BỘ XỬ LÝ ĐỊNH TUYẾN CHÍNH (DYNAMIC ROUTER ENGINE)
// =========================================================================
if (isset($routes[$page])) {
    [$controllerClass, $methodName] = $routes[$page];

    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass($productModel);

        if (method_exists($controllerInstance, $methodName)) {
            // Truyền ID tự động nếu là trang chi tiết, ngược lại gọi hàm rỗng
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
    $result = ['view' => $simpleViews[$page], 'data' => []];
} else {
    http_response_code(404);
    echo '<h3>Trang bạn tìm không tồn tại (404).</h3>';
    exit;
}

// Kiểm tra tính chuẩn mực của dữ liệu trả về từ các Controller
if (!is_array($result) || empty($result['view'])) {
    http_response_code(500);
    echo '<h3>Lỗi cấu trúc: Dữ liệu trả về từ Controller không đúng định dạng.</h3>';
    exit;
}

// =========================================================================
// 6. CHUẨN BỊ DỮ LIỆU TOÀN CỤC CHO VIEW (GLOBAL DATA)
// =========================================================================
$result['data'] ??= [];

// 6.1. Xử lý Flash Message (Chỉ hiển thị 1 lần)
if (!empty($_SESSION['flash']) && is_array($_SESSION['flash'])) {
    $type = array_key_first($_SESSION['flash']);
    if ($type !== null) {
        $result['data']['flash'] = [
            'type'    => (string)$type,
            'message' => (string)($_SESSION['flash'][$type] ?? ''),
        ];
    }
    unset($_SESSION['flash']); // Hủy ngay để giải phóng RAM
}

// 6.2. Đóng gói các biến dùng chung cho toàn bộ Header / Sidebar
$result['data']['page']         = $page;
$result['data']['currentUser']  = $_SESSION['user'] ?? null;
$result['data']['currentAdmin'] = $_SESSION['admin'] ?? null;
$result['data']['cartCount']    = function_exists('cart_items_count') ? cart_items_count() : 0;

// =========================================================================
// 7. KÍCH HOẠT HÀM HIỂN THỊ GIAO DIỆN CHÍNH THỨC (FINAL RENDER)
// =========================================================================
render_view($result['view'], $result['data']);
