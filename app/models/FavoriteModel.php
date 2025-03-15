<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

class Favorite {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Add a favorite movie
    public function addFavorite($userId, $title, $year, $type, $poster) {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, title, year, type, poster) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $title, $year, $type, $poster);
        return $stmt->execute();
    }

    // Remove a favorite movie by title and year
    public function removeFavorite($userId, $title, $year) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND title = ? AND year = ?");
        $stmt->bind_param("iss", $userId, $title, $year);
        return $stmt->execute();
    }

    // Get all favorite movies for a user
    public function getFavorites($userId) {
        $stmt = $this->db->prepare("SELECT title, year, type, poster FROM favorites WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $favorites = [];
        while ($row = $result->fetch_assoc()) {
            $favorites[] = $row;
        }
        return $favorites;
    }
}
?>
