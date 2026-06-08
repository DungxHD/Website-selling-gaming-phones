<?php
/**
 * User Model
 * Handles all user-related database operations
 */
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get user by email
     */
    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Create new user (register)
     */
    public function create($data) {
        // Check if email already exists
        if ($this->getByEmail($data['email'])) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " 
                  (full_name, email, password, phone, role, is_active) 
                  VALUES (:full_name, :email, :password, :phone, :role, :is_active)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':full_name', $data['full_name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $stmt->bindValue(':phone', isset($data['phone']) ? $data['phone'] : '');
        $stmt->bindValue(':role', isset($data['role']) ? $data['role'] : 'customer');
        $stmt->bindValue(':is_active', isset($data['is_active']) ? $data['is_active'] : 1, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET full_name = :full_name, email = :email, phone = :phone 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':full_name', $data['full_name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':phone', isset($data['phone']) ? $data['phone'] : '');

        return $stmt->execute();
    }

    /**
     * Update password
     */
    public function updatePassword($id, $password) {
        $query = "UPDATE " . $this->table . " 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_BCRYPT));

        return $stmt->execute();
    }

    /**
     * Toggle user active status (ban/unban)
     */
    public function toggleStatus($id) {
        $query = "UPDATE " . $this->table . " 
                  SET is_active = NOT is_active 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Get all users
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Verify user login
     */
    public function verifyLogin($email, $password) {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active'] == 1) {
                return $user;
            }
            return 'inactive';
        }
        
        return false;
    }
}
?>
