<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db.php";

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Register a new user
    public function register($username, $email, $password,$timestamp) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, timestamp) VALUES (?, ?, ?,?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword,$timestamp);
        return $stmt->execute();
    }

    // Login and generate a session token
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Generate a simple random token
            $token = bin2hex(random_bytes(32));

            // Store the token in the database
            $updateStmt = $this->db->prepare("UPDATE users SET token = ? WHERE id = ?");
            $updateStmt->bind_param("si", $token, $user['id']);
            $updateStmt->execute();

            return ["token" => $token, "username" => $user['username']];
        }
        return false;
    }

    // Verify token for protected routes
    public function verifyToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
