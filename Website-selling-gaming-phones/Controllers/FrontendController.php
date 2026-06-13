<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Product.php';

class FrontendController
{
    private const HOME_PRODUCTS_LIMIT = 8; // Danh sách sản phẩm (Mới nhất trong kho)
    private const SHOP_PER_PAGE = 12; // Số div sản phẩm trên 1 trang
    private const DETAIL_FALLBACK_LIMIT = 12; //Số sản phẩm dự phòng khi không tìm thấy chi tiết
    private const RELATED_PRODUCTS_LIMIT = 8; // Số sản phẩm liên quan cùng thương hiệu

    public function __construct(
        private Product $productModel = new Product()
    ) {}

    // =========================================================
    // NHÓM HÀM TRANG CHỦ
    // =========================================================
    public function home(): array
    {
        return [
            'view' => 'frontend/home.php',
            'data' => [
                'bestSellers' => $this->productModel->getBestSellers(self::HOME_PRODUCTS_LIMIT),
                'products'    => $this->productModel->getAll(self::HOME_PRODUCTS_LIMIT),
                'hotProducts' => $this->productModel->getHotProducts(),
                'pagination'  => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop'],
            ],
        ];
    }

    // =========================================================
    // NHÓM HÀM CỬA HÀNG - TÌM KIẾM - PHÂN TRANG
    // =========================================================
    public function shop(): array
    {
        $requestData = $this->getShopRequestData();
        $currentPage = $requestData['currentPage'];
        $perPage = self::SHOP_PER_PAGE;
        $searchNormal = $requestData['searchNormal'];
        $action = $requestData['action'];
        $advancedFilters = $requestData['advancedFilters'];
        $allFilters = $requestData['allFilters'];
        $hasAdvancedFilters = $requestData['hasAdvancedFilters'];

        [$totalProducts, $products] = match (true) {
            // Tìm nâng cao 
            $action === 'advanced_search' || $hasAdvancedFilters => [
                $this->productModel->getAdvancedSearchCount($advancedFilters),
                $this->productModel->getAdvancedSearchProducts($advancedFilters, $currentPage, $perPage),
            ],
            // Tìm thường
            $searchNormal !== '' => [
                $this->productModel->getNormalSearchCount($searchNormal),
                $this->productModel->getNormalSearchProducts($searchNormal, $currentPage, $perPage),
            ],
            // Mặc định: Hiển thị tất cả sản phẩm
            default => [
                $this->productModel->getTotalProductsCount(),
                $this->productModel->getProductsByPage($currentPage, $perPage),
            ],
        };

        // Tính toán phân trang 
        $totalPages = max(1, (int)ceil($totalProducts / $perPage));
        $currentPage = min($currentPage, $totalPages); // Không vượt quá tổng số trang

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
                    // Array filter: Lọc bỏ filter rỗng để URL gọn gàng
                    'query'      => array_filter($allFilters, fn($v) => $v !== ''),
                ],
            ],
        ];
    }

    // =========================================================
    // NHÓM HÀM CHI TIẾT SẢN PHẨM
    // =========================================================
    /**
     * Chi tiết sản phẩm
     */
    public function detail(int $id): array
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            return [
                'view' => 'frontend/shop.php',
                'data' => [
                    'flash'      => [
                        'type' => 'error',
                        'message' => 'Không tìm thấy sản phẩm bạn muốn xem. Hệ thống đã đưa bạn về danh sách sản phẩm.',
                    ],
                    'filters'    => [],
                    'brands'     => $this->productModel->getBrands(),
                    'products'   => $this->productModel->getAll(self::DETAIL_FALLBACK_LIMIT),
                    'pagination' => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop', 'query' => []],
                ],
            ];
        }

        // Sản phẩm liên quan cùng thương hiệu
        $related = $this->productModel->getRelatedProductsByBrand(
            (string)($product['brand'] ?? ''),
            (int)($product['id'] ?? 0),
            self::RELATED_PRODUCTS_LIMIT
        );

        return [
            'view' => 'frontend/detail.php',
            'data' => [
                'product'         => $product,
                'relatedProducts' => $related,
            ],
        ];
    }

    // - Hàm này được thêm vào để gom phần đọc/làm sạch dữ liệu từ URL.
    private function getShopRequestData(): array
    {
        $currentPage = max(1, (int)($_GET['p'] ?? 1));
        $searchNormal = trim((string)($_GET['search'] ?? $_GET['q'] ?? ''));
        $action = trim((string)($_GET['action'] ?? ''));

        $advancedFilters = [
            'name' => trim((string)($_GET['name'] ?? '')),
            'company' => trim((string)($_GET['company'] ?? $_GET['brand'] ?? '')),
            'status' => trim((string)($_GET['status'] ?? $_GET['condition'] ?? '')),
            'price_min' => trim((string)($_GET['price_min'] ?? $_GET['min_price'] ?? '')),
            'price_max' => trim((string)($_GET['price_max'] ?? $_GET['max_price'] ?? '')),
            'arrange' => trim((string)($_GET['arrange'] ?? $_GET['sort'] ?? '')),
        ];

        $hasAdvancedFilters = count(array_filter($advancedFilters, fn($value) => $value !== '')) > 0;

        return [
            'currentPage' => $currentPage,
            'searchNormal' => $searchNormal,
            'action' => $action,
            'advancedFilters' => $advancedFilters,
            'allFilters' => ['search' => $searchNormal, 'action' => $action] + $advancedFilters,
            'hasAdvancedFilters' => $hasAdvancedFilters,
        ];
    }
}
