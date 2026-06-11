<?php
// =========================================================
// MODEL DATABASE (PDO)
// =========================================================
// Mục tiêu file này:
// - Chỉ làm 1 nhiệm vụ: kết nối CSDL bằng PDO.
// - Không trộn HTML vào đây.
// - Controller/Model khác sẽ gọi Database::connect() để lấy PDO và query.
//
// Gợi ý khi học MVC:
// - Model: xử lý dữ liệu (query, insert, update...)
// - Controller: nhận request, gọi model, rồi trả dữ liệu cho view
// - View: chỉ hiển thị (HTML/CSS/JS), hạn chế viết query trong view

class Database
{
    private ?PDO $pdo = null;

    public function __construct(
        private string $host = 'localhost',
        private string $dbName = 'gaming_phone_db',
        private string $username = 'root',
        private string $password = ''
    ) {
    }

    public function connect(): PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            die(
                'Kết nối cơ sở dữ liệu thất bại. '
                . 'Hãy kiểm tra lại tên database, tài khoản MySQL hoặc XAMPP/MySQL đã chạy chưa. '
                . 'Chi tiết lỗi: ' . $exception->getMessage()
            );
        }

        return $this->pdo;
    }
}
