<?php
include('config.php');

class Autocomplete {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPredictions($query) {
        $predictions = array();

        $sql = "SELECT name FROM items WHERE name LIKE '%$query%' LIMIT 5";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $predictions[] = $row['name'];
            }
        }

        return $predictions;
    }
}

// Main script
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $autocomplete = new Autocomplete($conn);
    $predictions = $autocomplete->getPredictions($query);

    echo json_encode($predictions);
}
?>
