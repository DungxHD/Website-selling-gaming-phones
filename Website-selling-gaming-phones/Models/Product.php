<?php
declare(strict_types=1);

class Product
{
    private const HOT_PRODUCTS_LIMIT = 4; // Hiển thị danh sách sản phẩm hot

    public function __construct(
        private ?PDO $pdo = null
    ) {
        $this->pdo ??= (new Database())->connect();
    }

    public function getAll(int $limit = 100): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBrands(): array
    {
        return $this->pdo
            ->query('SELECT DISTINCT brand FROM products WHERE brand <> "" ORDER BY brand ASC')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getBestSellers(int $limit = 8): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY sales DESC, id DESC LIMIT :limit');
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHotProducts(int $limit = self::HOT_PRODUCTS_LIMIT): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY rating DESC, sales DESC, id DESC LIMIT :limit');
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalProductsCount(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }

    public function getProductsByPage(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // NHÓM HÀM TÌM KIẾM VÀ LỌC SẢN PHẨM
    // =========================================================

    public function getNormalSearchCount(string $keyword): int
    {
        if (trim($keyword) === '') {
            return 0;
        }
        [$whereSql, $params] = $this->buildWordByWordWhere($keyword, 'name');
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products {$whereSql}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn(); // Trả về số lượng sản phẩm tìm thấy từ $keyword
    }

    public function getNormalSearchProducts(string $keyword, int $page, int $perPage): array
    {
        if (trim($keyword) === '') {
            return [];
        }

        [$whereSql, $params] = $this->buildWordByWordWhere($keyword, 'name');

        $sql = "SELECT * FROM products {$whereSql} ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng chứa các sản phẩm nằm trong $keyword
    }

    // TÌM KIẾM NÂNG CAO
    public function getAdvancedSearchCount(array $filters): int
    {
        $filters = $this->normalizeAdvancedFilters($filters);
        [$whereSql, $params] = $this->buildAdvancedWhereClause($filters);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products {$whereSql}");
        $this->bindTypedParams($stmt, $params);
        $stmt->execute();

        return (int)$stmt->fetchColumn(); // Trả về số lượng sản phẩm tìm thấy từ $keyword
    }

                                                 // Điều kiện, số trang, số sản phẩm/1page(Được truyền từ FrontendController) 
    public function getAdvancedSearchProducts(array $filters, int $page, int $perPage): array
    {
        $filters = $this->normalizeAdvancedFilters($filters);
        [$whereSql, $params] = $this->buildAdvancedWhereClause($filters);

        $orderBy = match ($filters['arrange'] ?? '') {
            'price_asc'  => 'ORDER BY price ASC',     // Giá thấp → cao
            'price_desc' => 'ORDER BY price DESC',    // Giá cao → thấp
            'rating'     => 'ORDER BY rating DESC',   // Đánh giá cao
            'sales'      => 'ORDER BY sales DESC',    // Bán chạy nhất
            default      => 'ORDER BY id DESC',       // Mặc định: mới nhất
        };

        $sql = "SELECT * FROM products {$whereSql} {$orderBy} LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $this->bindTypedParams($stmt, $params);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng chứa các sản phẩm nằm trong $keyword
    }

    public function getRelatedProductsByBrand(string $brand, int $excludeId = 0, int $limit = 8): array
    {
        if (trim($brand) === '') {
            return [];
        }

        $sql = 'SELECT * FROM products WHERE brand = :brand AND id <> :exclude_id ORDER BY sales DESC, id DESC LIMIT :limit';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':brand', trim($brand), PDO::PARAM_STR);
        $stmt->bindValue(':exclude_id', max(0, $excludeId), PDO::PARAM_INT);
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // NHÓM HÀM HỖ TRỢ NỘI BỘ CHO TÌM KIẾM
    // =========================================================

    // Hàm xử lý logic tìm kiếm theo đơn giản theo tên
    private function buildWordByWordWhere(string $keyword, string $column): array
    {
        $words = preg_split('/\s+/', trim($keyword), -1, PREG_SPLIT_NO_EMPTY); // Tách $keyword xóa space
        $conditions = [];
        $params = [];

        foreach ($words as $i => $word) {
            $conditions[] = "{$column} LIKE :w{$i}";
            $params[":w{$i}"] = "%{$word}%"; // Giá trị cần tìm của cột name trong database
        }

        $whereSql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : ''; // Câu lệnh truy vấn Database
        return [$whereSql, $params]; // Trả về câu lệnh truy vấn và mảng chứa giá trị cần lọc
    }

    // Hàm kiểm tra các điều kiện của bộ lọc tìm kiếm nâng cao
    private function buildAdvancedWhereClause(array $filters): array
    {
        $filterMap = [
            'name'      => ['name',          'LIKE', fn($v) => "%{$v}%"],
            'company'   => ['brand',         '=',    fn($v) => $v],
            'status'    => ['`condition`',   '=',    fn($v) => $v],
            'price_min' => ['price',         '>=',   fn($v) => (int)$v],
            'price_max' => ['price',         '<=',   fn($v) => (int)$v],
        ];

        $conditions = [];
        $params = []; // value

        foreach ($filterMap as $key => [$column, $operator, $formatter]) {
            if (!isset($filters[$key]) || $filters[$key] === '') {
                continue;
            }

            $conditions[] = "{$column} {$operator} :{$key}"; // Gom các câu điều kiện lại một mảng
            $params[":{$key}"] = $formatter($filters[$key]); // Giá trị cần tìm của các cột điều kiện $filters
        }

        $whereSql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : ''; // Câu lệnh truy vấn Database (Ghép thành một câu truy vấn hoàn chỉnh)

        return [$whereSql, $params]; // Trả về câu lệnh truy vấn và mảng chứa giá trị cần lọc
    }

    // Nhận giá trị từ bộ lọc từ tìm kiếm nâng cao
    private function normalizeAdvancedFilters(array $filters): array
    {
        return [
            'name' => trim((string)($filters['name'] ?? $filters['search'] ?? '')),
            'company' => trim((string)($filters['company'] ?? $filters['brand'] ?? '')),
            'status' => trim((string)($filters['status'] ?? $filters['condition'] ?? '')),
            'price_min' => trim((string)($filters['price_min'] ?? $filters['min_price'] ?? '')),
            'price_max' => trim((string)($filters['price_max'] ?? $filters['max_price'] ?? '')),
            'arrange' => trim((string)($filters['arrange'] ?? $filters['sort'] ?? '')),
        ];
    }

    // Hàm nạp dữ liệu của tìm kiếm thường
    private function bindTypedParams(\PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR; // Check kiểu dữ liệu của value(is_int kiểm tra $value và trả về bool)
            $stmt->bindValue($key, $value, $type); // Tạo thành kiểu dữ liệu đúng
        }
    }
}
