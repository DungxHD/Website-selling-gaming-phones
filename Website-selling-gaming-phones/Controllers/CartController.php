<?php

declare(strict_types=1);

class CartController
{
    public function __construct(
        private Product $productModel
    ) {}
    public function cart(): array
    {
        return [
            'view' => 'frontend/cart.php',
            'data' => [
                'cartRows' => '',
                'cartTotal' => 0,
            ]
        ];
    }
}
