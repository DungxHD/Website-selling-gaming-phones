<?php
/**
 * Home Controller
 * Handles homepage, shop, product detail, and cart operations
 */
class HomeController {
    private $productModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productModel = new Product($this->db);
    }

    /**
     * Display homepage
     */
    public function index() {
        $featuredProducts = $this->productModel->getFeatured(8);
        $bestSellingProducts = $this->productModel->getBestSelling(8);
        $newestProducts = $this->productModel->getNewest(8);
        
        include 'Views/user/home.php';
    }

    /**
     * Display shop page with filtering and sorting
     */
    public function shop() {
        $filters = [];
        
        // Get search term
        if (isset($_GET['search'])) {
            $filters['search'] = trim($_GET['search']);
        }
        
        // Get price range
        if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
            $filters['min_price'] = (int)$_GET['min_price'];
        }
        if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
            $filters['max_price'] = (int)$_GET['max_price'];
        }
        
        // Get condition filter
        if (isset($_GET['condition']) && $_GET['condition'] !== '') {
            $filters['condition'] = $_GET['condition'];
        }
        
        // Get sort option
        if (isset($_GET['sort']) && $_GET['sort'] !== '') {
            $filters['sort'] = $_GET['sort'];
        }
        
        $products = $this->productModel->getAll($filters);
        
        include 'Views/user/shop.php';
    }

    /**
     * Display product detail
     */
    public function detail() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=home&action=shop');
            exit;
        }
        
        $productId = (int)$_GET['id'];
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            header('Location: index.php?controller=home&action=shop');
            exit;
        }
        
        include 'Views/user/detail.php';
    }

    /**
     * Add to cart
     */
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($productId > 0 && $quantity > 0) {
                $product = $this->productModel->getById($productId);
                
                if ($product && $product['stock'] >= $quantity) {
                    // Initialize cart if not exists
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    
                    // Add or update cart item
                    if (isset($_SESSION['cart'][$productId])) {
                        $_SESSION['cart'][$productId]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$productId] = [
                            'product' => $product,
                            'quantity' => $quantity
                        ];
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Sản phẩm không đủ số lượng']);
                    exit;
                }
            }
            
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Display cart
     */
    public function cart() {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item['product']['price'] * $item['quantity'];
        }
        
        include 'Views/user/cart.php';
    }

    /**
     * Update cart item quantity
     */
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart'])) {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
            
            if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $product = $this->productModel->getById($productId);
                    if ($product && $product['stock'] >= $quantity) {
                        $_SESSION['cart'][$productId]['quantity'] = $quantity;
                        echo json_encode(['success' => true]);
                        exit;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Không đủ số lượng trong kho']);
                        exit;
                    }
                }
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart'])) {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            
            if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ']);
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Process checkout
     */
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Here you would typically create an order in the database
            // For now, we'll just clear the cart and show success
            
            $_SESSION['cart'] = [];
            $_SESSION['checkout_success'] = true;
            
            header('Location: index.php?controller=home&action=cart');
            exit;
        }
        
        header('Location: index.php?controller=home&action=cart');
        exit;
    }
}
?>
