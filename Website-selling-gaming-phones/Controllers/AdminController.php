<?php 
class AdminController
{
    private $productModel;
    private $adminModel;

    public function __construct($productModel)
    {
        $this->productModel = $productModel;
        $this->adminModel = new AdminModel();
    }

    // =========================================================
    // HÀM DASHBOARD - THỐNG KÊ TỔNG QUAN
    // =========================================================
    public function dashboard(): array
    {
        return [
            'view' => 'backend/dashboard.php',
            'data' => [
                'stats'        => $this->adminModel->getDashboardStats(),
                'topProducts'  => $this->adminModel->getTopSellingProducts(10),
                'latestOrders' => $this->adminModel->getLatestOrders(5),
            ]
        ];
    }

    // 1. THÊM SẢN PHẨM
    public function addProduct(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputs = array_map(fn($value) => trim($value ?? ''), $_POST);
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
                $uploadedImage,
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

// 2. HIỂN THỊ DANH SÁCH SẢN PHẨM (CÓ TÌM KIẾM)
public function products(): array
{
    // Lấy từ khóa tìm kiếm từ URL
    $searchKeyword = trim($_GET['search'] ?? '');
    
    // Nếu có từ khóa tìm kiếm → gọi hàm searchProducts
    if ($searchKeyword !== '') {
        $products = $this->adminModel->searchProducts($searchKeyword);
        $_SESSION['flash']['success'] = 'Sản phẩm đã tìm thấy hãy kéo xuống để xem !!';
    } else {
        // Không có tìm kiếm → lấy tất cả
        $products = $this->productModel->getAll(100);
        $_SESSION['flash']['success'] = 'Xóa tìm sản phẩm thành công !!';
    }
    
    return [
        'view' => 'backend/products.php',
        'data' => [
            'products'       => $products,
            'editingProduct' => isset($_GET['edit']) ? $this->productModel->getById((int)$_GET['edit']) : null,
            'searchKeyword'  => $searchKeyword  // Truyền từ khóa xuống view
        ]
    ];
}


    // 3. XÓA SẢN PHẨM
    public function deleteProducts(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['product_id'] ?? 0);
            if ($id > 0) {
                $this->adminModel->deleteProducts($id);
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
                $imagePath = $inputs['old_image'] ?? '';
                $uploadedImage = upload_product_image('image');
                if ($uploadedImage !== false) {
                    $imagePath = $uploadedImage;
                    if (!empty($inputs['old_image']) && file_exists(__DIR__ . '/../' . $inputs['old_image'])) {
                        @unlink(__DIR__ . '/../' . $inputs['old_image']);
                    }
                }
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
                    $imagePath,
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