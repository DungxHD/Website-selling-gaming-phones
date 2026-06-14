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
        // =========================================================
    // ĐĂNG NHẬP USER (FRONTEND)
    // =========================================================
    /**
     * Xử lý đăng nhập cho người dùng thường (role = 'user')
     *
     * NHẬN XÉT:
     * - Luồng này tách biệt hoàn toàn với adminLogin() ở trên để tránh nhầm lẫn quyền.
     * - Khi đã đăng nhập rồi thì tự động đẩy về trang chủ, không cho vào lại form login.
     *
     * CẢI THIỆN:
     * - Có thể thêm tính năng "Remember me" bằng cookie trong tương lai.
     */
    public function login(): array
    {
        // Nếu đã login rồi thì không cho vào form login nữa
        if (!empty($_SESSION['user'])) {
            redirect_to('index.php?page=home');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string)($_POST['username'] ?? ''));
            $password = trim((string)($_POST['password'] ?? ''));

            if ($username === '' || $password === '') {
                flash('error', 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!');
                redirect_to('index.php?page=login');
            }

            // Gọi verifyLogin với role = 'user' để chỉ cho phép user thường đăng nhập
            $user = $this->userModel->verifyLogin($username, $password, 'user');

            if ($user) {
                $_SESSION['user'] = [
                    'id'       => (int)$user['id'],
                    'username' => $user['username'],
                    'name'     => $user['name'],
                    'contact'  => $user['contact'],
                    'role'     => $user['role'],
                ];
                flash('success', 'Đăng nhập thành công! Chào mừng ' . $user['name']);

                // Nếu có URL cần quay lại (ví dụ từ trang checkout) thì quay lại đó
                $redirect = $_SESSION['redirect_after_login'] ?? 'index.php?page=home';
                unset($_SESSION['redirect_after_login']);
                redirect_to($redirect);
            }

            flash('error', 'Tài khoản hoặc mật khẩu không đúng, hoặc tài khoản đang bị khóa!');
            redirect_to('index.php?page=login');
        }

        return ['view' => 'frontend/login.php', 'data' => []];
    }

    // =========================================================
    // ĐĂNG XUẤT USER
    // =========================================================
    public function logout(): void
    {
        unset($_SESSION['user']);
        flash('success', 'Bạn đã đăng xuất thành công. Hẹn gặp lại!');
        redirect_to('index.php?page=home');
    }

    // =========================================================
    // ĐĂNG KÝ USER
    // =========================================================
    /**
     * Xử lý đăng ký tài khoản mới cho người dùng
     *
     * NHẬN XÉT:
     * - Mặc định vai trò là 'user', is_active = 1 (hoạt động ngay).
     * - Kiểm tra trùng username trước khi tạo để tránh lỗi UNIQUE KEY của database.
     *
     * CẢI THIỆN:
     * - Thêm validate định dạng email/SĐT bằng regex.
     * - Thêm kiểm tra độ mạnh mật khẩu (ít nhất 8 ký tự, có chữ hoa, số, ký tự đặc biệt).
     */
    public function register(): array
    {
        // Đã login rồi thì không cần đăng ký nữa
        if (!empty($_SESSION['user'])) {
            redirect_to('index.php?page=home');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username'  => trim((string)($_POST['username'] ?? '')),
                'password'  => trim((string)($_POST['password'] ?? '')),
                'name'      => trim((string)($_POST['name'] ?? '')),
                'contact'   => trim((string)($_POST['contact'] ?? '')),
                'role'      => 'user',
                'is_active' => 1,
            ];

            // Validate các trường bắt buộc
            if ($data['username'] === '' || $data['password'] === ''
                || $data['name'] === '' || $data['contact'] === '') {
                flash('error', 'Vui lòng điền đầy đủ tất cả các trường!');
                redirect_to('index.php?page=register');
            }

            // Kiểm tra độ dài tối thiểu
            if (mb_strlen($data['username']) < 3) {
                flash('error', 'Tên đăng nhập phải có ít nhất 3 ký tự!');
                redirect_to('index.php?page=register');
            }
            if (mb_strlen($data['password']) < 6) {
                flash('error', 'Mật khẩu phải có ít nhất 6 ký tự!');
                redirect_to('index.php?page=register');
            }

            // Kiểm tra username đã tồn tại chưa
            if ($this->userModel->findByUsername($data['username'])) {
                flash('error', 'Tên đăng nhập "' . $data['username'] . '" đã tồn tại. Vui lòng chọn tên khác!');
                redirect_to('index.php?page=register');
            }

            // Tạo user mới
            $this->userModel->create($data);
            flash('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.');
            redirect_to('index.php?page=login');
        }

        return ['view' => 'frontend/register.php', 'data' => []];
    }

    // =========================================================
    // QUÊN MẬT KHẨU (RESET PASSWORD)
    // =========================================================
    /**
     * Xử lý đổi mật khẩu khi người dùng quên
     *
     * NHẬN XÉT:
     * - Vì đây là project học tập nên cơ chế "quên mật khẩu" được làm đơn giản:
     *   xác minh bằng (username + contact) rồi cho đổi mật khẩu mới ngay.
     * - Không gửi email thật, tránh phức tạp cho người mới học.
     *
     * CẢI THIỆN:
     * - Khi làm production thật, nên tạo token ngẫu nhiên, lưu vào DB, gửi qua email
     *   và bắt người dùng click link xác nhận trước khi đổi mật khẩu.
     */
    public function forgotPassword(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username    = trim((string)($_POST['username'] ?? ''));
            $contact     = trim((string)($_POST['contact'] ?? ''));
            $newPassword = trim((string)($_POST['new_password'] ?? ''));

            // Validate đầu vào
            if ($username === '' || $contact === '' || $newPassword === '') {
                flash('error', 'Vui lòng điền đầy đủ tất cả các trường!');
                redirect_to('index.php?page=forgot');
            }
            if (mb_strlen($newPassword) < 6) {
                flash('error', 'Mật khẩu mới phải có ít nhất 6 ký tự!');
                redirect_to('index.php?page=forgot');
            }

            // Tìm user khớp cả username và contact
            $user = $this->userModel->findByUsernameAndContact($username, $contact);

            if ($user) {
                $this->userModel->updatePassword((int)$user['id'], $newPassword);
                flash('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
                redirect_to('index.php?page=login');
            }

            flash('error', 'Thông tin không khớp! Vui lòng kiểm tra lại tên đăng nhập và SĐT/Email.');
            redirect_to('index.php?page=forgot');
        }

        return ['view' => 'frontend/forgot.php', 'data' => []];
    }

    // =========================================================
    // ĐỔI MẬT KHẨU (KHI ĐÃ ĐĂNG NHẬP)
    // =========================================================
    /**
     * Xử lý đổi mật khẩu cho user đã đăng nhập
     *
     * NHẬN XÉT:
     * - Bắt buộc phải đăng nhập mới được vào trang này (có require_user_login()).
     * - Phải nhập đúng mật khẩu cũ mới cho đổi sang mật khẩu mới.
     *
     * CẢI THIỆN:
     * - Sau khi đổi mật khẩu thành công, có thể hủy tất cả session khác
     *   để đảm bảo an toàn (logout all devices).
     */
    public function changePassword(): array
    {
        // Gate: Bắt buộc phải login
        require_user_login();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldPassword = trim((string)($_POST['old_password'] ?? ''));
            $newPassword = trim((string)($_POST['new_password'] ?? ''));

            if ($oldPassword === '' || $newPassword === '') {
                flash('error', 'Vui lòng điền đầy đủ mật khẩu cũ và mật khẩu mới!');
                redirect_to('index.php?page=change_password');
            }
            if (mb_strlen($newPassword) < 6) {
                flash('error', 'Mật khẩu mới phải có ít nhất 6 ký tự!');
                redirect_to('index.php?page=change_password');
            }
            if ($oldPassword === $newPassword) {
                flash('error', 'Mật khẩu mới không được trùng với mật khẩu cũ!');
                redirect_to('index.php?page=change_password');
            }

            // Lấy thông tin user hiện tại từ session
            $userId = (int)($_SESSION['user']['id'] ?? 0);
            $user = $this->userModel->getById($userId);

            if (!$user) {
                flash('error', 'Không tìm thấy thông tin tài khoản!');
                redirect_to('index.php?page=login');
            }

            // Verify mật khẩu cũ (đồng bộ với cơ chế plain text hiện tại)
            if ($user['password'] !== $oldPassword) {
                flash('error', 'Mật khẩu cũ không đúng!');
                redirect_to('index.php?page=change_password');
            }

            // Cập nhật mật khẩu mới
            $this->userModel->updatePassword($userId, $newPassword);
            flash('success', 'Đổi mật khẩu thành công!');
            redirect_to('index.php?page=home');
        }

        return ['view' => 'frontend/change_password.php', 'data' => []];
    }
}