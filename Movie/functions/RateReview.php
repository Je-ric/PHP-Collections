<?php 
require_once __DIR__ . '/../db/config.php';

class RateReview {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function getReviewsByMovie($movieId) {
        $sql = "
            SELECT r.rating, r.review, r.created_at, u.username 
            FROM ratings_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.movie_id = ?
            ORDER BY r.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addReview($userId, $movieId, $rating, $review) {
        $sql = "
            INSERT INTO ratings_reviews (user_id, movie_id, rating, review)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiis", $userId, $movieId, $rating, $review);
        return $stmt->execute();
    }

    public function hasReviewed($userId, $movieId) {
        $sql = "
            SELECT id 
            FROM ratings_reviews
            WHERE user_id = ? AND movie_id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $movieId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getUserReview($userId, $movieId) {
        $sql = "
            SELECT rating, review 
            FROM ratings_reviews 
            WHERE user_id = ? AND movie_id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $movieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
public function getAverageRating($movieId) {
    $sql = "
        SELECT AVG(rating) AS avg_rating, COUNT(*) AS total
        FROM ratings_reviews
        WHERE movie_id = ?
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    // Format average rating as float or null if no ratings
    $avg = $result['avg_rating'] ? floatval($result['avg_rating']) : null;
    $total = intval($result['total']);
    
    return ['avg' => $avg, 'total' => $total];
}


}
