<?php
session_start();
// Dũng đã hoàn thành rồi nhé...
// =========================================================
// FRONT CONTROLLER (Router tối giản)
// =========================================================
// Mục tiêu file này:
// - Nhận mọi request qua index.php?page=...
// - Gọi Controller để lấy dữ liệu
// - Render View
// Hoàn thành Ginag 
// Bạn đang học MVC nên mình giữ mọi thứ thật gọn:
// - Models: Models/Database.php, Models/Product.php
// - Controllers: Controllers/FrontendController.php
// - Views: Views/frontend và Views/backend (chỉ hiển thị)
// Giang rồnggggggggggg hẹ hẹ
// Dũng trọc hà đông
require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/Product.php';
require_once __DIR__ . '/Controllers/FrontendController.php';
require_once __DIR__ . '/helpers.php';

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

function cart_items_count(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}

$page = $_GET['page'] ?? 'home';

$productModel = new Product();
$frontendController = new FrontendController($productModel);

$result = null;

switch ($page) {
    case 'home':
        $result = $frontendController->home();
        break;
    case 'shop':
        $result = $frontendController->shop();
        break;
    case 'detail':
        $result = $frontendController->detail((int)($_GET['id'] ?? 0));
        break;
    default:
        // Các trang còn lại hiện chỉ giữ giao diện để bạn làm tiếp.
        // Khi bạn học xong, bạn sẽ tạo controller/model cho từng chức năng.
        $simpleViews = [
            'login' => 'frontend/login.php',
            'register' => 'frontend/register.php',
            'forgot' => 'frontend/forgot.php',
            'change_password' => 'frontend/change_password.php',
            'cart' => 'frontend/cart.php',
            'checkout' => 'frontend/checkout.php',
            'admin_login' => 'backend/login.php',
            'admin_dashboard' => 'backend/dashboard.php',
            'admin_products' => 'backend/products.php',
            'admin_orders' => 'backend/orders.php',
            'admin_users' => 'backend/users.php',
        ];

        if (isset($simpleViews[$page])) {
            $result = ['view' => $simpleViews[$page], 'data' => []];
            break;
        }

        http_response_code(404);
        echo '<h3>Trang bạn tìm không tồn tại (404).</h3>';
        exit;
}

if (!is_array($result) || empty($result['view'])) {
    http_response_code(500);
    echo '<h3>Không thể hiển thị trang hiện tại.</h3>';
    exit;
}

$flash = null;
if (!empty($_SESSION['flash']) && is_array($_SESSION['flash'])) {
    $type = array_key_first($_SESSION['flash']);
    if ($type !== null) {
        $flash = [
            'type' => (string)$type,
            'message' => (string)($_SESSION['flash'][$type] ?? ''),
        ];
    }
    unset($_SESSION['flash']);
}

// Data dùng chung để header/admin sidebar hoạt động đúng (đơn giản)
$shared = [
    'page' => $page,
    'cartCount' => cart_items_count(),
    'currentUser' => $_SESSION['user'] ?? null,
    'currentAdmin' => $_SESSION['admin'] ?? null,
    'flash' => $flash,
];

render_view($result['view'], array_merge($shared, $result['data'] ?? []));
