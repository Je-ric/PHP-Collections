<?php
require_once __DIR__ . '/../db/config.php';

class Movie {
    private $db;
    private $uploadDir;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;

        $this->uploadDir = __DIR__ . '/../uploads/posters/';
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0777, true);
    }

    public function getAllMovies() {
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name
            FROM movies m
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            ORDER BY release_year DESC
        ";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMovieById($id) {
        $sql = "
            SELECT m.*, c.name AS country_name, l.name AS language_name
            FROM movies m
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE m.id = ?
        ";
        $query = $this->db->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc();
    }

    // ==========================
    // manageMovie.php
    // =========================== 
    public function addMovie($title, $description, $releaseYear, $posterFile, $trailerUrl, $countryName, $languageName, $genreIds = []) {
        $countryId = $this->getCountryId($countryName);
        $languageId = $this->getLanguageId($languageName);
        $posterPath = $this->handleUpload($posterFile, $title, $releaseYear);

        $sql = "
            INSERT INTO movies (title, description, release_year, poster_url, trailer_url, country_id, language_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $query = $this->db->prepare($sql);
        $query->bind_param("ssissii", $title, $description, $releaseYear, $posterPath, $trailerUrl, $countryId, $languageId);
        $query->execute();

        $movieId = $query->insert_id;
        if (!empty($genreIds)) $this->updateMovieGenres($movieId, $genreIds);

        return $movieId;
    }

    public function updateMovie($id, $title, $description, $releaseYear, $posterFile, $trailerUrl, $countryName, $languageName, $genreIds = []) {
        $countryId = $this->getCountryId($countryName);
        $languageId = $this->getLanguageId($languageName);

        $posterPath = $posterFile['error'] === UPLOAD_ERR_OK
            ? $this->handleUpload($posterFile, $title, $releaseYear)
            : $this->getMovieById($id)['poster_url'];

        $sql = "
            UPDATE movies 
            SET title=?, description=?, release_year=?, poster_url=?, trailer_url=?, country_id=?, language_id=?
            WHERE id=?
        ";
        $query = $this->db->prepare($sql);
        $query->bind_param("ssissiii", $title, $description, $releaseYear, $posterPath, $trailerUrl, $countryId, $languageId, $id);
        $query->execute();

        $this->updateMovieGenres($id, $genreIds);
        return true;
    }

    public function deleteMovie($id) {
        $movie = $this->getMovieById($id);

        if ($movie && !empty($movie['poster_url']) && file_exists(__DIR__ . '/../' . $movie['poster_url'])) {
            unlink(__DIR__ . '/../' . $movie['poster_url']);
        }

        $sql = "DELETE FROM movies WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bind_param("i", $id);
        return $query->execute();
    }

    // ==========================
    // FILE UPLOAD HANDLER 
    // ===========================
    private function handleUpload($uploadedFile, $movieTitle, $releaseYear) {
        $safeTitle = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($movieTitle));
        $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
        $newFileName = $safeTitle . '_' . $releaseYear . '.' . $fileExtension;

        $destinationPath = $this->uploadDir . $newFileName;
        if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
            return 'uploads/posters/' . $newFileName;
        }
        return null;
    }

    // ==========================
    // Getter
    // ===========================
    public function getAllGenres() {
      $sql = "SELECT * FROM genres ORDER BY name ASC";
      $result = $this->db->query($sql);

      if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
      }

      return [];
    }

    public function getGenresByMovie($movieId, $return = 'name') {
      // $return = 'name' or 'id'
      $sql = "
          SELECT g.id, g.name
          FROM movie_genres mg
          JOIN genres g ON mg.genre_id = g.id
          WHERE mg.movie_id = ?
      ";
      $query = $this->db->prepare($sql);
      $query->bind_param("i", $movieId);
      $query->execute();
      $result = $query->get_result();

      $genres = [];
      while ($row = $result->fetch_assoc()) {
          if ($return === 'id') {
              $genres[] = $row['id'];
          } else {
              $genres[] = $row['name'];
          }
      }
      return $genres;
  }

    public function updateMovieGenres($movieId, $genreIds) {
        $sql = "DELETE FROM movie_genres WHERE movie_id = ?";
        $query = $this->db->prepare($sql);
        $query->bind_param("i", $movieId);
        $query->execute();

        $sql = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)";
        $query = $this->db->prepare($sql);
        foreach ($genreIds as $genreId) {
            $query->bind_param("ii", $movieId, $genreId);
            $query->execute();
        }
    }

    // ==========================
    // Getter
    // ===========================
    private function getCountryId($countryName) {
        $sql = "SELECT id FROM countries WHERE name = ?";
        $query = $this->db->prepare($sql);
        $query->bind_param("s", $countryName);
        $query->execute();
        $result = $query->get_result();

        if ($row = $result->fetch_assoc()) return $row['id'];

        $sql = "INSERT INTO countries (name) VALUES (?)";
        $query = $this->db->prepare($sql);
        $query->bind_param("s", $countryName);
        $query->execute();
        return $query->insert_id;
    }

    private function getLanguageId($languageName) {
        $sql = "SELECT id FROM languages WHERE name = ?";
        $query = $this->db->prepare($sql);
        $query->bind_param("s", $languageName);
        $query->execute();
        $result = $query->get_result();

        if ($row = $result->fetch_assoc()) return $row['id'];

        $sql = "INSERT INTO languages (name) VALUES (?)";
        $query = $this->db->prepare($sql);
        $query->bind_param("s", $languageName);
        $query->execute();
        return $query->insert_id;
    }
}
