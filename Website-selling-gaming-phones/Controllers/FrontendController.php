<?php
require_once __DIR__ . '/../Models/Product.php';

class FrontendController
{
    public function __construct(
        private Product $productModel = new Product()
    ) {}

    public function home(): array
    {
        return [
            'view' => 'frontend/home.php',
            'data' => [
                'bestSellers' => $this->productModel->getBestSellers(8),
                'products'    => $this->productModel->getAll(8),
                'hotProducts'  => $this->productModel->getHotproduct(),
                'pagination'  => [
                    'current'    => 1,
                    'totalPages' => 1,
                    'basePage'   => 'shop'
                ]
            ],
        ];
    }

    public function shop(): array
    {
        // 1. Phân trang mặc định
        $currentPage = (int)($_GET['p'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;
        $perPage = 12; // Hiển thị 12 sản phẩm trên 1 trang cửa hàng

        // 2. Hứng các tham số từ URL chuẩn bị cho bồ tự code sau này
        $searchNormal = trim($_GET['search'] ?? '');
        $action       = trim($_GET['action'] ?? '');

        // Hứng bộ lọc nâng cao theo đúng tên bồ thiết kế
        $advancedFilters = [
            'name'      => trim($_GET['name'] ?? ''),
            'company'   => trim($_GET['company'] ?? ''),
            'status'    => trim($_GET['status'] ?? ''),
            'price_min' => trim($_GET['price_min'] ?? ''),
            'price_max' => trim($_GET['price_max'] ?? ''),
            'arrange'   => trim($_GET['arrange'] ?? ''),
        ];

        // Gộp mảng để truyền về View (Giúp giữ lại các giá trị đã nhập trên ô tìm kiếm)
        $allFilters = array_merge(['search' => $searchNormal, 'action' => $action], $advancedFilters);

        $products = [];
        $totalProducts = 0;

        // ==========================================================
        // 3. KHUNG LOGIC PHÂN LUỒNG (Để bồ tự code)
        // ==========================================================
        if ($action === 'advanced_search') {

            // TODO: Bồ tự viết gọi Model cho TÌM KIẾM NÂNG CAO ở đây
            // URL tương ứng: ?page=shop&action=advanced_search&name=...&company=...
            $totalProducts = 0; // Bồ tự thay bằng hàm đếm sản phẩm lọc nâng cao
            $products      = []; // Bồ tự thay bằng hàm lấy sản phẩm lọc nâng cao

        } elseif ($searchNormal !== '') {

            // TODO: Bồ tự viết gọi Model cho TÌM KIẾM THƯỜNG ở đây
            // URL tương ứng: ?page=shop&search=keywords
            $totalProducts = 0; // Bồ tự thay bằng hàm đếm sản phẩm tìm kiếm thường
            $products      = []; // Bồ tự thay bằng hàm lấy sản phẩm tìm kiếm thường

        } else {

            // MẶC ĐỊNH: HIỂN THỊ CỬA HÀNG BÌNH THƯỜNG (?page=shop)
            // Phần này tôi đã làm sẵn để hiển thị sản phẩm cho bồ xem luôn
            $totalProducts = $this->productModel->getTotalProductsCount();
            $products      = $this->productModel->getProductsByPage($currentPage, $perPage);
        }
        // ==========================================================

        // 4. Tính toán số trang (Tự động thích ứng với các luồng trên)
        $totalPages = (int)ceil($totalProducts / $perPage);
        if ($totalPages < 1) $totalPages = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        // 5. Trả dữ liệu về cho View (shop.php)
        return [
            'view' => 'frontend/shop.php',
            'data' => [
                'filters'    => $allFilters,
                'brands'     => $this->productModel->getBrands(),
                'products'   => $products,
                'pagination' => [
                    'current'    => $currentPage,
                    'totalPages' => $totalPages,
                    'basePage'   => 'shop',
                    // Lọc bỏ biến rỗng để URL khi bấm chuyển trang trông gọn gàng
                    'query'      => array_filter($allFilters, fn($val) => $val !== ''),
                ],
            ],
        ];
    }


    public function detail(int $id): array
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            return [
                'view' => 'frontend/shop.php',
                'data' => [
                    'filters'    => ['keyword' => '', 'brand' => ''],
                    'brands'     => $this->productModel->getBrands(),
                    'products'   => $this->productModel->getAll(20),
                    'pagination' => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop', 'query' => []],
                ],
            ];
        }

        $related = $this->productModel->search('', (string)($product['brand'] ?? ''), 8);

        return [
            'view' => 'frontend/detail.php',
            'data' => [
                'product'         => $product,
                'relatedProducts' => $related,
            ],
        ];
    }
}
