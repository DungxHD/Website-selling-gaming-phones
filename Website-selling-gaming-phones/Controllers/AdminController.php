<?php
require_once __DIR__ . '/../Models/Product.php';
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

class AdminController {
    private $admin;
    public function __construct()
    {
        $admin = new Product;
    }
    public function addProduct() {
        $name = trim($_POST['name']) ?? '';
        $brand = trim($_POST['brand']) ?? '';
        $price = trim($_POST['price']) ?? '';
        $quantity = trim($_POST['quantity']) ?? '';
        $condition = $_POST['condition'];
        $rating = trim($_POST['rating']) ?? '';
        $cpu = trim($_POST['cpu']) ?? '';
        $screen = trim($_POST['screen']) ?? '';
        $battery = trim($_POST['battery']) ?? '';
        $charger = trim($_POST['charger']) ?? '';
        $image = trim($_POST['image']) ?? '';
        $description = trim($_POST['description']) ?? '';
    }
}
