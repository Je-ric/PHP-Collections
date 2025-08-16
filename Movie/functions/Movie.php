<?php
require_once __DIR__ . '/../db/config.php';

class Movie
{
  private $db;
  private $uploadDir;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->conn;
    $this->uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0777, true);
  }

  public function getAllMovies()
  {
    $result = $this->db->query("
            SELECT m.*, c.name AS country_name, l.name AS language_name
            FROM movies m
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            ORDER BY release_year DESC
        ");
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getMovieById($id)
  {
    $stmt = $this->db->prepare("
            SELECT m.*, c.name AS country_name, l.name AS language_name
            FROM movies m
            LEFT JOIN countries c ON m.country_id = c.id
            LEFT JOIN languages l ON m.language_id = l.id
            WHERE m.id=?
        ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }

public function addMovie($title, $description, $release_year, $posterFile, $trailer_url, $countryName, $languageName, $genreIds = []) {
    $country_id = $this->getCountryId($countryName);
    $language_id = $this->getLanguageId($languageName);
    $posterPath = $this->handleUpload($posterFile, $title, $release_year);

    $stmt = $this->db->prepare(
      "INSERT INTO movies (
                  title, description, release_year, 
                  poster_url, trailer_url, 
                  country_id, language_id)
              VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssissii", 
              $title, $description, $release_year, 
              $posterPath, $trailer_url, 
              $country_id, $language_id);
    $stmt->execute();

    $movieId = $stmt->insert_id;

    if (!empty($genreIds)) {
        $this->updateMovieGenres($movieId, $genreIds);
    }

    return $movieId;
}

public function updateMovie($id, $title, $description, $release_year, $posterFile, $trailer_url, $countryName, $languageName, $genreIds = []) {
    $country_id = $this->getCountryId($countryName);
    $language_id = $this->getLanguageId($languageName);

    $posterPath = $posterFile['error'] === UPLOAD_ERR_OK
        ? $this->handleUpload($posterFile, $title, $release_year)
        : $this->getMovieById($id)['poster_url'];

    $stmt = $this->db->prepare(
      "UPDATE movies 
              SET title=?, description=?, release_year=?, 
                  poster_url=?, trailer_url=?, 
                  country_id=?, language_id=?
              WHERE id=?"
    );
    $stmt->bind_param("ssissiii", 
        $title, $description, $release_year, 
              $posterPath, $trailer_url, 
              $country_id, $language_id, $id);
    $stmt->execute();

    $this->updateMovieGenres($id, $genreIds);

    return true;
}

  public function deleteMovie($id)
  {
    $movie = $this->getMovieById($id);
    if ($movie && !empty($movie['poster_url']) && file_exists(__DIR__ . '/../' . $movie['poster_url'])) {
      unlink(__DIR__ . '/../' . $movie['poster_url']); // delete file
    }
    $stmt = $this->db->prepare("DELETE FROM movies WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  
  private function handleUpload($uploadedFile, $movieTitle, $releaseYear)
  {
    $safeTitle = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($movieTitle));
    $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $newFileName = $safeTitle . '_' . $releaseYear . '.' . $fileExtension;

    $uploadDirectory = __DIR__ . '/../uploads/posters/';
    if (!is_dir($uploadDirectory)) {
      mkdir($uploadDirectory, 0777, true);
    }

    $destinationPath = $uploadDirectory . $newFileName;
    if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
      return 'uploads/posters/' . $newFileName;
    }

    return null; // failed
  }


// ========================================================================
public function getAllGenres() {
    $result = $this->db->query("SELECT * FROM genres ORDER BY name ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function getGenresByMovie($movieId) {
    $stmt = $this->db->prepare("SELECT genre_id FROM movie_genres WHERE movie_id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    return $result ? array_column($result, 'genre_id') : [];
}

public function updateMovieGenres($movieId, $genreIds) {
    // Remove old genres
    $stmt = $this->db->prepare("DELETE FROM movie_genres WHERE movie_id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();

    // Insert new genres
    $stmt = $this->db->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
    foreach ($genreIds as $genreId) {
        $stmt->bind_param("ii", $movieId, $genreId);
        $stmt->execute();
    }
}

// ========================================================================
  private function getCountryId($countryName)
  {
    // if country exists, get its ID
    $stmt = $this->db->prepare("SELECT id FROM countries WHERE name = ?");
    $stmt->bind_param("s", $countryName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
      return $row['id'];
    }

    // If not exists, insert
    $stmt = $this->db->prepare("INSERT INTO countries (name) VALUES (?)");
    $stmt->bind_param("s", $countryName);
    $stmt->execute();
    return $stmt->insert_id;
  }

  // Same 
  private function getLanguageId($languageName)
  {
    $stmt = $this->db->prepare("SELECT id FROM languages WHERE name = ?");
    $stmt->bind_param("s", $languageName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
      return $row['id'];
    }

    $stmt = $this->db->prepare("INSERT INTO languages (name) VALUES (?)");
    $stmt->bind_param("s", $languageName);
    $stmt->execute();
    return $stmt->insert_id;
  }
}
