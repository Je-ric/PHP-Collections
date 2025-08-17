<?php
require_once __DIR__ . '/../db/config.php';

class People {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Add person if not exists; return person_id
    public function addPerson(string $name): int {
        $name = trim($name);
        if ($name === '') return 0;

        // Check if person already exists
        $stmt = $this->db->prepare("SELECT id FROM movie_people WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $stmt->close();
            return (int)$row['id'];
        }
        $stmt->close();

        // Insert new person
        $stmt = $this->db->prepare("INSERT INTO movie_people (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return (int)$id;
    }

    // Attach person to movie (Director or Cast)
    public function attachToMovie(int $movieId, int $personId, string $role): bool {
        if (!in_array($role, ['Director','Cast'], true)) return false;

        // Prevent duplicates
        $stmt = $this->db->prepare(
            "SELECT id FROM movie_cast WHERE movie_id = ? AND person_id = ? AND role = ?"
        );
        $stmt->bind_param("iis", $movieId, $personId, $role);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->fetch_assoc()) {
            $stmt->close();
            return false; // already linked
        }
        $stmt->close();

        // Insert into movie_cast
        $stmt = $this->db->prepare(
            "INSERT INTO movie_cast (movie_id, person_id, role) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iis", $movieId, $personId, $role);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get all people linked to a movie (optional by role)
    public function getMoviePeople(int $movieId, string $role = ''): array {
        if ($role !== '') {
            $stmt = $this->db->prepare(
                "SELECT mp.id, p.name 
                 FROM movie_cast mp
                 JOIN movie_people p ON mp.person_id = p.id
                 WHERE mp.movie_id = ? AND mp.role = ?
                 ORDER BY p.name"
            );
            $stmt->bind_param("is", $movieId, $role);
        } else {
            $stmt = $this->db->prepare(
                "SELECT mp.id, p.name 
                 FROM movie_cast mp
                 JOIN movie_people p ON mp.person_id = p.id
                 WHERE mp.movie_id = ?
                 ORDER BY p.name"
            );
            $stmt->bind_param("i", $movieId);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    // Remove person from a movie
    public function removeFromMovie(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM movie_cast WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
