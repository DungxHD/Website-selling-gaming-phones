<?php  
class AdminModel
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
    ): void {
        $sql = "INSERT INTO products
            (name, brand, price, quantity, `condition`, rating, cpu, ram, rom, screen, battery, charger, image, description)
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
        $stmt = $this->pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product && !empty($product['image']) && file_exists(__DIR__ . '/../' . $product['image'])) {
            @unlink(__DIR__ . '/../' . $product['image']);
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 3. Hàm cập nhật sản phẩm
    public function updateProduct(
        int $id, string $name, string $brand, int $price, int $quantity,
        string $condition, int $rating, string $cpu, string $ram, string $rom,
        string $screen, string $battery, string $charger, $image, string $description
    ): bool {
        $sql = "UPDATE products SET
            name = ?, brand = ?, price = ?, quantity = ?, `condition` = ?,
            rating = ?, cpu = ?, ram = ?, rom = ?, screen = ?,
            battery = ?, charger = ?, image = ?, description = ?
            WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $name, $brand, $price, $quantity, $condition, $rating,
            $cpu, $ram, $rom, $screen, $battery, $charger, $image, $description, $id
        ]);
    }

    // =========================================================
    // NHÓM HÀM THỐNG KÊ CHO DASHBOARD
    // =========================================================

    /**
     * Lấy tất cả thống kê tổng quan cho dashboard
     */
    public function getDashboardStats(): array
    {
        // Đếm số sản phẩm
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products");
        $productCount = (int)$stmt->fetchColumn();

        // Tổng tồn kho
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM products");
        $totalStock = (int)$stmt->fetchColumn();

        // Đếm số người dùng
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $userCount = (int)$stmt->fetchColumn();

        // Đếm tài khoản bị khóa
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE is_active = 0");
        $stmt->execute();
        $inactiveUserCount = (int)$stmt->fetchColumn();

        // Đếm tổng đơn hàng
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders");
        $orderCount = (int)$stmt->fetchColumn();

        // Đếm đơn đang giao
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'shipping'");
        $stmt->execute();
        $shippingOrderCount = (int)$stmt->fetchColumn();

        // Đếm đơn hoàn tất
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $completedOrderCount = (int)$stmt->fetchColumn();

        // Doanh thu (chỉ tính đơn đã hoàn tất)
        $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $revenue = (int)$stmt->fetchColumn();

        return [
            'productCount'        => $productCount,
            'totalStock'          => $totalStock,
            'userCount'           => $userCount,
            'inactiveUserCount'   => $inactiveUserCount,
            'orderCount'          => $orderCount,
            'shippingOrderCount'  => $shippingOrderCount,
            'completedOrderCount' => $completedOrderCount,
            'revenue'             => $revenue,
        ];
    }

    /**
     * Lấy danh sách sản phẩm bán chạy nhất
     */
    public function getTopSellingProducts(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY sales DESC, id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách đơn hàng mới nhất (kèm số lượng sản phẩm trong đơn)
     */
    public function getLatestOrders(int $limit = 5): array
    {
        $sql = "SELECT o.*, 
                       COUNT(oi.id) AS item_count
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                GROUP BY o.id
                ORDER BY o.created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}