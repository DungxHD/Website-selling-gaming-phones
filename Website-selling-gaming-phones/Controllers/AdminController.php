<?php
// Đảm bảo đã require file AdminModel
require_once __DIR__ . '/../Models/AdminModel.php'; 

class AdminController
{
    private $productModel;
    private $adminModel;

    public function __construct($productModel)
    {
        $this->productModel = $productModel;
        $this->adminModel = new AdminProduct(); // Khởi tạo Admin Model
    }

    // 1. THÊM SẢN PHẨM
    public function addProduct(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputs = array_map(fn($value) => trim($value ?? ''), $_POST); // array_map duyệt toàn bộ post để thêm trim loại bỏ khoảng trắng vào từng phần tử

            // XỬ LÝ UPLOAD ẢNH (Bắt buộc phải có file)
            $uploadedImage = upload_product_image('image');

            if ($uploadedImage === false) {
                $_SESSION['flash']['error'] = 'Vui lòng chọn ảnh sản phẩm!';
                header("Location: index.php?page=admin_products");
                exit();
            }

            $this->adminModel->addProduct(
                $inputs['name'] ?? '',
                $inputs['brand'] ?? '',
                (int)($inputs['price'] ?? 0),
                (int)($inputs['quantity'] ?? 0),
                $inputs['condition'] ?? '',
                (int)($inputs['rating'] ?? 0),
                $inputs['cpu'] ?? '',
                $inputs['ram'] ?? '',
                $inputs['rom'] ?? '',
                $inputs['screen'] ?? '',
                $inputs['battery'] ?? '',
                $inputs['charger'] ?? '',
                $uploadedImage, // Đường dẫn ảnh đã upload
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



    // 2. HIỂN THỊ DANH SÁCH SẢN PHẨM
    public function products(): array
    {
        return [
            'view' => 'backend/products.php',
            'data' => [
                'products'       => $this->productModel->getAll(100), // Đọc dữ liệu vẫn dùng productModel
                'editingProduct' => isset($_GET['edit']) ? $this->productModel->getById((int)$_GET['edit']) : null
            ]
        ];
    }


    // 3. XÓA SẢN PHẨM
    public function deleteProducts(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['product_id'] ?? 0);

            if ($id > 0) {
                $this->adminModel->deleteProducts($id); // Gọi sang adminModel
                $_SESSION['flash']['success'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['flash']['error'] = 'ID sản phẩm không hợp lệ!';
            }

            header("Location: index.php?page=admin_products");
            exit();
        }

        header("Location: index.php?page=admin_products");
        exit();
    }

    // 4. CẬP NHẬT SẢN PHẨM
    public function updateProduct(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $inputs = array_map(fn($value) => trim($value ?? ''), $_POST);

                // XỬ LÝ ẢNH KHI SỬA
                $imagePath = $inputs['old_image'] ?? ''; // Mặc định giữ ảnh cũ

                // Nếu có upload ảnh mới
                $uploadedImage = upload_product_image('image');
                if ($uploadedImage !== false) {
                    $imagePath = $uploadedImage;

                    // Xóa ảnh cũ nếu có
                    if (!empty($inputs['old_image']) && file_exists(__DIR__ . '/../' . $inputs['old_image'])) {
                        @unlink(__DIR__ . '/../' . $inputs['old_image']);
                    }
                }
                // Nếu không upload ảnh mới → Giữ nguyên ảnh cũ (đã gán ở trên)

                $isUpdated = $this->adminModel->updateProduct(
                    $id,
                    $inputs['name'] ?? '',
                    $inputs['brand'] ?? '',
                    (int)($inputs['price'] ?? 0),
                    (int)($inputs['quantity'] ?? 0),
                    $inputs['condition'] ?? '',
                    (int)($inputs['rating'] ?? 0),
                    $inputs['cpu'] ?? '',
                    $inputs['ram'] ?? '',
                    $inputs['rom'] ?? '',
                    $inputs['screen'] ?? '',
                    $inputs['battery'] ?? '',
                    $inputs['charger'] ?? '',
                    $imagePath, // Đường dẫn ảnh (mới hoặc cũ)
                    $inputs['description'] ?? ''
                );

                if ($isUpdated) {
                    $_SESSION['flash']['success'] = 'Cập nhật sản phẩm thành công! 🎉';
                } else {
                    $_SESSION['flash']['error'] = 'Cập nhật thất bại, vui lòng thử lại!';
                }
            } else {
                $_SESSION['flash']['error'] = 'ID sản phẩm không hợp lệ!';
            }

            header("Location: index.php?page=admin_products");
            exit();
        }

        header("Location: index.php?page=admin_products");
        exit();
    }
}
