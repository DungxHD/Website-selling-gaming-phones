<?php

declare(strict_types=1);

class CartController
{
    public function __construct(
        private Product $productModel
    ) {}
    public function cart(): array
    {
        if ($_GET['action'] ?? '' === 'cart_add') {
            $interface = 'frontend/' . $_GET['page'] . '.php';
        } else {
            $interface = 'frontend/cart.php';
        }

        return [
            'view' => $interface,
            'data' => [
                'cartRows' => '',
                'cartTotal' => 0,
            ]
        ];
    }
}
