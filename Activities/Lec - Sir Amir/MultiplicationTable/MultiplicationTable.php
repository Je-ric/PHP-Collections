<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiplication Table</title>
    <link rel="stylesheet" href="/PHP-Projects/MultiplicationTable/MultiplicationTable.css">
</head>
<body>
<!-- Jeric J. Dela Cruz
    BSIT 2- 2-->
<div class="container">
    <?php
        $tableSize = 20;

        echo "<table id='multiplicationTable'>";
        echo "<tr>";
        echo "<th></th>";
        for ($i = 1; $i <= $tableSize; $i++) {
            echo "<th>$i</th>";
        }
        echo "</tr>";

        for ($i = 1; $i <= $tableSize; $i++) {
            echo "<tr>";
            echo "<th>$i</th>";
            for ($j = 1; $j <= $tableSize; $j++) {
                $value = "$i x $j";
                $result = $i * $j;
                echo "<td data-value='$value' onmouseover='highlightNumbers($i, $j)' onmouseout='removeHighlight()'>" . "$result" . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    ?>
</div>

<script src="/PHP-Projects/MultiplicationTable/MultiplicationTable.js"></script>
</body>
</html>
