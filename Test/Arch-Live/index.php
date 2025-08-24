<?php
session_start();
include 'db_conn.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch research papers the user has access to
$query = "
    SELECT r.id, r.title, r.status, r.year
    FROM research r
    JOIN research_sharing rs ON r.id = rs.research_id
    WHERE rs.user_id = ? AND rs.access_level = 'edit'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Research List</title>
</head>
<body>

<h2>Available Research Papers</h2>
<button onclick="location.href='editor.php?new=true'">+ Create New Research</button>

<ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <a href="editor.php?research_id=<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?> (<?= $row['year'] ?>) - <?= ucfirst($row['status']) ?>
            </a>
        </li>
    <?php endwhile; ?>
</ul>
<a href="logout.php">Logout</a>

</body>
</html>
