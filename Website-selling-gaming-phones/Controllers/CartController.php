<?php

declare(strict_types=1);

class CartController
{
    // Sử dụng readonly (PHP 8.1+) giúp code chuyên nghiệp, an toàn và tối ưu bộ nhớ
    private readonly CartModel $cartModel;

    // Nhận $productModel được bơm tự động từ index.php
    public function __construct(Product $productModel)
    {
        $this->cartModel = new CartModel($productModel);
    }

    /**
     * Luồng 1: Điều hướng chính và hiển thị trang giỏ hàng
     */
    public function cart(): ?array
    {
        $action = $_GET['action'] ?? '';

        // Tối ưu hóa: Dùng match() (PHP 8.0+) thay cho nhiều lệnh if.
        // Vì bên trong hàm update, remove, clear đều gọi redirect_to() có chứa hàm exit(),
        // nên luồng code sẽ tự động dừng tại đây nếu khớp action.
        match ($action) {
            'cart_update', 'update' => $this->update(),
            'cart_remove', 'remove' => $this->remove(),
            'cart_clear', 'clear'   => $this->clear(),
            default                 => null, // Tiếp tục chạy xuống dưới nếu không có action
        };

        // Nếu không có action xử lý dữ liệu, thì lấy chi tiết và hiển thị giỏ hàng
        $this->cartModel->getCartDetails();

        return [
            'view' => 'frontend/cart.php',
            'data' => [
                'cartRows'  => $this->cartModel->rows,
                'cartTotal' => $this->cartModel->total
            ]
        ];
    }

    /**
     * Luồng 2: Xử lý thêm vào giỏ (Hỗ trợ nút Mua nhanh & Form chi tiết)
     */
    public function add(): void
    {
        // Quét lấy ID từ POST hoặc GET để đảm bảo tương thích mọi Form
        $productId = (int)($_POST['product_id'] ?? $_GET['id'] ?? 0);
        $quantity  = max(1, (int)($_POST['quantity'] ?? $_GET['quantity'] ?? 1));

        if ($productId > 0) {
            $this->cartModel->add($productId, $quantity);
            flash('success', 'Đã thêm sản phẩm vào giỏ hàng thành công! 🛒');
        } else {
            flash('error', 'Sản phẩm không hợp lệ. ⚠️');
        }

        // Lấy đường dẫn trang trước đó để quay lại, mặc định là trang shop
        $referer = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php?page=shop';
        redirect_to($referer);
    }

    /**
     * Luồng 3: Cập nhật đồng loạt số lượng trong giỏ
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantities = $_POST['quantities'] ?? [];
            if (is_array($quantities)) {
                foreach ($quantities as $productId => $quantity) {
                    $this->cartModel->update((int)$productId, (int)$quantity);
                }
                flash('success', 'Cập nhật số lượng giỏ hàng thành công! 🔄');
            }
        }
        redirect_to('index.php?page=cart');
    }

    /**
     * Luồng 4: Xóa một sản phẩm khỏi giỏ
     */
    public function remove(): void
    {
        $productId = (int)($_POST['product_id'] ?? $_GET['id'] ?? 0);
        if ($productId > 0) {
            $this->cartModel->delete($productId);
            flash('success', 'Đã xóa sản phẩm khỏi giỏ hàng. 🗑️');
        }
        redirect_to('index.php?page=cart');
    }

    /**
     * Luồng 5: Xóa toàn bộ giỏ hàng
     */
    public function clear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->cartModel->clear();
            flash('success', 'Giỏ hàng đã được dọn sạch. ✨');
        }
        redirect_to('index.php?page=cart');
    }
}