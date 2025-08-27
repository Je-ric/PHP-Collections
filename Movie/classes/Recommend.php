<?php
require_once __DIR__ . '/../db/config.php';

class Recommend
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    /**
     * Trending based on average rating (then release year)
     * Endpoint action: 'trending'
     * Pages: index.php (home)
     */
    public function getTrending(int $limit = 12): array
    {
        $limit = $this->clampLimit($limit);
        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(ratings_reviews.id) AS total_reviews
            FROM movies
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            GROUP BY movies.id
            ORDER BY (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, COUNT(ratings_reviews.id) DESC, movies.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Latest by release year
     * Endpoint action: 'latest'
     * Pages: exposed via API; not directly used in current pages
     */

    public function getLatest(int $limit = 12): array
    {
        $limit = $this->clampLimit($limit);
        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(ratings_reviews.id) AS total_reviews
            FROM movies
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            GROUP BY movies.id
            ORDER BY movies.release_year DESC, movies.id DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Endpoint action: 'search'
     * Pages: exposed via API; not directly used (index uses client-side filter)
     */
    public function searchByTitle(string $term, int $limit = 24): array
    {
        $limit = $this->clampLimit($limit, 60);
        $searchLike = '%' . $term . '%';
        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(ratings_reviews.id) AS total_reviews
            FROM movies
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE movies.title LIKE ?
            GROUP BY movies.id
            ORDER BY movies.release_year DESC, movies.title ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $searchLike, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Endpoint action: 'favGenres'
     * Pages: pages/profile.php (Recommendation tab)
     */
    public function basedOnFavoriteGenres(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        // get top genres
        $sqlTopGenres = "
            SELECT movie_genres.genre_id, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movie_genres ON movie_genres.movie_id = user_favorites.movie_id
            WHERE user_favorites.user_id = ?
            GROUP BY movie_genres.genre_id
            ORDER BY cnt DESC
            LIMIT 5
        ";
        $stmtTop = $this->db->prepare($sqlTopGenres);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $topGenres = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($topGenres)) return [];
        $genreIds = array_column($topGenres, 'genre_id');

        $placeholders = implode(',', array_fill(0, count($genreIds), '?'));
        $bindTypes = str_repeat('i', count($genreIds)) . 'i' . 'i';

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(DISTINCT movie_genres.genre_id) AS match_genres
            FROM movies
            JOIN movie_genres ON movie_genres.movie_id = movies.id
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE movie_genres.genre_id IN ($placeholders)
              AND movies.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY movies.id
            ORDER BY match_genres DESC, (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, movies.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $bindValues = array_merge($genreIds, [$userId, $limit]);
        $this->bindParams($stmt, $bindTypes, $bindValues);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Endpoint action: 'favCountries'
     * Pages: pages/profile.php (Recommendation tab)
     */
    public function basedOnFavoriteCountries(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sqlTopCountries = "
            SELECT movies.country_id, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movies ON movies.id = user_favorites.movie_id
            WHERE user_favorites.user_id = ? AND movies.country_id IS NOT NULL
            GROUP BY movies.country_id
            ORDER BY cnt DESC
            LIMIT 3
        ";
        $stmtTop = $this->db->prepare($sqlTopCountries);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $topCountryRows = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($topCountryRows)) return [];
        $countryIds = array_column($topCountryRows, 'country_id');

        $placeholders = implode(',', array_fill(0, count($countryIds), '?'));
        $bindTypes = str_repeat('i', count($countryIds)) . 'i' . 'i';

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating
            FROM movies
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE movies.country_id IN ($placeholders)
              AND movies.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY movies.id
            ORDER BY (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, movies.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $bindValues = array_merge($countryIds, [$userId, $limit]);
        $this->bindParams($stmt, $bindTypes, $bindValues);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Endpoint action: 'favLanguages'
     * Pages: pages/profile.php (Recommendation tab)
     */
    public function basedOnFavoriteLanguages(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sqlTopLanguages = "
            SELECT movies.language_id, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movies ON movies.id = user_favorites.movie_id
            WHERE user_favorites.user_id = ? AND movies.language_id IS NOT NULL
            GROUP BY movies.language_id
            ORDER BY cnt DESC
            LIMIT 3
        ";
        $stmtTop = $this->db->prepare($sqlTopLanguages);
        $stmtTop->bind_param('i', $userId);
        $stmtTop->execute();
        $topLanguageRows = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($topLanguageRows)) return [];
        $languageIds = array_column($topLanguageRows, 'language_id');

        $placeholders = implode(',', array_fill(0, count($languageIds), '?'));
        $bindTypes = str_repeat('i', count($languageIds)) . 'i' . 'i';

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating
            FROM movies
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE movies.language_id IN ($placeholders)
              AND movies.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)
            GROUP BY movies.id
            ORDER BY (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, movies.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $bindValues = array_merge($languageIds, [$userId, $limit]);
        $this->bindParams($stmt, $bindTypes, $bindValues);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Movies the user favorited
     * Endpoint action: 'favorites'
     * Pages: pages/profile.php (Favorites tab)
     */
    public function getFavorites(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(ratings_reviews.id) AS total_reviews
            FROM user_favorites
            JOIN movies ON movies.id = user_favorites.movie_id
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE user_favorites.user_id = ?
            GROUP BY movies.id
            ORDER BY movies.title ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Movies the user rated/reviewed
     * Endpoint action: 'rated'
     * Pages: pages/profile.php (Rated tab)
     */
    public function getRated(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                ROUND(COALESCE(AVG(all_reviews.rating), 0), 2) AS avg_rating,
                COUNT(all_reviews.id) AS total_reviews
            FROM ratings_reviews AS user_reviews
            JOIN movies ON movies.id = user_reviews.movie_id
            LEFT JOIN ratings_reviews AS all_reviews ON all_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE user_reviews.user_id = ?
            GROUP BY movies.id
            ORDER BY MAX(user_reviews.id) DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get user's top genres (with counts)
     * Endpoint action: 'topGenres' (when logged in)
     * Pages: pages/profile.php (Recommendation tab)
     */
    public function getUserTopGenres(int $userId, int $limit = 5): array
    {
        if ($userId <= 0) return [];
        $limit = $this->clampLimit($limit, 10);

        $sql = "
        SELECT genres.id, genres.name, COUNT(*) AS cnt
        FROM user_favorites
        JOIN movie_genres ON movie_genres.movie_id = user_favorites.movie_id
        JOIN genres ON genres.id = movie_genres.genre_id
        WHERE user_favorites.user_id = ?
        GROUP BY genres.id, genres.name
        ORDER BY cnt DESC
        LIMIT ?
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get overall top genres across all users
     * Endpoint action: 'topGenres' (when not logged in)
     * Pages: exposed via API; fallback variant, not directly used in profile
     */
    public function getTopGenresOverall(int $limit = 5): array
    {
        $limit = $this->clampLimit($limit, 10);

        $sql = "
        SELECT genres.id, genres.name, COUNT(*) AS cnt
        FROM user_favorites
        JOIN movie_genres ON movie_genres.movie_id = user_favorites.movie_id
        JOIN genres ON genres.id = movie_genres.genre_id
        GROUP BY genres.id, genres.name
        ORDER BY cnt DESC
        LIMIT ?
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get movies by genre (optionally excluding user's favorites)
     * Endpoint action: 'byGenre'
     * Pages: pages/profile.php (Popular in <genre> shelves)
     */
    public function getByGenre(int $genreId, int $userId = 0, int $limit = 12): array
    {
        $limit = $this->clampLimit($limit);

        $sql = "
        SELECT 
            movies.id,
            movies.title,
            movies.release_year,
            movies.poster_url,
            countries.name AS country_name,
            languages.name AS language_name,
            ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
            COUNT(ratings_reviews.id) AS total_reviews
        FROM movies
        JOIN movie_genres ON movie_genres.movie_id = movies.id
        LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
        LEFT JOIN countries ON movies.country_id = countries.id
        LEFT JOIN languages ON movies.language_id = languages.id
        WHERE movie_genres.genre_id = ?
    ";

        if ($userId > 0) {
            $sql .= " AND movies.id NOT IN (SELECT movie_id FROM user_favorites WHERE user_id = ?)";
        }

        $sql .= "
        GROUP BY movies.id
        ORDER BY (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, movies.release_year DESC
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

    
    /**
     * how many times a user has favorited movies of each genre
     * Endpoint action: 'favGenreCounts'
     * Pages: pages/profile.php (Favorites pills)
     */
    public function getFavCountsByGenre(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT genres.id, genres.name, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movie_genres ON movie_genres.movie_id = user_favorites.movie_id
            JOIN genres ON genres.id = movie_genres.genre_id
            WHERE user_favorites.user_id = ?
            GROUP BY genres.id, genres.name
            HAVING cnt > 0
            ORDER BY cnt DESC, genres.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * favorites grouped by movie country
     * Endpoint action: 'favCountryCounts'
     * Pages: pages/profile.php (Favorites pills)
     */
    public function getFavCountsByCountry(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT countries.id, countries.name, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movies ON movies.id = user_favorites.movie_id
            JOIN countries ON countries.id = movies.country_id
            WHERE user_favorites.user_id = ? AND movies.country_id IS NOT NULL
            GROUP BY countries.id, countries.name
            HAVING cnt > 0
            ORDER BY cnt DESC, countries.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * how many distinct rated movies fall under each language
     * Endpoint action: 'favLanguageCounts'
     * Pages: pages/profile.php (Favorites pills)
     */
    public function getFavCountsByLanguage(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT languages.id, languages.name, COUNT(*) AS cnt
            FROM user_favorites
            JOIN movies ON movies.id = user_favorites.movie_id
            JOIN languages ON languages.id = movies.language_id
            WHERE user_favorites.user_id = ? AND movies.language_id IS NOT NULL
            GROUP BY languages.id, languages.name
            HAVING cnt > 0
            ORDER BY cnt DESC, languages.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * how many unique movies a user has rated/reviewed, grouped by genre
     * Endpoint action: 'ratedGenreCounts'
     * Pages: pages/profile.php (Rated pills)
     */
    public function getRatedCountsByGenre(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT genres.id, genres.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT ratings_reviews.movie_id FROM ratings_reviews WHERE ratings_reviews.user_id = ?
            ) user_movies
            JOIN movie_genres ON movie_genres.movie_id = user_movies.movie_id
            JOIN genres ON genres.id = movie_genres.genre_id
            GROUP BY genres.id, genres.name
            HAVING cnt > 0
            ORDER BY cnt DESC, genres.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * how many unique movies a user has rated/reviewed, grouped by the country
     * Endpoint action: 'ratedCountryCounts'
     * Pages: pages/profile.php (Rated pills)
     */
    public function getRatedCountsByCountry(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT countries.id, countries.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT ratings_reviews.movie_id FROM ratings_reviews WHERE ratings_reviews.user_id = ?
            ) user_movies
            JOIN movies ON movies.id = user_movies.movie_id
            JOIN countries ON countries.id = movies.country_id
            WHERE movies.country_id IS NOT NULL
            GROUP BY countries.id, countries.name
            HAVING cnt > 0
            ORDER BY cnt DESC, countries.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * how many unique movies a user has rated/reviewed, grouped by language
     * Endpoint action: 'ratedLanguageCounts'
     * Pages: pages/profile.php (Rated pills)
     */
    public function getRatedCountsByLanguage(int $userId): array
    {
        if ($userId <= 0) return [];
        $sql = "
            SELECT languages.id, languages.name, COUNT(*) AS cnt
            FROM (
                SELECT DISTINCT ratings_reviews.movie_id FROM ratings_reviews WHERE ratings_reviews.user_id = ?
            ) user_movies
            JOIN movies ON movies.id = user_movies.movie_id
            JOIN languages ON languages.id = movies.language_id
            WHERE movies.language_id IS NOT NULL
            GROUP BY languages.id, languages.name
            HAVING cnt > 0
            ORDER BY cnt DESC, languages.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Movies related to a given movie (by shared genres)
     * Endpoint action: 'related'
     * Pages: pages/viewMovie.php (Related movies)
     */
    public function getRelatedToMovie(int $movieId, int $limit = 12): array
    {
        if ($movieId <= 0) return [];
        $limit = $this->clampLimit($limit);

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.release_year,
                movies.poster_url,
                countries.name AS country_name,
                languages.name AS language_name,
                COUNT(DISTINCT movie_genres.genre_id) AS match_genres,
                ROUND(COALESCE(AVG(ratings_reviews.rating), 0), 2) AS avg_rating,
                COUNT(ratings_reviews.id) AS total_reviews
            FROM movies
            JOIN movie_genres ON movie_genres.movie_id = movies.id
            JOIN movie_genres ref ON ref.genre_id = movie_genres.genre_id AND ref.movie_id = ?
            LEFT JOIN ratings_reviews ON ratings_reviews.movie_id = movies.id
            LEFT JOIN countries ON movies.country_id = countries.id
            LEFT JOIN languages ON movies.language_id = languages.id
            WHERE movies.id <> ?
            GROUP BY movies.id
            ORDER BY match_genres DESC, (AVG(ratings_reviews.rating) IS NULL), AVG(ratings_reviews.rating) DESC, movies.release_year DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii', $movieId, $movieId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    //   Ensure the requested limit is within a safe range.
    //   - limit <= 0  => default to 12
    //   - limit > max => clamp down to max (default 48)
    private function clampLimit(int $limit, int $max = 48): int
    {
        if ($limit <= 0) $limit = 12;
        if ($limit > $max) $limit = $max;
        return $limit;
    }

    
    // Example:
    // $typeString = 'iii';
    // $values = [10, 20, 30];
    // $this->bindParams($stmt, $typeString, $values);
    private function bindParams(mysqli_stmt $statement, string $typeString, array $values): void
    {
        $paramRefs = [];
        $paramRefs[] = &$typeString; // first is the types string, by reference
        foreach ($values as $index => $value) {
            // push each value by reference; don't use the $value copy
            // kase yung $value copy lang, di magbabago pag nagbago yung original
            $paramRefs[] = &$values[$index];
        }
        call_user_func_array([$statement, 'bind_param'], $paramRefs);
    }
}
