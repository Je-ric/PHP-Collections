<?php
require_once __DIR__ . '/../db/config.php';

class Recommend {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Trending based on average rating (then by number of reviews)
    public function getTrending(int $limit = 12): array {
        $limit = $this->clampLimit($limit);
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating, COUNT(r.id) AS total_reviews
            FROM movies m
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            GROUP BY m.id
            ORDER BY (AVG(r.rating) IS NULL), AVG(r.rating) DESC, COUNT(r.id) DESC, m.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Latest by release year
    public function getLatest(int $limit = 12): array {
        $limit = $this->clampLimit($limit);
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating, COUNT(r.id) AS total_reviews
            FROM movies m
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            GROUP BY m.id
            ORDER BY m.release_year DESC, m.id DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Search by movie title
    public function searchByTitle(string $term, int $limit = 24): array {
        $limit = $this->clampLimit($limit, 60);
        $like = '%' . $term . '%';
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating, COUNT(r.id) AS total_reviews
            FROM movies m
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE m.title LIKE ?
            GROUP BY m.id
            ORDER BY m.release_year DESC, m.title ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $like, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Based on user's favorite genres
    public function basedOnFavoriteGenres(int $userId, int $limit = 12): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        // Get top genres from user's favorites
        $sqlTop = "
            SELECT mg.genre_id, COUNT(*) AS cnt
            FROM user_favorites uf
            JOIN movie_genres mg ON mg.movie_id = uf.movie_id
            WHERE uf.user_id = ?
            GROUP BY mg.genre_id
            ORDER BY cnt DESC
            LIMIT 5
        ";
        $stmtTop = $this->db->prepare($sqlTop);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $genres = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($genres)) return [];
        $genreIds = array_column($genres, 'genre_id');

        $in = implode(',', array_fill(0, count($genreIds), '?'));
        $types = str_repeat('i', count($genreIds)) . 'i' . 'i'; // genre ids + userId(exclude) + limit

        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating, COUNT(DISTINCT mg.genre_id) AS match_genres
            FROM movies m
            JOIN movie_genres mg ON mg.movie_id = m.id
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE mg.genre_id IN ($in)
              AND m.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY m.id
            ORDER BY match_genres DESC, (AVG(r.rating) IS NULL), AVG(r.rating) DESC, m.release_year DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        // bind dynamic params
        $params = array_merge($genreIds, [$userId, $limit]);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Based on user's favorite countries
    public function basedOnFavoriteCountries(int $userId, int $limit = 12): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sqlTop = "
            SELECT m.country_id, COUNT(*) AS cnt
            FROM user_favorites uf
            JOIN movies m ON m.id = uf.movie_id
            WHERE uf.user_id = ? AND m.country_id IS NOT NULL
            GROUP BY m.country_id
            ORDER BY cnt DESC
            LIMIT 3
        ";
        $stmtTop = $this->db->prepare($sqlTop);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $rows = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($rows)) return [];
        $ids = array_column($rows, 'country_id');

        $in = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids)) . 'i' . 'i';

        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating
            FROM movies m
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE m.country_id IN ($in)
              AND m.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY m.id
            ORDER BY (AVG(r.rating) IS NULL), AVG(r.rating) DESC, m.release_year DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $params = array_merge($ids, [$userId, $limit]);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Based on user's favorite languages
    public function basedOnFavoriteLanguages(int $userId, int $limit = 12): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sqlTop = "
            SELECT m.language_id, COUNT(*) AS cnt
            FROM user_favorites uf
            JOIN movies m ON m.id = uf.movie_id
            WHERE uf.user_id = ? AND m.language_id IS NOT NULL
            GROUP BY m.language_id
            ORDER BY cnt DESC
            LIMIT 3
        ";
        $stmtTop = $this->db->prepare($sqlTop);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $rows = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($rows)) return [];
        $ids = array_column($rows, 'language_id');

        $in = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids)) . 'i' . 'i';

        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating
            FROM movies m
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE m.language_id IN ($in)
              AND m.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY m.id
            ORDER BY (AVG(r.rating) IS NULL), AVG(r.rating) DESC, m.release_year DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $params = array_merge($ids, [$userId, $limit]);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get user's top favorite genres (id, name, cnt)
    public function getUserTopGenres(int $userId, int $limit = 5): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit, 10);
        $sql = "
            SELECT g.id, g.name, COUNT(*) AS cnt
            FROM user_favorites uf
            JOIN movie_genres mg ON mg.movie_id = uf.movie_id
            JOIN genres g ON g.id = mg.genre_id
            WHERE uf.user_id = ?
            GROUP BY g.id, g.name
            ORDER BY cnt DESC, g.name ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get overall top genres by favorite count; fallback to most common by movie count
    public function getTopGenresOverall(int $limit = 5): array {
        $limit = $this->clampLimit($limit, 10);
        // Try by favorites
        $sqlFav = "
            SELECT g.id, g.name, COUNT(*) AS cnt
            FROM user_favorites uf
            JOIN movie_genres mg ON mg.movie_id = uf.movie_id
            JOIN genres g ON g.id = mg.genre_id
            GROUP BY g.id, g.name
            ORDER BY cnt DESC, g.name ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sqlFav);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (!empty($rows)) return $rows;

        // Fallback by movie frequency
        $sqlMovies = "
            SELECT g.id, g.name, COUNT(*) AS cnt
            FROM movie_genres mg
            JOIN genres g ON g.id = mg.genre_id
            GROUP BY g.id, g.name
            ORDER BY cnt DESC, g.name ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sqlMovies);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get movies by a specific genre; exclude user's favorites when userId>0
    public function getByGenre(int $genreId, int $userId = 0, int $limit = 12): array {
        if ($genreId <= 0) return [];
        $limit = $this->clampLimit($limit);
        $exclude = $userId > 0 ? "AND m.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)" : "";
        $types = $userId > 0 ? 'iii' : 'ii';
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name,
                   AVG(r.rating) AS avg_rating, COUNT(r.id) AS total_reviews
            FROM movies m
            JOIN movie_genres mg ON mg.movie_id = m.id
            LEFT JOIN ratings_reviews r ON r.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE mg.genre_id = ? $exclude
            GROUP BY m.id
            ORDER BY (AVG(r.rating) IS NULL), AVG(r.rating) DESC, m.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        if ($userId > 0) {
            $stmt->bind_param('iii', $genreId, $userId, $limit);
        } else {
            $stmt->bind_param('ii', $genreId, $limit);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function clampLimit(int $limit, int $max = 48): int {
        if ($limit <= 0) $limit = 12;
        if ($limit > $max) $limit = $max;
        return $limit;
    }

    private function bindParams(mysqli_stmt $stmt, string $types, array $params): void {
        // mysqli doesn't accept arrays directly; use references
        $refs = [];
        $refs[] = & $types;
        foreach ($params as $k => $v) {
            $refs[] = & $params[$k];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }
}
