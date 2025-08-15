<?php
// File: Movie.php
// Description: A class to handle movie-related operations.

require_once 'Database.php';

class Movie {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all movies
    public function getAllMovies() {
        $this->db->query('SELECT * FROM movies ORDER BY release_year DESC');
        return $this->db->resultset();
    }

    // Search for movies by title or genre
    public function searchMovies($query) {
        $query = "%" . $query . "%";
        $this->db->query('SELECT * FROM movies WHERE title LIKE :query OR genre LIKE :query');
        $this->db->bind(':query', $query);
        return $this->db->resultset();
    }

    // Add a rating or review
    public function addRatingReview($userId, $movieId, $rating, $review) {
        // Check if user has already rated this movie
        $this->db->query('SELECT * FROM ratings_reviews WHERE user_id = :user_id AND movie_id = :movie_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':movie_id', $movieId);
        $existingReview = $this->db->single();

        if ($existingReview) {
            // Update existing rating/review
            $this->db->query('UPDATE ratings_reviews SET rating = :rating, review = :review WHERE id = :id');
            $this->db->bind(':id', $existingReview['id']);
        } else {
            // Insert new rating/review
            $this->db->query('INSERT INTO ratings_reviews (user_id, movie_id, rating, review) VALUES (:user_id, :movie_id, :rating, :review)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':movie_id', $movieId);
        }

        $this->db->bind(':rating', $rating);
        $this->db->bind(':review', $review);
        return $this->db->execute();
    }

    // Add a movie to a user's favorites
    public function addFavorite($userId, $movieId) {
        // Check if it's already a favorite
        $this->db->query('SELECT * FROM user_favorites WHERE user_id = :user_id AND movie_id = :movie_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':movie_id', $movieId);
        if ($this->db->single()) {
            return false; // Already a favorite
        }
        $this->db->query('INSERT INTO user_favorites (user_id, movie_id) VALUES (:user_id, :movie_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':movie_id', $movieId);
        return $this->db->execute();
    }

    // Get a user's favorite movies
    public function getUserFavorites($userId) {
        $this->db->query('SELECT m.* FROM movies m JOIN user_favorites uf ON m.id = uf.movie_id WHERE uf.user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultset();
    }

    // Get recommended movies (simple logic)
    public function getRecommendations() {
        // Find the top 3 rated movies
        $this->db->query('
            SELECT m.*, AVG(rr.rating) as avg_rating
            FROM movies m
            JOIN ratings_reviews rr ON m.id = rr.movie_id
            GROUP BY m.id
            ORDER BY avg_rating DESC
            LIMIT 3
        ');
        return $this->db->resultset();
    }
}
?>