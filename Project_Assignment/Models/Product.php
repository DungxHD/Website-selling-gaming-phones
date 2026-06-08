<?php
/**
 * Product Model
 * Handles all product-related database operations
 */
class Product {
    private $conn;
    private $table = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all products with filtering and sorting
     */
    public function getAll($filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        
        $conditions = [];
        $params = [];

        // Filter by search term
        if (!empty($filters['search'])) {
            $conditions[] = "(name LIKE :search OR brand LIKE :search_brand)";
            $params[':search'] = "%" . $filters['search'] . "%";
            $params[':search_brand'] = "%" . $filters['search'] . "%";
        }

        // Filter by price range
        if (!empty($filters['min_price'])) {
            $conditions[] = "price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $conditions[] = "price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        // Filter by condition (new/used)
        if (!empty($filters['condition'])) {
            $conditions[] = "condition = :condition";
            $params[':condition'] = $filters['condition'];
        }

        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }

        // Sorting
        $sort = !empty($filters['sort']) ? $filters['sort'] : 'best_selling';
        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'best_selling':
            default:
                $query .= " ORDER BY sold_count DESC, created_at DESC";
                break;
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get product by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Get featured products (for homepage)
     */
    public function getFeatured($limit = 8) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE is_featured = 1 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Get best selling products
     */
    public function getBestSelling($limit = 8) {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY sold_count DESC, created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Get newest products
     */
    public function getNewest($limit = 8) {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Create new product
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (name, brand, price, cpu, screen, battery, charging_power, 
                   description, image, condition, stock, is_featured, sold_count) 
                  VALUES (:name, :brand, :price, :cpu, :screen, :battery, 
                          :charging_power, :description, :image, :condition, 
                          :stock, :is_featured, :sold_count)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':brand', $data['brand']);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_INT);
        $stmt->bindValue(':cpu', $data['cpu']);
        $stmt->bindValue(':screen', $data['screen']);
        $stmt->bindValue(':battery', $data['battery']);
        $stmt->bindValue(':charging_power', $data['charging_power']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':image', $data['image']);
        $stmt->bindValue(':condition', $data['condition']);
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':is_featured', isset($data['is_featured']) ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':sold_count', isset($data['sold_count']) ? $data['sold_count'] : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Update product
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, brand = :brand, price = :price, 
                      cpu = :cpu, screen = :screen, battery = :battery, 
                      charging_power = :charging_power, description = :description, 
                      image = :image, condition = :condition, stock = :stock, 
                      is_featured = :is_featured 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':brand', $data['brand']);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_INT);
        $stmt->bindValue(':cpu', $data['cpu']);
        $stmt->bindValue(':screen', $data['screen']);
        $stmt->bindValue(':battery', $data['battery']);
        $stmt->bindValue(':charging_power', $data['charging_power']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':image', $data['image']);
        $stmt->bindValue(':condition', $data['condition']);
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':is_featured', isset($data['is_featured']) ? 1 : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Update stock
     */
    public function updateStock($id, $quantity) {
        $query = "UPDATE " . $this->table . " 
                  SET stock = stock - :quantity, sold_count = sold_count + :quantity 
                  WHERE id = :id AND stock >= :quantity";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
?>
