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
        $keys = array_rand($this->getAll(), 4);
        $hotProducts = [];
        foreach ($keys as $k) {
            $hotProducts[] = $this->getAll()[$k];
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
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
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
        $sql = "SELECT * FROM products 
                ORDER BY sales DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}