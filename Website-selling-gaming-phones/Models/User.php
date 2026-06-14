<?php
declare(strict_types=1);

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->connect();
    }

    /**
     * Tìm user theo username
     */
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy user theo ID
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Xác thực đăng nhập (kiểm tra username + password + is_active + role)
     */
    public function verifyLogin(string $username, string $password, string $role = 'admin'): array|false
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return false;
        }
        if ((int)($user['is_active'] ?? 1) === 0) {
            return false;
        }
        if ($user['password'] !== $password) {
            return false;
        }
        if (($user['role'] ?? '') !== $role) {
            return false;
        }
        return $user;
    }

    /**
     * Lấy tất cả users
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm user mới
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, name, contact, role, is_active) 
             VALUES (:username, :password, :name, :contact, :role, :is_active)"
        );
        return $stmt->execute([
            ':username'  => $data['username'],
            ':password'  => $data['password'],
            ':name'      => $data['name'],
            ':contact'   => $data['contact'],
            ':role'      => $data['role'] ?? 'user',
            ':is_active' => (int)($data['is_active'] ?? 1)
        ]);
    }

    /**
     * Cập nhật user
     */
    public function update(int $id, array $data): bool
    {
        $setPassword = !empty($data['password']) ? ", password = :password" : "";
        
        $sql = "UPDATE users SET 
                username = :username, 
                name = :name, 
                contact = :contact, 
                role = :role, 
                is_active = :is_active
                {$setPassword}
                WHERE id = :id";

        $params = [
            ':id'        => $id,
            ':username'  => $data['username'],
            ':name'      => $data['name'],
            ':contact'   => $data['contact'],
            ':role'      => $data['role'],
            ':is_active' => (int)$data['is_active']
        ];

        if (!empty($data['password'])) {
            $params[':password'] = $data['password'];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Xóa user
     */
    public function delete(int $id): bool
    {
        if (isset($_SESSION['admin']) && (int)$_SESSION['admin']['id'] === $id) {
            return false;
        }
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Khóa / Mở khóa
     */
    public function toggleStatus(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT is_active FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;
        if (isset($_SESSION['admin']) && (int)$_SESSION['admin']['id'] === $id) {
            return false;
        }

        $newStatus = (int)$user['is_active'] === 1 ? 0 : 1;
        $stmt = $this->pdo->prepare("UPDATE users SET is_active = :status WHERE id = :id");
        return $stmt->execute([':status' => $newStatus, ':id' => $id]);
    }
}