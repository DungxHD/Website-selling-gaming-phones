<?php
// =========================================================
// ADMIN CONTROLLER (CHỈ HƯỚNG DẪN)
// =========================================================
// Nhiệm vụ của AdminController trong MVC:
// 1) Bảo vệ trang admin:
//    - Kiểm tra $_SESSION['admin'] trước khi cho vào trang quản trị
// 2) Dashboard:
//    - Thống kê số sản phẩm, số đơn hàng, số người dùng...
// 3) Quản lý sản phẩm (CRUD):
//    - create: thêm sản phẩm
//    - read: xem danh sách
//    - update: sửa sản phẩm
//    - delete: xóa sản phẩm
// 4) Quản lý đơn hàng:
//    - xem danh sách
//    - cập nhật trạng thái (mới, đang giao, hoàn tất...)
// 5) Quản lý người dùng:
//    - xem danh sách
//    - khóa/mở tài khoản
//
// Gợi ý các hàm bạn nên tạo:
// - dashboard()
// - products(), productSave(), productDelete()
// - orders(), orderUpdateStatus()
// - users(), userToggleStatus()


require_once __DIR__ . '/../Models/Product.php';

class AdminController
{
    private $productModel;
    public function __construct($productModel)
    {
        $this->productModel = $productModel;
    }

    public function addProduct(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputs = array_map(fn($value) => trim($value ?? ''), $_POST);

            $this->productModel->addProduct(
                $inputs['name'] ?? '',
                $inputs['brand'] ?? '',
                $inputs['price'] ?? 0,
                $inputs['quantity'] ?? 0,
                $inputs['condition'] ?? '',
                $inputs['rating'] ?? 0,
                $inputs['cpu'] ?? '',
                $inputs['ram'] ?? '',
                $inputs['rom'] ?? '',
                $inputs['screen'] ?? '',
                $inputs['battery'] ?? '',
                $inputs['charger'] ?? '',
                $inputs['image'] ?? '',
                $inputs['description'] ?? ''
            );

            $_SESSION['flash']['success'] = 'Thêm sản phẩm mới thành công! 🎉';
            header("Location: index.php?page=admin_products");
            exit();
        }

        return [
            'view' => 'backend/products.php',
            'data' => []
        ];
    }

    public function products(): array
    {
        return [
            'view' => 'backend/products.php',
            'data' => [
                'products'       => $this->productModel->getAll(1000),
                'editingProduct' => isset($_GET['edit']) ? $this->productModel->getById((int)$_GET['edit']) : null
            ]
        ];
    }
}
