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
                'products' => $this->productModel->getAll(8),
                'hotProducts' => $this->productModel->getHotproduct(),
            ],
        ];
    }

    public function shop(): array
    {
        $filters = [
            'keyword' => trim($_GET['q'] ?? ''),
            'brand'   => trim($_GET['brand'] ?? ''),
        ];

        return [
            'view' => 'frontend/shop.php',
            'data' => [
                'filters'    => $filters,
                'brands'     => $this->productModel->getBrands(),
                'products'   => $this->productModel->search($filters['keyword'], $filters['brand'], 200),
                'pagination' => [
                    'current'    => 1,
                    'totalPages' => 1,
                    'basePage'   => 'shop',
                    'query'      => $filters,
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
