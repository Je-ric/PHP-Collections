<?php
function compute($first, $second, $type) {
    switch ($type) {
        case 'Add':
            return $first + $second;
        case 'Subtract':
            return $first - $second;
        case 'Multiply':
            return $first * $second;
        case 'Divide':
            return $first / $second;
        default:
            return "Error: Invalid operation!";
    }
}

function display($result) {
    echo "<br>Result: <input type='text' value='$result'><br>";
}

if ($_POST) {
    $first = $_POST['first'];
    $second = $_POST['second'];

    if(isset($_POST['Add'])) {
        $type = 'Add';
    } elseif(isset($_POST['Subtract'])) {
        $type = 'Subtract';
    } elseif(isset($_POST['Multiply'])) {
        $type = 'Multiply';
    } elseif(isset($_POST['Divide'])) {
        $type = 'Divide';
    } else {
        $type = ''; 
    }

    if($type) {
        $result = compute($first, $second, $type);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <form action="" method="post" style="border: 1px solid black; flex: 1; padding: 10px; margin-right: 10px;">
        <h1>PHP - Simple Calculator Program</h1>

        First Number: <input type="number" name="first" required><br><br>
        Second:  <input type="number" name="second" required>
        <br>
        <input type="submit" value="Add" name="Add">
        <input type="submit" value="Subtract" name="Subtract">
        <input type="submit" value="Multiply" name="Multiply">
        <input type="submit" value="Divide" name="Divide">
        <br>
        <?php if(isset($result)) display($result); ?>
    </form>
</body>
</html>
