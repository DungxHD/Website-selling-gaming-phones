<?php
declare(strict_types=1); 

class CartModels 
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
        // Bồ sẽ viết vòng lặp xử lý logic ở đây...
    }
}