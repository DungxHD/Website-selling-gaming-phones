<?php

declare(strict_types=1);

class CartModel
{
    public array $rows = [];
    public int $total = 0;

    public function __construct(
        private Product $productModel,
        private array $cartSession
    ) {
        // Tự động kích hoạt việc tính toán ngay khi class được khởi tạo
        $this->processCart();
    }

    // Hàm nội bộ dùng để tính tiền và nhét dữ liệu vào biến $rows và $total
    private function processCart(): void
    {
        // Kiểm tra nếu giỏ hàng trống thì dừng lại luôn
        if (empty($this->cartSession)) {
            return;
        }

        // Duyệt qua từng sản phẩm trong giỏ hàng thô (ID => Số lượng)
        foreach ($this->cartSession as $productId => $quantity) {
            // Dùng productModel để lấy thông tin chi tiết của máy từ database
            $product = $this->productModel->getById((int)$productId);

            if ($product) {
                // Tính thành tiền của món này = Giá máy * Số lượng mua
                $subTotal = $product['price'] * $quantity;

                // Đóng gói thông tin đầy đủ và nhét vào mảng $rows
                $this->rows[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subTotal' => $subTotal
                ];

                // Cộng dồn vào tổng tiền của cả giỏ hàng
                $this->total += $subTotal;
            }
        }
    }
}
