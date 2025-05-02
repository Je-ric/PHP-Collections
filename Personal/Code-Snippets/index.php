<?php
include 'config.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$sql = "SELECT COUNT(id) AS total FROM code_snippets";
$result = $conn->query($sql);
$total = $result->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$sql = "SELECT id, title, html, css, js FROM code_snippets ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $title = htmlspecialchars($row['title']);
        $htmlCode = htmlspecialchars($row['html']);
        $cssCode = htmlspecialchars($row['css']);
        $jsCode = htmlspecialchars($row['js']);
        $output .= "<div class='code-box'>
                        <h3>$title</h3>
                        <iframe srcdoc=\"$htmlCode<style>$cssCode</style><script>$jsCode<\/script>\"></iframe>
                        <a href='code.php?id={$row['id']}'>View Code</a>
                        <a href='index.php?id={$row['id']}' class='delete-btn' onclick='return confirmDelete()'>Delete</a>
                    </div>";
    }
} else {
    $output .= '<p>No code snippets found.</p>';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM code_snippets WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting code snippet: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Snippets Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .code-container {
            display: flex;
            gap: 30px;
            padding: 20px;
        }
        .code-box {
            border: 1px solid #ccc;
            padding: 10px;
            width: 40%;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            height: 300px;
            border: none;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .delete-btn {
            color: red;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>Code Snippets Dashboard</h1>
    <a href="code.php" id="add-code-btn">Add New Code</a>

    <div class="code-container">
        <?php echo $output; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this code snippet?");
        }
    </script>
</body>
</html>

