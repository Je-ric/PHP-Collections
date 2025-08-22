<?php
require_once __DIR__ . '/../db/config.php';

class Recommend {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Trending based on average rating (then reviews, then release year)
    public function getTrending(int $limit = 12): array {
        $limit = $this->clampLimit($limit);
        $sql = "
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating,
                COUNT(r.id) AS total_reviews
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
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating,
                COUNT(r.id) AS total_reviews
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
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating,
                COUNT(r.id) AS total_reviews
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

        // get top genres
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
        $types = str_repeat('i', count($genreIds)) . 'i' . 'i'; 

        $sql = "
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating,
                COUNT(DISTINCT mg.genre_id) AS match_genres
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
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating
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
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating
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

    // Movies the user favorited
    public function getFavorites(int $userId, int $limit = 12): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sql = "
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(rr.rating),0),2) AS avg_rating,
                COUNT(rr.id) AS total_reviews
            FROM user_favorites f
            JOIN movies m ON m.id = f.movie_id
            LEFT JOIN ratings_reviews rr ON rr.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE f.user_id = ?
            GROUP BY m.id
            ORDER BY m.title ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Movies the user rated/reviewed
    public function getRated(int $userId, int $limit = 12): array {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sql = "
            SELECT 
                m.id, m.title, m.release_year, m.poster_url,
                c.name AS country_name, l.name AS language_name,
                ROUND(COALESCE(AVG(rr.rating),0),2) AS avg_rating,
                COUNT(rr.id) AS total_reviews
            FROM ratings_reviews ur
            JOIN movies m ON m.id = ur.movie_id
            LEFT JOIN ratings_reviews rr ON rr.movie_id = m.id
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE ur.user_id = ?
            GROUP BY m.id
            ORDER BY MAX(ur.id) DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get user's top genres (with counts)
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
        ORDER BY cnt DESC
        LIMIT ?
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('ii', $userId, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get overall top genres across all users
public function getTopGenresOverall(int $limit = 5): array {
    $limit = $this->clampLimit($limit, 10);

    $sql = "
        SELECT g.id, g.name, COUNT(*) AS cnt
        FROM user_favorites uf
        JOIN movie_genres mg ON mg.movie_id = uf.movie_id
        JOIN genres g ON g.id = mg.genre_id
        GROUP BY g.id, g.name
        ORDER BY cnt DESC
        LIMIT ?
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get movies by genre (optionally excluding user's favorites)
public function getByGenre(int $genreId, int $userId = 0, int $limit = 12): array {
    $limit = $this->clampLimit($limit);

    $sql = "
        SELECT 
            m.id, m.title, m.release_year, m.poster_url,
            c.name AS country_name, l.name AS language_name,
            ROUND(COALESCE(AVG(r.rating),0),2) AS avg_rating,
            COUNT(r.id) AS total_reviews
        FROM movies m
        JOIN movie_genres mg ON mg.movie_id = m.id
        LEFT JOIN ratings_reviews r ON r.movie_id = m.id
        LEFT JOIN countries c ON m.country_id = c.id
        LEFT JOIN languages l ON m.language_id = l.id
        WHERE mg.genre_id = ?
    ";

    if ($userId > 0) {
        $sql .= " AND m.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)";
    }

    $sql .= "
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


    // Aggregate: favorites by genre
    public function getFavCountsByGenre(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT g.id, g.name, COUNT(*) AS cnt
            FROM user_favorites f
            JOIN movie_genres mg ON mg.movie_id = f.movie_id
            JOIN genres g ON g.id = mg.genre_id
            WHERE f.user_id = ?
            GROUP BY g.id, g.name
            HAVING cnt > 0
            ORDER BY cnt DESC, g.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregate: favorites by country
    public function getFavCountsByCountry(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT c.id, c.name, COUNT(*) AS cnt
            FROM user_favorites f
            JOIN movies m ON m.id = f.movie_id
            JOIN countries c ON c.id = m.country_id
            WHERE f.user_id = ? AND m.country_id IS NOT NULL
            GROUP BY c.id, c.name
            HAVING cnt > 0
            ORDER BY cnt DESC, c.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregate: favorites by language
    public function getFavCountsByLanguage(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT l.id, l.name, COUNT(*) AS cnt
            FROM user_favorites f
            JOIN movies m ON m.id = f.movie_id
            JOIN languages l ON l.id = m.language_id
            WHERE f.user_id = ? AND m.language_id IS NOT NULL
            GROUP BY l.id, l.name
            HAVING cnt > 0
            ORDER BY cnt DESC, l.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregate: rated by genre (dedupe rated movies)
    public function getRatedCountsByGenre(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT g.id, g.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT movie_id FROM ratings_reviews WHERE user_id = ?
            ) um
            JOIN movie_genres mg ON mg.movie_id = um.movie_id
            JOIN genres g ON g.id = mg.genre_id
            GROUP BY g.id, g.name
            HAVING cnt > 0
            ORDER BY cnt DESC, g.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregate: rated by country
    public function getRatedCountsByCountry(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT c.id, c.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT movie_id FROM ratings_reviews WHERE user_id = ?
            ) um
            JOIN movies m ON m.id = um.movie_id
            JOIN countries c ON c.id = m.country_id
            WHERE m.country_id IS NOT NULL
            GROUP BY c.id, c.name
            HAVING cnt > 0
            ORDER BY cnt DESC, c.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregate: rated by language
    public function getRatedCountsByLanguage(int $userId): array {
        if ($userId <= 0) return [];
        $sql = "
            SELECT l.id, l.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT movie_id FROM ratings_reviews WHERE user_id = ?
            ) um
            JOIN movies m ON m.id = um.movie_id
            JOIN languages l ON l.id = m.language_id
            WHERE m.language_id IS NOT NULL
            GROUP BY l.id, l.name
            HAVING cnt > 0
            ORDER BY cnt DESC, l.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function clampLimit(int $limit, int $max = 48): int {
        if ($limit <= 0) $limit = 12;
        if ($limit > $max) $limit = $max;
        return $limit;
    }

    private function bindParams(mysqli_stmt $stmt, string $types, array $params): void {
        $refs = [];
        $refs[] = &$types;
        foreach ($params as $k => $v) {
            $refs[] = &$params[$k];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }
}
