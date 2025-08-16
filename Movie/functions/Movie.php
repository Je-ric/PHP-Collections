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
    $result = $this->db->query("SELECT * FROM movies ORDER BY release_year DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getMovieById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM movies WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }

  public function addMovie($title, $description, $release_year, $posterFile, $trailer_url)
  {
    $posterPath = $this->handleUpload($posterFile, $title, $release_year);
    $stmt = $this->db->prepare("INSERT INTO movies (title, description, release_year, poster_url, trailer_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $title, $description, $release_year, $posterPath, $trailer_url);
    return $stmt->execute();
  }

  public function updateMovie($id, $title, $description, $release_year, $posterFile, $trailer_url)
  {
    $posterPath = $posterFile['error'] === UPLOAD_ERR_OK
      ? $this->handleUpload($posterFile, $title, $release_year)
      : $this->getMovieById($id)['poster_url'];

    $stmt = $this->db->prepare("UPDATE movies SET title=?, description=?, release_year=?, poster_url=?, trailer_url=? WHERE id=?");
    $stmt->bind_param("ssissi", $title, $description, $release_year, $posterPath, $trailer_url, $id);
    return $stmt->execute();
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
    // Get the file extension (ex. jpg, png)
    $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

    // concat
    $newFileName = $safeTitle . '_' . $releaseYear . '.' . $fileExtension;

    $uploadDirectory = __DIR__ . '/../uploads/posters/';
    if (!is_dir($uploadDirectory)) {
      mkdir($uploadDirectory, 0777, true);
    }

    $destinationPath = $uploadDirectory . $newFileName;
    if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
      return 'uploads/posters/' . $newFileName;
    }

    // failed
    return null;
  }
}
