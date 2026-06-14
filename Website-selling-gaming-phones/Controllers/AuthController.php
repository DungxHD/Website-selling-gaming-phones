<?php
declare(strict_types=1);

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // =========================================================
    // ĐĂNG NHẬP ADMIN
    // =========================================================
    public function adminLogin(): array
    {
        if (!empty($_SESSION['admin'])) {
            header("Location: index.php?page=admin_dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string)($_POST['username'] ?? ''));
            $password = trim((string)($_POST['password'] ?? ''));

            if ($username === '' || $password === '') {
                $_SESSION['flash']['error'] = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!';
                header("Location: index.php?page=admin_login");
                exit();
            }

            $admin = $this->userModel->verifyLogin($username, $password, 'admin');
            if ($admin) {
                $_SESSION['admin'] = [
                    'id'       => (int)$admin['id'],
                    'username' => $admin['username'],
                    'name'     => $admin['name'],
                    'role'     => $admin['role'],
                ];
                $_SESSION['flash']['success'] = 'Đăng nhập thành công! Chào mừng ' . $admin['name'];
                header("Location: index.php?page=admin_dashboard");
                exit();
            }

            $_SESSION['flash']['error'] = 'Tài khoản hoặc mật khẩu không đúng, hoặc không có quyền admin!';
            header("Location: index.php?page=admin_login");
            exit();
        }

        return ['view' => 'backend/login.php', 'data' => []];
    }

    // =========================================================
    // ĐĂNG XUẤT ADMIN
    // =========================================================
    public function adminLogout(): void
    {
        unset($_SESSION['admin']);
        $_SESSION['flash']['success'] = 'Bạn đã đăng xuất khỏi trang quản trị.';
        header("Location: index.php?page=admin_login");
        exit();
    }

    // =========================================================
    // DANH SÁCH USER + XỬ LÝ TOGGLE/DELETE
    // =========================================================
    public function adminUsers(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $userId = (int)($_POST['user_id'] ?? 0);

            if ($userId > 0) {
                if ($action === 'toggle') {
                    $this->userModel->toggleStatus($userId);
                    $_SESSION['flash']['success'] = 'Cập nhật trạng thái thành công!';
                } elseif ($action === 'delete') {
                    if ($this->userModel->delete($userId)) {
                        $_SESSION['flash']['success'] = 'Xóa tài khoản thành công!';
                    } else {
                        $_SESSION['flash']['error'] = 'Không thể xóa tài khoản này!';
                    }
                }
            }
            header("Location: index.php?page=admin_users");
            exit();
        }

        return [
            'view' => 'backend/users.php',
            'data' => [
                'users'    => $this->userModel->getAll(),
                'editUser' => null,
                'mode'     => 'list'
            ]
        ];
    }

    // =========================================================
    // FORM THÊM USER (hiển thị trên trang users.php)
    // =========================================================
    public function adminUserAdd(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username'  => trim($_POST['username'] ?? ''),
                'password'  => trim($_POST['password'] ?? ''),
                'name'      => trim($_POST['name'] ?? ''),
                'contact'   => trim($_POST['contact'] ?? ''),
                'role'      => $_POST['role'] ?? 'user',
                'is_active' => $_POST['is_active'] ?? 1
            ];

            if (empty($data['username']) || empty($data['password']) || empty($data['name'])) {
                $_SESSION['flash']['error'] = 'Vui lòng điền đầy đủ Tên đăng nhập, Mật khẩu và Tên hiển thị!';
            } elseif ($this->userModel->findByUsername($data['username'])) {
                $_SESSION['flash']['error'] = 'Tên đăng nhập đã tồn tại!';
            } else {
                $this->userModel->create($data);
                $_SESSION['flash']['success'] = 'Thêm tài khoản thành công!';
                header("Location: index.php?page=admin_users");
                exit();
            }
        }

        return [
            'view' => 'backend/users.php',
            'data' => [
                'users'    => $this->userModel->getAll(),
                'editUser' => [],
                'mode'     => 'add'
            ]
        ];
    }

    // =========================================================
    // FORM SỬA USER (hiển thị trên trang users.php)
    // =========================================================
    public function adminUserUpdate(): array
    {
        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->getById($id);

        if (!$user) {
            $_SESSION['flash']['error'] = 'Không tìm thấy tài khoản!';
            header("Location: index.php?page=admin_users");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username'  => trim($_POST['username'] ?? ''),
                'password'  => trim($_POST['password'] ?? ''),
                'name'      => trim($_POST['name'] ?? ''),
                'contact'   => trim($_POST['contact'] ?? ''),
                'role'      => $_POST['role'] ?? 'user',
                'is_active' => $_POST['is_active'] ?? 1
            ];

            if (empty($data['username']) || empty($data['name'])) {
                $_SESSION['flash']['error'] = 'Vui lòng điền đầy đủ thông tin!';
            } else {
                $checkUser = $this->userModel->findByUsername($data['username']);
                if ($checkUser && (int)$checkUser['id'] !== $id) {
                    $_SESSION['flash']['error'] = 'Tên đăng nhập đã tồn tại!';
                } else {
                    $this->userModel->update($id, $data);
                    $_SESSION['flash']['success'] = 'Cập nhật tài khoản thành công!';
                    header("Location: index.php?page=admin_users");
                    exit();
                }
            }
        }

        return [
            'view' => 'backend/users.php',
            'data' => [
                'users'    => $this->userModel->getAll(),
                'editUser' => $user,
                'mode'     => 'edit'
            ]
        ];
    }
}