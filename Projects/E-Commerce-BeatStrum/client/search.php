<?php
include('config.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    
    // Perform a database query to fetch predicted results
    $sql = "SELECT name FROM items WHERE name LIKE '%$query%' LIMIT 5";
    $result = $conn->query($sql);

    $predictions = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $predictions[] = $row['name'];
        }
    }
    
    // Return the predicted results as JSON
    echo json_encode($predictions);
}
?>
