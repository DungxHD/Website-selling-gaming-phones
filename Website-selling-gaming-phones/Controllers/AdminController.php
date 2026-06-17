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
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            $inputs = array_map(fn($value) => trim($value ?? ''), $_POST);
            $uploadedImage = upload_product_image('image');
            $detailSpecs = $this->collectProductDetailSpecs($inputs);
            
            if ($uploadedImage === false) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ảnh sản phẩm!']);
                    exit();
                }
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
                $inputs['camera'] ?? '',
                $uploadedImage,
                $inputs['description'] ?? '',
                $detailSpecs
            );
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm mới thành công! 🎉']);
                exit();
            }

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
    $searchKeyword = trim($_GET['search'] ?? '');
    $brandFilter = trim($_GET['category_filter'] ?? '');
    $sortBy = trim($_GET['sort_by'] ?? 'newest');

    // Gom tìm kiếm + lọc hãng + sắp xếp về cùng 1 luồng để View admin dễ dùng hơn
    $products = $this->adminModel->filterProducts($searchKeyword, $brandFilter, $sortBy);

    return [
        'view' => 'backend/products.php',
        'data' => [
            'products'       => $products,
            'editingProduct' => isset($_GET['edit']) ? $this->productModel->getById((int)$_GET['edit']) : null,
            'searchKeyword'  => $searchKeyword,
            'selectedBrand'  => $brandFilter,
            'selectedSort'   => $sortBy,
            'brandOptions'   => $this->adminModel->getProductBrands(),
        ]
    ];
}


    // 3. XÓA SẢN PHẨM
    public function deleteProducts(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $id = (int)($_POST['product_id'] ?? 0);
            
            if ($id > 0) {
                $this->adminModel->deleteProducts($id);
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công!']);
                    exit();
                }
                $_SESSION['flash']['success'] = 'Xóa sản phẩm thành công!';
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ!']);
                    exit();
                }
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
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id > 0) {
                $inputs = array_map(fn($value) => trim($value ?? ''), $_POST);
                $detailSpecs = $this->collectProductDetailSpecs($inputs);
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
                    $inputs['camera'] ?? '',
                    $imagePath,
                    $inputs['description'] ?? '',
                    $detailSpecs
                );
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    if ($isUpdated) {
                        echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công! 🎉']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại, vui lòng thử lại!']);
                    }
                    exit();
                }

                if ($isUpdated) {
                    $_SESSION['flash']['success'] = 'Cập nhật sản phẩm thành công! 🎉';
                } else {
                    $_SESSION['flash']['error'] = 'Cập nhật thất bại, vui lòng thử lại!';
                }
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ!']);
                    exit();
                }
                $_SESSION['flash']['error'] = 'ID sản phẩm không hợp lệ!';
            }
            header("Location: index.php?page=admin_products");
            exit();
        }
        header("Location: index.php?page=admin_products");
        exit();
    }

    // Gom toàn bộ cột chi tiết của bảng products thành 1 mảng để Thêm/Sửa dùng chung 1 chuẩn dữ liệu
    private function collectProductDetailSpecs(array $inputs): array
    {
        return [
            'screen_ratio' => $this->normalizeOptionalText($inputs['screen_ratio'] ?? ''),
            'screen_tech' => $this->normalizeOptionalText($inputs['screen_tech'] ?? ''),
            'screen_resolution' => $this->normalizeOptionalText($inputs['screen_resolution'] ?? ''),
            'screen_glass' => $this->normalizeOptionalText($inputs['screen_glass'] ?? ''),
            'design_material' => $this->normalizeOptionalText($inputs['design_material'] ?? ''),
            'dimensions' => $this->normalizeOptionalText($inputs['dimensions'] ?? ''),
            'weight' => $this->normalizeOptionalText($inputs['weight'] ?? ''),
            'cam_rear_count' => ($inputs['cam_rear_count'] ?? '') === '' ? null : (int)$inputs['cam_rear_count'],
            'cam_rear_features' => $this->normalizeOptionalText($inputs['cam_rear_features'] ?? ''),
            'cam_rear_video' => $this->normalizeOptionalText($inputs['cam_rear_video'] ?? ''),
            'cam_front_specs' => $this->normalizeOptionalText($inputs['cam_front_specs'] ?? ''),
            'cam_front_video' => $this->normalizeOptionalText($inputs['cam_front_video'] ?? ''),
            'cam_front_features' => $this->normalizeOptionalText($inputs['cam_front_features'] ?? ''),
            'os' => $this->normalizeOptionalText($inputs['os'] ?? ''),
            'cpu_speed' => $this->normalizeOptionalText($inputs['cpu_speed'] ?? ''),
            'gpu' => $this->normalizeOptionalText($inputs['gpu'] ?? ''),
            'network' => $this->normalizeOptionalText($inputs['network'] ?? ''),
            'sim' => $this->normalizeOptionalText($inputs['sim'] ?? ''),
            'wifi' => $this->normalizeOptionalText($inputs['wifi'] ?? ''),
            'bluetooth' => $this->normalizeOptionalText($inputs['bluetooth'] ?? ''),
            'port_charging' => $this->normalizeOptionalText($inputs['port_charging'] ?? ''),
            'port_audio' => $this->normalizeOptionalText($inputs['port_audio'] ?? ''),
            'gps' => $this->normalizeOptionalText($inputs['gps'] ?? ''),
            'charging_tech' => $this->normalizeOptionalText($inputs['charging_tech'] ?? ''),
            'memory_card' => $this->normalizeOptionalText($inputs['memory_card'] ?? ''),
            'security' => $this->normalizeOptionalText($inputs['security'] ?? ''),
            'water_resistance' => $this->normalizeOptionalText($inputs['water_resistance'] ?? ''),
            'extra_features' => $this->normalizeOptionalText($inputs['extra_features'] ?? ''),
        ];
    }

    private function normalizeOptionalText(string $value): ?string
    {
        $value = trim($value);
        return $value === '' ? null : $value;
    }
}
