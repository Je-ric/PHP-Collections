<?php
session_start();
include('../../includes/config.php');

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../../index.php");
    exit();
}


$selectedItems = [];

$searchName = $_GET['search_name'] ?? '';
$searchBrand = $_GET['search_brand'] ?? '';

$query = "
    SELECT item_id, name, brand, is_bogo 
    FROM Item 
    WHERE isActive = TRUE
";
if ($searchName) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}
if ($searchBrand) {
    $query .= " AND brand LIKE '%" . $conn->real_escape_string($searchBrand) . "%'";
}
$query .= " ORDER BY name";
$items = $conn->query($query);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedItems = $_POST['selected_items'] ?? []; 
    $enableBogo = isset($_POST['enable_bogo']); 

    if (empty($selectedItems)) {
        echo "No items selected!";
    } else {
        foreach ($selectedItems as $itemId) {
            $isBogo = $enableBogo ? 1 : 0;  
            
            
            $stmt = $conn->prepare("
                UPDATE Item 
                SET is_bogo = ? 
                WHERE item_id = ?
            ");
            $stmt->bind_param("ii", $isBogo, $itemId);
            $stmt->execute();
        }

        if (empty($searchName) && empty($searchBrand)) {
            header("Location: bogo.php");  
        } else {
            header("Location: bogo.php?search_name=$searchName&search_brand=$searchBrand");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set BOGO Eligibility</title>
</head>
<body>
    <h1>Set Items for Buy One Get One (BOGO) Promotion</h1>

    <form method="GET" action=" ">
        <div>
            <label for="search_name">Search by Name:</label>
            <input type="text" name="search_name" id="search_name" value="<?= htmlspecialchars($searchName) ?>">
        </div>
        <div>
            <label for="search_brand">Search by Brand:</label>
            <input type="text" name="search_brand" id="search_brand" value="<?= htmlspecialchars($searchBrand) ?>">
        </div>
        <button type="submit">Search</button>
    </form>

    <form method="POST" action="">
        <table border="1">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>BOGO Eligible</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_items[]" value="<?= $row['item_id'] ?>" class="select_item"
                                <?= in_array($row['item_id'], $selectedItems) ? 'checked' : '' ?> />
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td>
                            <?= $row['is_bogo'] ? 'Yes' : 'No' ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div>
            <label for="enable_bogo">Enable BOGO for Selected Items:</label>
            <input type="checkbox" name="enable_bogo" id="enable_bogo">
        </div>

        <button type="submit">Update BOGO Eligibility</button>
    </form>

    <script>
        
        document.getElementById('select_all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.select_item');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
</body>
</html>
