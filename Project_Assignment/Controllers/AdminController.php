<?php
/**
 * Admin Controller
 * Handles admin dashboard, product management, order management, and user management
 */
class AdminController {
    private $productModel;
    private $userModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productModel = new Product($this->db);
        $this->userModel = new User($this->db);
    }

    /**
     * Check if user is admin
     */
    private function checkAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?controller=user&action=auth');
            exit;
        }
    }

    /**
     * Display admin login page
     */
    public function login() {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            header('Location: index.php?controller=admin&action=dashboard');
            exit;
        }
        
        include 'Views/admin/login.php';
    }

    /**
     * Process admin login
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
                exit;
            }
            
            $result = $this->userModel->verifyLogin($email, $password);
            
            if ($result === 'inactive') {
                echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa']);
                exit;
            } elseif ($result && $result['role'] === 'admin') {
                $_SESSION['user'] = [
                    'id' => $result['id'],
                    'full_name' => $result['full_name'],
                    'email' => $result['email'],
                    'role' => $result['role']
                ];
                
                echo json_encode(['success' => true, 'redirect' => 'index.php?controller=admin&action=dashboard']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng (hoặc không phải admin)']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Display dashboard with statistics
     */
    public function dashboard() {
        $this->checkAdmin();
        
        // Get statistics
        $totalProducts = count($this->productModel->getAll());
        $totalUsers = count($this->userModel->getAll());
        $totalOrders = isset($_SESSION['orders']) ? count($_SESSION['orders']) : 0;
        $revenue = isset($_SESSION['total_revenue']) ? $_SESSION['total_revenue'] : 0;
        
        $recentProducts = $this->productModel->getNewest(5);
        
        include 'Views/admin/dashboard.php';
    }

    /**
     * Display product list
     */
    public function products() {
        $this->checkAdmin();
        
        $products = $this->productModel->getAll();
        
        include 'Views/admin/products.php';
    }

    /**
     * Add new product
     */
    public function addProduct() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
                'brand' => isset($_POST['brand']) ? trim($_POST['brand']) : '',
                'price' => isset($_POST['price']) ? (int)$_POST['price'] : 0,
                'cpu' => isset($_POST['cpu']) ? trim($_POST['cpu']) : '',
                'screen' => isset($_POST['screen']) ? trim($_POST['screen']) : '',
                'battery' => isset($_POST['battery']) ? trim($_POST['battery']) : '',
                'charging_power' => isset($_POST['charging_power']) ? trim($_POST['charging_power']) : '',
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'image' => isset($_POST['image']) ? trim($_POST['image']) : 'default.jpg',
                'condition' => isset($_POST['condition']) ? $_POST['condition'] : 'new',
                'stock' => isset($_POST['stock']) ? (int)$_POST['stock'] : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];
            
            if ($this->productModel->create($data)) {
                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm']);
                exit;
            }
        }
        
        include 'Views/admin/product_form.php';
    }

    /**
     * Edit product
     */
    public function editProduct() {
        $this->checkAdmin();
        
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=admin&action=products');
            exit;
        }
        
        $productId = (int)$_GET['id'];
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            header('Location: index.php?controller=admin&action=products');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
                'brand' => isset($_POST['brand']) ? trim($_POST['brand']) : '',
                'price' => isset($_POST['price']) ? (int)$_POST['price'] : 0,
                'cpu' => isset($_POST['cpu']) ? trim($_POST['cpu']) : '',
                'screen' => isset($_POST['screen']) ? trim($_POST['screen']) : '',
                'battery' => isset($_POST['battery']) ? trim($_POST['battery']) : '',
                'charging_power' => isset($_POST['charging_power']) ? trim($_POST['charging_power']) : '',
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'image' => isset($_POST['image']) ? trim($_POST['image']) : $product['image'],
                'condition' => isset($_POST['condition']) ? $_POST['condition'] : 'new',
                'stock' => isset($_POST['stock']) ? (int)$_POST['stock'] : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];
            
            if ($this->productModel->update($productId, $data)) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật sản phẩm']);
                exit;
            }
        }
        
        include 'Views/admin/product_form.php';
    }

    /**
     * Delete product
     */
    public function deleteProduct() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $productId = (int)$_POST['id'];
            
            if ($this->productModel->delete($productId)) {
                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa sản phẩm']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Display user list
     */
    public function users() {
        $this->checkAdmin();
        
        $users = $this->userModel->getAll();
        
        include 'Views/admin/users.php';
    }

    /**
     * Toggle user status (ban/unban)
     */
    public function toggleUserStatus() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $userId = (int)$_POST['id'];
            
            if ($this->userModel->toggleStatus($userId)) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Display orders list
     */
    public function orders() {
        $this->checkAdmin();
        
        // In a real application, you would fetch orders from database
        $orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];
        
        include 'Views/admin/orders.php';
    }

    /**
     * Update order status
     */
    public function updateOrderStatus() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
            $orderId = (int)$_POST['order_id'];
            $status = $_POST['status'];
            
            // In a real application, you would update the order in database
            // For now, we'll just simulate it
            
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công']);
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }
}
?>
