function highlightNumbers(row, col) {
    const table = document.getElementById('multiplicationTable');
    
    const cell = table.rows[row].cells[col];
    cell.classList.add('current-number');
    
    const rowHeader = table.rows[row].cells[0];
    rowHeader.classList.add('current-number');
    
    const colHeader = table.rows[0].cells[col];
    colHeader.classList.add('current-number');
}

function removeHighlight() {
    const highlightedCells = document.querySelectorAll('.current-number');
    highlightedCells.forEach(cell => {
        cell.classList.remove('current-number');
    });
}