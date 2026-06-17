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
        string $name,
        string $brand,
        int $price,
        int $quantity,
        string $condition,
        int $rating,
        string $cpu,
        string $ram,
        string $rom,
        string $screen,
        string $battery,
        string $charger,
        string $camera,
        $image,
        string $description,
        array $detailSpecs = []
    ): void {
        $detailSpecs = $this->normalizeDetailSpecs($detailSpecs);

        $sql = "INSERT INTO products
            (
                name, brand, price, quantity, `condition`, rating, cpu, ram, rom, screen, battery, charger, camera, image, description,
                screen_ratio, screen_tech, screen_resolution, screen_glass, design_material, dimensions, weight,
                cam_rear_count, cam_rear_features, cam_rear_video, cam_front_specs, cam_front_video, cam_front_features,
                os, cpu_speed, gpu, network, sim, wifi, bluetooth, port_charging, port_audio, gps,
                charging_tech, memory_card, security, water_resistance, extra_features
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $name,
            $brand,
            $price,
            $quantity,
            $condition,
            $rating,
            $cpu,
            $ram,
            $rom,
            $screen,
            $battery,
            $charger,
            $camera,
            $image,
            $description,
            $detailSpecs['screen_ratio'],
            $detailSpecs['screen_tech'],
            $detailSpecs['screen_resolution'],
            $detailSpecs['screen_glass'],
            $detailSpecs['design_material'],
            $detailSpecs['dimensions'],
            $detailSpecs['weight'],
            $detailSpecs['cam_rear_count'],
            $detailSpecs['cam_rear_features'],
            $detailSpecs['cam_rear_video'],
            $detailSpecs['cam_front_specs'],
            $detailSpecs['cam_front_video'],
            $detailSpecs['cam_front_features'],
            $detailSpecs['os'],
            $detailSpecs['cpu_speed'],
            $detailSpecs['gpu'],
            $detailSpecs['network'],
            $detailSpecs['sim'],
            $detailSpecs['wifi'],
            $detailSpecs['bluetooth'],
            $detailSpecs['port_charging'],
            $detailSpecs['port_audio'],
            $detailSpecs['gps'],
            $detailSpecs['charging_tech'],
            $detailSpecs['memory_card'],
            $detailSpecs['security'],
            $detailSpecs['water_resistance'],
            $detailSpecs['extra_features'],
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
        int $id,
        string $name,
        string $brand,
        int $price,
        int $quantity,
        string $condition,
        int $rating,
        string $cpu,
        string $ram,
        string $rom,
        string $screen,
        string $battery,
        string $charger,
        string $camera,
        $image,
        string $description,
        array $detailSpecs = []
    ): bool {
        $detailSpecs = $this->normalizeDetailSpecs($detailSpecs);

        $sql = "UPDATE products SET
            name = ?, brand = ?, price = ?, quantity = ?, `condition` = ?,
            rating = ?, cpu = ?, ram = ?, rom = ?, screen = ?,
            battery = ?, charger = ?, camera = ?, image = ?, description = ?,
            screen_ratio = ?, screen_tech = ?, screen_resolution = ?, screen_glass = ?, design_material = ?, dimensions = ?, weight = ?,
            cam_rear_count = ?, cam_rear_features = ?, cam_rear_video = ?, cam_front_specs = ?, cam_front_video = ?, cam_front_features = ?,
            os = ?, cpu_speed = ?, gpu = ?, network = ?, sim = ?, wifi = ?, bluetooth = ?, port_charging = ?, port_audio = ?, gps = ?,
            charging_tech = ?, memory_card = ?, security = ?, water_resistance = ?, extra_features = ?
            WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $name,
            $brand,
            $price,
            $quantity,
            $condition,
            $rating,
            $cpu,
            $ram,
            $rom,
            $screen,
            $battery,
            $charger,
            $camera,
            $image,
            $description,
            $detailSpecs['screen_ratio'],
            $detailSpecs['screen_tech'],
            $detailSpecs['screen_resolution'],
            $detailSpecs['screen_glass'],
            $detailSpecs['design_material'],
            $detailSpecs['dimensions'],
            $detailSpecs['weight'],
            $detailSpecs['cam_rear_count'],
            $detailSpecs['cam_rear_features'],
            $detailSpecs['cam_rear_video'],
            $detailSpecs['cam_front_specs'],
            $detailSpecs['cam_front_video'],
            $detailSpecs['cam_front_features'],
            $detailSpecs['os'],
            $detailSpecs['cpu_speed'],
            $detailSpecs['gpu'],
            $detailSpecs['network'],
            $detailSpecs['sim'],
            $detailSpecs['wifi'],
            $detailSpecs['bluetooth'],
            $detailSpecs['port_charging'],
            $detailSpecs['port_audio'],
            $detailSpecs['gps'],
            $detailSpecs['charging_tech'],
            $detailSpecs['memory_card'],
            $detailSpecs['security'],
            $detailSpecs['water_resistance'],
            $detailSpecs['extra_features'],
            $id
        ]);
    }

    // =========================================================
    // NHÓM HÀM THỐNG KÊ CHO DASHBOARD
    // =========================================================

    public function getDashboardStats(): array
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products");
        $productCount = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM products");
        $totalStock = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $userCount = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE is_active = 0");
        $stmt->execute();
        $inactiveUserCount = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders");
        $orderCount = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'shipping'");
        $stmt->execute();
        $shippingOrderCount = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $completedOrderCount = (int)$stmt->fetchColumn();

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

    public function getTopSellingProducts(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY sales DESC, id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    // =========================================================
    // HÀM LẤY TẤT CẢ SẢN PHẨM (CHO ADMIN)
    // =========================================================
    
    /**
     * Lấy tất cả sản phẩm
     */
    public function getAll(int $limit = 100): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // HÀM TÌM KIẾM SẢN PHẨM (ADMIN)
    // =========================================================

    /**
     * Tìm kiếm sản phẩm theo tên (tìm theo từng từ)
     * VD: "poco f6" → tìm sản phẩm có cả "poco" VÀ "f6" trong tên/hãng/chip
     */
    public function searchProducts(string $keyword): array
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return $this->getAll(100);
        }

        // Tách từ khóa thành các từ riêng biệt
        $words = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($words)) {
            return $this->getAll(100);
        }

        // Xây dựng câu WHERE với positional parameters (?)
        $conditions = [];
        $params = [];
        
        foreach ($words as $word) {
            // Mỗi từ cần 3 placeholder riêng cho 3 cột
            $conditions[] = "(name LIKE ? OR brand LIKE ? OR cpu LIKE ?)";
            $searchTerm = "%{$word}%";
            $params[] = $searchTerm; // cho name
            $params[] = $searchTerm; // cho brand
            $params[] = $searchTerm; // cho cpu
        }

        $whereSql = 'WHERE ' . implode(' AND ', $conditions);
        $sql = "SELECT * FROM products {$whereSql} ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // HÀM LỌC DANH MỤC HÃNG + SẮP XẾP (ADMIN)
    // =========================================================
    public function filterProducts(string $keyword = '', string $brand = '', string $sortBy = 'newest'): array
    {
        $keyword = trim($keyword);
        $brand = trim($brand);

        $conditions = [];
        $params = [];

        if ($keyword !== '') {
            $words = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($words as $word) {
                $conditions[] = "(name LIKE ? OR brand LIKE ? OR cpu LIKE ?)";
                $searchTerm = "%{$word}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
        }

        if ($brand !== '') {
            $conditions[] = "brand = ?";
            $params[] = $brand;
        }

        $whereSql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $orderBy = $this->buildAdminProductOrderBy($sortBy);
        $sql = "SELECT * FROM products {$whereSql} {$orderBy}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductBrands(): array
    {
        return $this->pdo
            ->query("SELECT DISTINCT brand FROM products WHERE brand <> '' ORDER BY brand ASC")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    // Gom phần chuẩn hóa dữ liệu kỹ thuật về 1 chỗ để tránh sai lệch giữa Thêm và Sửa sản phẩm
    private function normalizeDetailSpecs(array $detailSpecs): array
    {
        $defaults = [
            'screen_ratio' => null,
            'screen_tech' => null,
            'screen_resolution' => null,
            'screen_glass' => null,
            'design_material' => null,
            'dimensions' => null,
            'weight' => null,
            'cam_rear_count' => null,
            'cam_rear_features' => null,
            'cam_rear_video' => null,
            'cam_front_specs' => null,
            'cam_front_video' => null,
            'cam_front_features' => null,
            'os' => null,
            'cpu_speed' => null,
            'gpu' => null,
            'network' => null,
            'sim' => null,
            'wifi' => null,
            'bluetooth' => null,
            'port_charging' => null,
            'port_audio' => null,
            'gps' => null,
            'charging_tech' => null,
            'memory_card' => null,
            'security' => null,
            'water_resistance' => null,
            'extra_features' => null,
        ];

        $normalized = array_merge($defaults, $detailSpecs);

        foreach ($normalized as $key => $value) {
            if ($key === 'cam_rear_count') {
                $normalized[$key] = $value === null || $value === '' ? null : (int)$value;
                continue;
            }

            $value = trim((string)($value ?? ''));
            $normalized[$key] = $value === '' ? null : $value;
        }

        return $normalized;
    }

    private function buildAdminProductOrderBy(string $sortBy): string
    {
        return match ($sortBy) {
            'price_asc'  => 'ORDER BY price ASC, id DESC',
            'price_desc' => 'ORDER BY price DESC, id DESC',
            'stock_desc' => 'ORDER BY quantity DESC, id DESC',
            default      => 'ORDER BY id DESC',
        };
    }
}
