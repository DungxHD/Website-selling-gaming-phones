<?php

declare(strict_types=1);

class CartModel
{
    public array $rows = [];
    public int $total = 0;

    public function __construct(
        private Product $productModel
    ) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add(int $productId, int $quantity): void
    {
        if ($productId <= 0 || $quantity <= 0) return;

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function update(int $productId, int $quantity): void
    {
        if ($productId <= 0) return;

        if ($quantity <= 0) {
            $this->delete($productId);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function delete(int $productId): void
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function getCartDetails(): array
    {
        $this->rows = [];
        $this->total = 0;
        $cartSession = $_SESSION['cart'] ?? [];

        foreach ($cartSession as $productId => $quantity) {
            $product = $this->productModel->getById((int)$productId);

            if ($product) {
                $lineTotal = (int)$product['price'] * (int)$quantity;
                $this->total += $lineTotal;

                $this->rows[] = [
                    'product'   => $product,
                    'quantity'  => (int)$quantity,
                    'lineTotal' => $lineTotal // Đồng bộ hoàn toàn với View
                ];
            } else {
                $this->delete((int)$productId);
            }
        }

        return [
            'rows'  => $this->rows,
            'total' => $this->total
        ];
    }
}