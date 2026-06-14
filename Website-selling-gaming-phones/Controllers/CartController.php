<?php

declare(strict_types=1);
require_once __DIR__ . '/../Models/CartModels.php';

class CartController
{
    // Khởi tạo Product model
    public function __construct(
        private  Product $productModel = new Product(),
    ) {}

    public function cartcontroler()
    {
      
    }
}
