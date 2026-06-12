<?php
require_once __DIR__ . '/Database.php';

class Product
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->connect();
    }

    public function getAll(int $limit = 100): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit');
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
    public function getHotproduct(): array
    {
        $allProducts = $this->getAll(100);
        if (empty($allProducts)) {
            return [];
        }

        // Đếm số sản phẩm thực tế trong DB
        $count = count($allProducts);
        // Nếu số sản phẩm nhỏ hơn 4, chỉ lấy tối đa số lượng đang có (tránh sập hàm array_rand)
        $numToPick = min($count, 4);

        $keys = array_rand($allProducts, $numToPick);

        // Ép kiểu về mảng phòng trường hợp array_rand chỉ trả về 1 số nguyên đơn lẻ
        $keys = (array)$keys;

        $hotProducts = [];
        foreach ($keys as $k) {
            $hotProducts[] = $allProducts[$k];
        }
        return $hotProducts;
    }

    public function search(string $keyword = '', string $brand = '', int $limit = 200): array
    {
        $keyword = trim($keyword);
        $brand = trim($brand);

        $where = [];
        $params = [];

        if ($keyword !== '') {
            $where[] = '(name LIKE :kw OR brand LIKE :kw OR cpu LIKE :kw)';
            $params[':kw'] = '%' . $keyword . '%';
        }

        if ($brand !== '') {
            $where[] = 'brand = :brand';
            $params[':brand'] = $brand;
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT * FROM products {$whereSql} ORDER BY id DESC LIMIT :limit";

        $statement = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_STR);
        }
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT); //bindValue dùng để gán giá trị trực tiếp vào tham số
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    // Thương hiệu
    public function getBrands(): array
    {
        $statement = $this->pdo->query('SELECT DISTINCT brand FROM products WHERE brand <> "" ORDER BY brand ASC');
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    // sản phẩm bán chạy nhất 
    public function getBestSellers(int $limit = 8): array
    {
        // Sắp xếp theo ID tăng dần hoặc giảm dần để lấy ra danh sách chữa cháy 
        // nếu cấu trúc database của bồ chưa bổ sung cột số lượng đã bán (sales)
        $statement = $this->pdo->prepare('SELECT * FROM products ORDER BY id ASC LIMIT :limit');
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sinh viên
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
        $image,
        string $description
    ) {
        // 1. KHÔNG có cột 'id' và 'sales' (để DB tự nhảy số)
        // 2. Đã thêm 'ram', 'rom'
        // 3. Đã bọc `condition` trong dấu nháy ngược
        // 4. Có đúng 14 cột và 14 dấu ?
        $sql = "INSERT INTO products 
            (name, brand, price, quantity, `condition`, rating, cpu, ram, rom, screen, battery, charger, image, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        // Truyền đúng 14 biến, đúng thứ tự với 14 cột ở trên
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
            $image,
            $description
        ]);
    }

// Thêm vào bên trong class Product

    /**
     * 1. CÁC HÀM CHO TRANG SHOP MẶC ĐỊNH
     */
    public function getTotalProductsCount(): int
    {
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM products");
        $statement->execute();
        return (int)$statement->fetchColumn();
    }

    public function getProductsByPage(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $statement = $this->pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 2. CÁC HÀM CHO TÌM KIẾM THƯỜNG (?page=shop&search=keyword)
     */
    public function getNormalSearchCount(string $keyword): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE :keyword");
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getNormalSearchProducts(string $keyword, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE name LIKE :keyword ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3. CÁC HÀM CHO BỘ LỌC NÂNG CAO
     */
    private function buildAdvancedWhereClause(array $filters): array
    {
        $sql = " WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $sql .= " AND name LIKE :name";
            $params[':name'] = "%" . $filters['name'] . "%";
        }
        if (!empty($filters['company'])) {
            $sql .= " AND brand = :company";
            $params[':company'] = $filters['company'];
        }
        if (!empty($filters['status'])) {
            // Cột condition được bọc trong dấu `` tránh trùng từ khóa hệ thống MySQL
            $sql .= " AND `condition` = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['price_min'])) {
            $sql .= " AND price >= :price_min";
            $params[':price_min'] = (int)$filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= :price_max";
            $params[':price_max'] = (int)$filters['price_max'];
        }
        return ['sql' => $sql, 'params' => $params];
    }

    public function getAdvancedSearchCount(array $filters): int
    {
        $where = $this->buildAdvancedWhereClause($filters);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products" . $where['sql']);
        foreach ($where['params'] as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getAdvancedSearchProducts(array $filters, int $page, int $perPage): array
    {
        $where = $this->buildAdvancedWhereClause($filters);
        $sql = "SELECT * FROM products" . $where['sql'];

        // Xử lý sắp xếp theo arrange
        if (!empty($filters['arrange'])) {
            if ($filters['arrange'] === 'price_asc') {
                $sql .= " ORDER BY price ASC";
            } elseif ($filters['arrange'] === 'price_desc') {
                $sql .= " ORDER BY price DESC";
            } else {
                $sql .= " ORDER BY id DESC";
            }
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        foreach ($where['params'] as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $offset = ($page - 1) * $perPage;
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
