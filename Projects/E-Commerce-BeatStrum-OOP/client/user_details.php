<?php
session_start();
include('config.php');

if (!isset($_SESSION['client_id'])) { 
    header("Location: index.php");
    exit();
}


$client_id = $_SESSION['client_id'];


$sql = "SELECT * FROM client_accounts WHERE id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows > 0) {
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Account Information</title>
<style>
   <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\login_client.css'); ?>
</style>
    </head>
    <body>
    <?php 
    include ('1.header.php');
?>
        <div class="form-container">
    <h1>Account Information</h1>
    <div class="form-inside-container">
        <?php
        while($row = $result->fetch_assoc()) {
            
            echo "<div class='left'>";
            // echo "<strong>ID:</strong><p>" . $row["id"]. "</p>";
            echo "<strong>Username:</strong><p>" . $row["username"]. "</p>";
            echo "<strong>Name:</strong><p>" . $row["name"]. "</p>";
            echo "</div>";

            
            echo "<div class='right'>";
            echo "<strong>Age:</strong><p>" . $row["age"]. "</p>";
            echo "<strong>Phone:</strong><p>" . $row["phone"]. "</p>";
            echo "<strong>Address:</strong><p>" . $row["address"]. "</p>";
            echo "</div>";
        }
        ?>
    </div>
    <div class="text-box">
        <form action="logout.php" method="post">
            <input class="logout-button" type="submit" value="Logout">
        </form>
    </div>
</div>

    </body>
    </html>
    <?php
} else {
    echo "<div class='container'>";
    echo "<p>No account found for the current user.</p>";
    echo "</div>";
}


$stmt->close();


$conn->close();
?>
