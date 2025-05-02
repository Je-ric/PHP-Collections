<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiplication Table</title>
    <style>
        *{
            cursor: pointer;
        
        }
        body {
            overflow: hidden;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #34495e;
            text-align: center;
            padding: 6px;
            position: relative;
            font-size: 14px;
        }

        th {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .highlight {
            background-color: #2772A5;
            color: #E4E4E4;
        }

        .current-number {
            background-color: #ffc107;
            color: black;
        }

        tr:nth-child(odd) {
            background-color: #ecf0f1;
        }

        #result {
            margin-top: 10px;
            font-weight: bold;
            font-size: 30px;
            width: auto;
            height: 50px;
            margin-top: 10%;
            text-align: center;
            background-color: transparent;
            color: black;
        
            padding: 10px;
        }

        #tableTitle {
            margin-top: 10px;
            text-align: center;
            font-size: 24px;
        }
        
        /* tr:nth-child(even) td:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:nth-child(odd) td:nth-child(odd) {
            background-color: #ecf0f1;
        }

        tr:nth-child(even) td:nth-child(odd),
        tr:nth-child(odd) td:nth-child(even) {
            background-color: #bdc3c7;
        } */
        
        #group-1{
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    $tableSize = 20;

        echo "<div id='group-1'>";
            echo "<div id='tableTitle'>";
                echo "<h2><br>Multiplication Table</h2>";
            echo "</div>";
            echo "<div id='result'>";
            echo "</div>";
            
        echo "</div>";

        echo "<table id='multiplicationTable'>";
        echo "<tr>";
        echo "<th onclick='highlightColumn(0)'>x</th>";
        for ($i = 1; $i <= $tableSize; $i++) {
            echo "<th onclick='highlightColumn($i)'>$i</th>";
        }
        echo "</tr>";

        for ($i = 1; $i <= $tableSize; $i++) {
            echo "<tr><th>$i</th>";
            for ($j = 1; $j <= $tableSize; $j++) {
                $multiplicationExpression = "$i x $j";
                $result = $i * $j;
                echo "<td data-value='$multiplicationExpression'>" . "$result" . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    ?>
</div>

<script>
    const table = document.getElementById('multiplicationTable');
    const resultDiv = document.getElementById('result');

    table.addEventListener('mouseover', highlightTable);
    table.addEventListener('mouseout', removeHighlights);

    function highlightTable(event) {
        const target = event.target;

        if (target.tagName === 'TD' && !isNaN(target.innerHTML.trim())) {
            const rowIndex = target.parentNode.rowIndex;
            const cellIndex = target.cellIndex;
            const currentNumber = target.innerHTML.trim();
            const multiplicationExpression = target.getAttribute('data-value');

            removeHighlights();

            for (let i = 0; i < table.rows[rowIndex].cells.length; i++) {
                table.rows[rowIndex].cells[i].classList.add('highlight');
            }

            for (let i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[cellIndex].classList.add('highlight');
            }
            

            target.classList.add('current-number');
            resultDiv.textContent = ` ${multiplicationExpression} = ${currentNumber}`;
        }
    }

    function removeHighlights() {
        const highlightedCells = document.querySelectorAll('.highlight, .current-number');
        highlightedCells.forEach(cell => {
            cell.classList.remove('highlight', 'current-number');
        });

        resultDiv.textContent = '';
    }
</script>

</body>
</html>
