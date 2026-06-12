<?php

require_once __DIR__ . '/Database.php';

class AdminProduct
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->connect();
    }

    // 1. Hàm thêm sản phẩm
    public function addProduct(
        string $name, string $brand, int $price, int $quantity, string $condition,
        int $rating, string $cpu, string $ram, string $rom, string $screen,
        string $battery, string $charger, $image, string $description
    ) {
        $sql = "INSERT INTO products 
            (name, brand, price, quantity, `condition`, rating, cpu, ram, rom, screen, battery, charger, image, description) -- `condition` là từ khóa key có thể gây ra sung đột `` dùng để bao quanh tên định danh 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $name, $brand, $price, $quantity, $condition, $rating, 
            $cpu, $ram, $rom, $screen, $battery, $charger, $image, $description
        ]);
    }

    // 2. Hàm xóa sản phẩm
    public function deleteProducts(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 3. Hàm cập nhật sản phẩm
    public function updateProduct(
        int $id, string $name, string $brand, int $price, int $quantity,
        string $condition, int $rating, string $cpu, string $ram, string $rom,
        string $screen, string $battery, string $charger, $image, string $description
    ): bool { // bool trả về giá trị true hoặc false
        $sql = "UPDATE products SET 
                name = ?, brand = ?, price = ?, quantity = ?, `condition` = ?, 
                rating = ?, cpu = ?, ram = ?, rom = ?, screen = ?, 
                battery = ?, charger = ?, image = ?, description = ? 
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql); // prepare chuẩn bị câu lệnh mysql
        return $stmt->execute([ // execute thực thi câu lệnh 
            $name, $brand, $price, $quantity, $condition, $rating, 
            $cpu, $ram, $rom, $screen, $battery, $charger, $image, $description, $id
        ]);
    }
}
