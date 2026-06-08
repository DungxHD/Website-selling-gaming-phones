<?php
/**
 * User Controller
 * Handles user authentication (login, register, logout, forgot password)
 */
class UserController {
    private $userModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    /**
     * Display login/register page
     */
    public function auth() {
        $error = '';
        $success = '';
        
        if (isset($_SESSION['user'])) {
            header('Location: index.php?controller=home&action=index');
            exit;
        }
        
        include 'Views/user/login.php';
    }

    /**
     * Process login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
                exit;
            }
            
            $result = $this->userModel->verifyLogin($email, $password);
            
            if ($result === 'inactive') {
                echo json_encode(['success' => false, 'message' => 'Tài khoản của bạn đã bị khóa']);
                exit;
            } elseif ($result) {
                // Set session
                $_SESSION['user'] = [
                    'id' => $result['id'],
                    'full_name' => $result['full_name'],
                    'email' => $result['email'],
                    'role' => $result['role']
                ];
                
                // Redirect admin users to admin dashboard
                if ($result['role'] === 'admin') {
                    echo json_encode(['success' => true, 'redirect' => 'index.php?controller=admin&action=dashboard']);
                    exit;
                }
                
                echo json_encode(['success' => true, 'redirect' => 'index.php?controller=home&action=index']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Process registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            
            // Validation
            if (empty($full_name) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc']);
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
                exit;
            }
            
            if (strlen($password) < 6) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
                exit;
            }
            
            if ($password !== $confirm_password) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
                exit;
            }
            
            $data = [
                'full_name' => $full_name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'role' => 'customer',
                'is_active' => 1
            ];
            
            if ($this->userModel->create($data)) {
                echo json_encode(['success' => true, 'message' => 'Đăng ký thành công. Vui lòng đăng nhập.']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Email này đã được sử dụng']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Process logout
     */
    public function logout() {
        session_destroy();
        header('Location: index.php?controller=home&action=index');
        exit;
    }

    /**
     * Display profile page
     */
    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=user&action=auth');
            exit;
        }
        
        $user = $this->userModel->getById($_SESSION['user']['id']);
        
        include 'Views/user/profile.php';
    }

    /**
     * Update profile
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            
            if (empty($full_name) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
                exit;
            }
            
            $data = [
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone
            ];
            
            if ($this->userModel->update($_SESSION['user']['id'], $data)) {
                $_SESSION['user']['full_name'] = $full_name;
                $_SESSION['user']['email'] = $email;
                echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Change password
     */
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
            $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            if (empty($old_password) || empty($new_password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
                exit;
            }
            
            if (strlen($new_password) < 6) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
                exit;
            }
            
            if ($new_password !== $confirm_password) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
                exit;
            }
            
            $user = $this->userModel->getById($_SESSION['user']['id']);
            
            if (!password_verify($old_password, $user['password'])) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu cũ không đúng']);
                exit;
            }
            
            if ($this->userModel->updatePassword($_SESSION['user']['id'], $new_password)) {
                echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }

    /**
     * Forgot password request
     */
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            
            if (empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email']);
                exit;
            }
            
            $user = $this->userModel->getByEmail($email);
            
            if ($user) {
                // In a real application, you would send a reset link via email
                // For now, we'll just show success
                echo json_encode(['success' => true, 'message' => 'Vui lòng kiểm tra email để đặt lại mật khẩu']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Email không tồn tại trong hệ thống']);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    }
}
?>
