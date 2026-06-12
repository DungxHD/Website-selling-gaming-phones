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
    // 1. Đổi tên biến thành $productModel cho chuẩn bản chất
    private $productModel;

    // 2. Thêm tham số $productModel để hứng dữ liệu từ index.php truyền vào
    public function __construct($productModel)
    {
        // 3. BẮT BUỘC phải có $this-> để gán vào thuộc tính của class
        $this->productModel = $productModel;
    }

    // public function viewProducts() {

    // }
    // 4. Đổi kiểu trả về thành array (để index.php render được view)
    public function addProduct(): array
    {
        // Logic chuẩn: POST để xử lý lưu DB, GET để hiển thị Form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sửa lại cách dùng ?? để tránh lỗi Warning ở PHP 8.x
            $name        = trim($_POST['name'] ?? '');
            $brand       = trim($_POST['brand'] ?? '');
            $price       = trim($_POST['price'] ?? 0);
            $quantity    = trim($_POST['quantity'] ?? 0);
            $condition   = $_POST['condition'] ?? '';
            $rating      = trim($_POST['rating'] ?? 0);
            $cpu         = trim($_POST['cpu'] ?? '');
            $ram         = trim($_POST['ram'] ?? '');
            $rom         = trim($_POST['rom'] ?? '');
            $screen      = trim($_POST['screen'] ?? '');
            $battery     = trim($_POST['battery'] ?? '');
            $charger     = trim($_POST['charger'] ?? '');
            $image       = trim($_POST['image'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Gọi hàm addProduct từ Model (lúc này $this->productModel đã là object xịn)
            $this->productModel->addProduct(
                $name,
                $brand,
                $price,
                $quantity,
                $condition,
                $rating,
                $cpu,
                $ram,
                $rom,
                $screen,
                $battery,
                $charger,
                $image,
                $description
            );

            // Sau khi lưu xong, báo thành công và chuyển hướng về trang danh sách
            $_SESSION['flash']['success'] = 'Thêm sản phẩm mới thành công!';
            header("Location: index.php?page=admin_products");
            exit();
        }

        // Nếu là GET request (người dùng mới vừa bấm vào menu "Thêm sản phẩm")
        // Trả về mảng chứa View để index.php render ra cái Form cho họ điền
        return [
            'view' => 'backend/products.php', // Đảm bảo bạn có file Views/backend/add_product.php
            'data' => []
        ];
    }

    // Hàm hiển thị trang quản lý sản phẩm
    public function products(): array
    {
        // 1. Lấy toàn bộ sản phẩm từ Model (tăng limit lên cao để lấy hết)
        $products = $this->productModel->getAll(1000);

        // 2. Kiểm tra xem có đang bấm nút "Sửa" không (URL có ?edit=ID)
        $editingProduct = null;
        if (isset($_GET['edit'])) {
            $editId = (int)$_GET['edit'];
            $editingProduct = $this->productModel->getById($editId);
        }

        // 3. Trả về View kèm theo DỮ LIỆU
        return [
            'view' => 'backend/products.php',
            'data' => [
                'products' => $products,         // Truyền danh sách sản phẩm
                'editingProduct' => $editingProduct // Truyền sản phẩm cần sửa (nếu có)
            ]
        ];
    }
}
