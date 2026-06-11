<?php
require_once __DIR__ . '/../Models/Product.php';

// =========================================================
// CONTROLLER FRONTEND (Đơn giản nhất)
// =========================================================
// Nhiệm vụ Controller:
// - Nhận request (đọc $_GET/$_POST)
// - Gọi Model để lấy dữ liệu
// - Trả về: view + data để index.php render
//
// Khi bạn làm ASM tiếp, bạn có thể tách thêm controller:
// - AuthController: đăng nhập/đăng ký
// - CartController: giỏ hàng
// - AdminController: quản trị

class FrontendController
{
    public function __construct(
        private Product $productModel = new Product()
    ) {
    }

    public function home(): array
    {
        return [
            'view' => 'frontend/home.php',
            'data' => [
                // Trang chủ đang dùng giao diện có nhiều block.
                // Bạn mới học nên tạm truyền dữ liệu tối giản: danh sách sản phẩm mới nhất.
                'products' => $this->productModel->getAll(8),
                'hotProducts' => $this->productModel->getHotproduct(),
            ],
        ];
    }

    public function shop(): array
    {
        // Lấy dữ liệu lọc cơ bản từ URL (đơn giản nhất)
        $filters = [
            'keyword' => trim($_GET['q'] ?? ''),
            'brand' => trim($_GET['brand'] ?? ''),
        ];

        return [
            'view' => 'frontend/shop.php',
            'data' => [
                'filters' => $filters,
                'brands' => $this->productModel->getBrands(),
                'products' => $this->productModel->search($filters['keyword'], $filters['brand'], 200),
                'pagination' => [
                    // Chưa làm phân trang để code dễ hiểu (bạn có thể làm ở bước tiếp theo)
                    'current' => 1,
                    'totalPages' => 1,
                    'basePage' => 'shop',
                    'query' => $filters,
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
                    'filters' => ['keyword' => '', 'brand' => ''],
                    'brands' => $this->productModel->getBrands(),
                    'products' => $this->productModel->getAll(20),
                    'pagination' => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop', 'query' => []],
                ],
            ];
        }

        $related = $this->productModel->search('', (string)($product['brand'] ?? ''), 8);

        return [
            'view' => 'frontend/detail.php',
            'data' => [
                'product' => $product,
                'relatedProducts' => $related,
            ],
        ];
    }
}
