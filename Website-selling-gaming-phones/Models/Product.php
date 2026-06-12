<?php

declare(strict_types=1);



require_once __DIR__ . '/Database.php';

// =========================================================
// NHẬN XÉT TIẾN ĐỘ - PRODUCT MODEL
// =========================================================
// 1) Nhận xét theo sơ đồ chức năng:
//    - File này đang phụ trách khá tốt phần dữ liệu cho nhóm chức năng Frontend:
//      + Trang chủ: lấy sản phẩm bán chạy, sản phẩm mới, sản phẩm nổi bật.
//      + Cửa hàng: lấy danh sách sản phẩm, tìm kiếm thường, tìm kiếm nâng cao, sắp xếp, phân trang.
//      + Chi tiết sản phẩm: lấy sản phẩm theo id và hỗ trợ lấy sản phẩm liên quan theo hãng.
//    - Như vậy, xét riêng phần dữ liệu sản phẩm thì tiến độ đang khá sát với sơ đồ tổng thể.
//    - Tuy nhiên file này hiện mới tập trung vào phần đọc dữ liệu sản phẩm.
//      Các phần CRUD sản phẩm cho admin chưa nằm ở đây hoặc chưa hoàn thiện trong file này.
//
// 2) Điểm làm tốt:
//    - Có dùng PDO + prepared statement, đây là cách làm đúng và an toàn hơn.
//    - Có tách hàm nhỏ như buildWordByWordWhere(), buildAdvancedWhereClause(), bindTypedParams().
//      Đây là điểm tốt vì giúp code đỡ lặp lại.
//    - Đã phân biệt được các nhóm xử lý:
//      + tìm kiếm thường,
//      + tìm kiếm nâng cao,
//      + lấy danh sách,
//      + phân trang,
//      + lấy sản phẩm theo id.
//
// 3) Điểm nên cải thiện:
//    - Tên hàm chưa đồng bộ hoàn toàn, ví dụ getHotproduct() nên đổi thành getHotProducts().
//    - Một số chức năng đang bị trùng ý tưởng, ví dụ:
//      + getAll(), getProductsByPage(), search(), getNormalSearchProducts(), getAdvancedSearchProducts()
//      => Điều này chưa sai, nhưng về sau dễ làm file dài và khó bảo trì.
//    - Hàm getHotproduct() hiện lấy ngẫu nhiên từ toàn bộ sản phẩm.
//      Nếu theo đúng nghĩa "sản phẩm nổi bật", về sau nên có tiêu chí rõ hơn
//      như theo rating cao, sales cao hoặc cột is_featured.
//    - Các comment kỹ thuật hiện có khá chi tiết, tốt cho học tập,
//      nhưng nên thống nhất ngôn ngữ và cách trình bày để file chuyên nghiệp hơn.
//
// 4) Hướng sửa về sau:
//    - Bước 1: Chuẩn hóa lại tên hàm và tên filter.
//    - Bước 2: Cân nhắc gom những hàm tìm kiếm gần giống nhau để tránh trùng logic.
//    - Bước 3: Bổ sung các hàm CRUD sản phẩm nếu muốn file này phục vụ thêm phần admin.
//    - Bước 4: Nếu dự án lớn hơn, có thể tách ProductQuery hoặc ProductRepository để Model gọn hơn.

class Product
{

    public function __construct(
        private ?PDO $pdo = null
    ) {
        $this->pdo ??= (new Database())->connect();
    }

    // TÌM KIẾM THƯỜNG THEO TÊN SẢN PHẨM

    public function getNormalSearchCount(string $keyword): int
    {
        // NHẬN XÉT:
        // - Hàm này phục vụ tốt cho chức năng tìm kiếm theo tên/hãng trong sơ đồ.
        // - Có kiểm tra keyword rỗng ngay từ đầu, giúp tránh query thừa.
        // - Gợi ý sửa sau: nếu muốn tìm theo cả hãng đúng như mô tả chức năng,
        //   có thể mở rộng buildWordByWordWhere() hoặc viết điều kiện cho cả name và brand.
        if (trim($keyword) === '') {
            return 0;
        }

        // 🎯 Array Destructuring: Tách mảng trả về thành 2 biến riêng
        // [$whereSql, $params] tương đương với:
        //   $result = $this->buildWordByWordWhere(...);
        //   $whereSql = $result[0];
        //   $params = $result[1];
        [$whereSql, $params] = $this->buildWordByWordWhere($keyword, 'name');

        // NHẬN XÉT:
        // - Dùng prepared statement là đúng hướng.
        // - Khi viết báo cáo, bạn có thể ghi đây là điểm đảm bảo an toàn dữ liệu đầu vào.
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products {$whereSql}");
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }

    public function getNormalSearchProducts(string $keyword, int $page, int $perPage): array
    {
        // NHẬN XÉT:
        // - Hàm này xử lý tốt phần tìm kiếm thường + phân trang.
        // - Đây là phần bám khá đúng với sơ đồ chức năng "Cửa hàng & Tìm kiếm".
        // - Gợi ý sửa sau: nên kiểm tra $page và $perPage luôn >= 1 ngay trong Model
        //   để tránh trường hợp dữ liệu truyền vào không hợp lệ.
        if (trim($keyword) === '') {
            return [];
        }

        [$whereSql, $params] = $this->buildWordByWordWhere($keyword, 'name');

        // Build SQL đầy đủ với ORDER BY + phân trang
        $sql = "SELECT * FROM products {$whereSql} ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        // Bind params cho từ khóa (tất cả là STRING)
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        // Bind params cho phân trang (BẮT BUỘC dùng PARAM_INT)
        // Nếu bind string "12" vào LIMIT khi EMULATE_PREPARES=false → MySQL lỗi
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // TÌM KIẾM NÂNG CAO

    public function getAdvancedSearchCount(array $filters): int
    {
        // NHẬN XÉT:
        // - Hàm này cho thấy bạn đã làm tới phần tìm kiếm nâng cao, đây là tiến độ tốt.
        // - Đúng với sơ đồ: đã có lọc theo giá và tình trạng máy.
        // - Gợi ý sửa sau: có thể bổ sung lọc theo hãng trực tiếp bằng tên rõ ràng hơn
        //   nếu muốn khớp tuyệt đối với mô tả trong giao diện.
        [$whereSql, $params] = $this->buildAdvancedWhereClause($filters);

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products {$whereSql}");
        $this->bindTypedParams($stmt, $params);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
                                                 // Điều kiện, số trang, số trang/1page(Được truyền từ FrontendController) 
    public function getAdvancedSearchProducts(array $filters, int $page, int $perPage): array
    {
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

        // Bind params filter (tự động nhận diện kiểu)
        $this->bindTypedParams($stmt, $params);

        // Bind params phân trang (bắt buộc INT)
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildWordByWordWhere(string $keyword, string $column): array
    {
        // NHẬN XÉT:
        // - Việc tách hàm private như thế này là điểm tốt, giúp tránh lặp code.
        // - Đây là dấu hiệu bạn đã biết tổ chức code theo từng nhiệm vụ nhỏ.
        $words = preg_split('/\s+/', trim($keyword), -1, PREG_SPLIT_NO_EMPTY);
        $conditions = [];
        $params = [];

        foreach ($words as $i => $word) {
            $conditions[] = "{$column} LIKE :w{$i}";
            $params[":w{$i}"] = "%{$word}%";
        }

        $whereSql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : ''; // Câu lệnh truy vấn Database
        return [$whereSql, $params];
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
        $params = [];

        foreach ($filterMap as $key => [$column, $operator, $formatter]) {
            if (!isset($filters[$key]) || $filters[$key] === '') {
                continue;
            }

            $conditions[] = "{$column} {$operator} :{$key}";
            $params[":{$key}"] = $formatter($filters[$key]);
        }

        $whereSql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        return [$whereSql, $params];
    }

    private function bindTypedParams(\PDOStatement $stmt, array $params): void
    {
        // NHẬN XÉT:
        // - Hàm này thể hiện cách làm cẩn thận khi bind đúng kiểu dữ liệu.
        // - Đây là điểm tốt về kỹ thuật vì tránh lỗi khi truyền số vào SQL.
        foreach ($params as $key => $value) {
            // Tự động chọn kiểu dựa trên giá trị
            // (int)10000000 → PARAM_INT, "ASUS" → PARAM_STR
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }
    }


    public function getAll(int $limit = 100): array
    {
        // NHẬN XÉT:
        // - Hàm này phù hợp cho việc lấy danh sách sản phẩm mới nhất.
        // - Đang phục vụ tốt cho Trang chủ và các trường hợp cần lấy nhanh dữ liệu.
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        // NHẬN XÉT:
        // - Hàm này là bắt buộc cho chức năng xem chi tiết sản phẩm.
        // - Viết ngắn gọn, đúng nhiệm vụ của Model.
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBrands(): array
    {
        // NHẬN XÉT:
        // - Hàm này hỗ trợ tốt cho phần bộ lọc hãng ở trang cửa hàng.
        // - Nếu sau này có nhiều dữ liệu rác, nên cân nhắc trim brand ngay từ khi lưu dữ liệu.
        return $this->pdo
            ->query('SELECT DISTINCT brand FROM products WHERE brand <> "" ORDER BY brand ASC')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getBestSellers(int $limit = 8): array
    {
        // NHẬN XÉT:
        // - Hàm này bám rất đúng yêu cầu "Sản phẩm đang được bán chạy nhất" ở Trang chủ.
        // - Đây là một điểm tiến độ tốt theo sơ đồ chức năng tổng thể.
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY sales DESC, id DESC LIMIT :limit');
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHotproducts(): array
    {
        // NHẬN XÉT:
        // - Hàm này đang dùng để tạo nhóm sản phẩm nổi bật/ngẫu nhiên cho Trang chủ.
        // - Cách làm này phù hợp nếu mục tiêu là làm giao diện sinh động nhanh cho ASM.
        // - Tuy nhiên về nghĩa nghiệp vụ thì "hot product" không nên hoàn toàn ngẫu nhiên.
        //   Gợi ý sửa sau: nên chọn theo sales, rating hoặc gắn cờ nổi bật trong database.
        $all = $this->getAll(100);
        if (empty($all)) return [];
        $numToPick = min(count($all), 4);
        $keys = (array)array_rand($all, $numToPick);
        return array_map(fn($k) => $all[$k], $keys);
    }

    public function getTotalProductsCount(): int
    {
        // NHẬN XÉT:
        // - Hàm này phục vụ đúng cho phần phân trang danh sách sản phẩm.
        // - Viết ngắn gọn và hợp lý.
        return (int)$this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }

    public function getProductsByPage(int $page, int $perPage): array
    {
        // NHẬN XÉT:
        // - Hàm này hỗ trợ tốt cho phần xem danh sách sản phẩm có phân trang.
        // - Gợi ý sửa sau: có thể kiểm tra an toàn cho $page và $perPage giống các hàm khác.
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $keyword = '', string $brand = '', int $limit = 200): array
    {
        // NHẬN XÉT:
        // - Hàm này đang khá tiện vì dùng lại được cho nhiều nơi như tìm theo hãng hoặc sản phẩm liên quan.
        // - Tuy nhiên hiện tại chức năng tìm kiếm đang bị tách thành khá nhiều hàm khác nhau.
        // - Gợi ý sửa sau: cân nhắc gom logic để tránh trùng lặp giữa search(),
        //   getNormalSearchProducts() và getAdvancedSearchProducts().
        $where = [];
        $params = [];

        if (trim($keyword) !== '') {
            $where[] = '(name LIKE :kw OR brand LIKE :kw OR cpu LIKE :kw)';
            $params[':kw'] = '%' . trim($keyword) . '%';
        }

        if (trim($brand) !== '') {
            $where[] = 'brand = :brand';
            $params[':brand'] = trim($brand);
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT * FROM products {$whereSql} ORDER BY id DESC LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
