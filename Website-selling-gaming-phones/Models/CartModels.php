<?php

declare(strict_types=1);

class Cart
{
    public function __construct(
        public string $Username, // Bắt buộc phải có tên user truyền vào
        public array $Cart = [],
    ) {}
    public function Cart($productModel): array
    {
        // Kiểm tra bảo vệ: Chưa đăng nhập thì bắt buộc sang trang login
        if (empty($_SESSION['username'])) {
            $_SESSION['flash']['error'] = 'Bạn cần đăng nhập để xem giỏ hàng!';
            header('Location: index.php?page=login');
            exit;
        }

        // Lấy dữ liệu giỏ hàng từ Session
        $cartSession = $_SESSION['cart'] ?? [];
        $cartRows = [];
        $cartTotal = 0;

        // 3. Vòng lặp tính toán tiền và gom thông tin sản phẩm
        foreach ($cartSession as $productId => $quantity) {
            $product = $this->productModel->getById((int)$productId);

            if ($product) {
                $subtotal = (int)$product['price'] * (int)$quantity;
                $cartTotal += $subtotal;

                // Gom dữ liệu thành một dòng hàng hoàn chỉnh
                $cartRows[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }

        // 4. TRẢ DATA VỀ THEO CHUẨN MVC ĐỂ index.php TỰ RENDER
        return [
            'view' => 'frontend/cart.php',
            'data' => [
                'cartRows'  => $cartRows,
                'cartTotal' => $cartTotal
            ]
        ];
    }
}
