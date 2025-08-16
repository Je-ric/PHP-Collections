<?php 
require_once __DIR__ . '/../db/config.php';

class RateReview {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function addReview($userId, $movieId, $rating, $review) {
        $stmt = $this->db->prepare("
            INSERT INTO ratings_reviews (user_id, movie_id, rating, review)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiis", $userId, $movieId, $rating, $review);
        return $stmt->execute();
    }

    public function hasReviewed($userId, $movieId) {
        $stmt = $this->db->prepare("
            SELECT id FROM ratings_reviews
            WHERE user_id = ? AND movie_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $userId, $movieId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getUserReview($userId, $movieId) {
        $stmt = $this->db->prepare("
            SELECT rating, review 
            FROM ratings_reviews 
            WHERE user_id = ? AND movie_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $userId, $movieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getReviews($movieId) {
        $stmt = $this->db->prepare("
            SELECT r.rating, r.review, r.created_at, u.username
            FROM ratings_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.movie_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAverageRating($movieId) {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) AS avg_rating, COUNT(*) AS total 
            FROM ratings_reviews 
            WHERE movie_id = ?
        ");
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
