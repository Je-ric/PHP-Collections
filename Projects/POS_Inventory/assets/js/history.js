// function filterAddHistoryTable() {
//     const userFilter = $('#addHistoryUserFilter').val().toLowerCase();
//     const selectedDate = $('#addHistoryDateFilter').val();
//     const actionFilter = $('#filterDropdown').val().toLowerCase();
//     let anyVisible = false;

//     $('#addHistoryTable tbody tr').each(function() {
//         const dateCell = $(this).find('td:nth-child(1)').text(); 
//         const userCell = $(this).find('td:nth-child(2)').text().toLowerCase(); 
//         const detailCell = $(this).find('td:nth-child(3)').text().toLowerCase(); 

//         const userMatch = userCell.indexOf(userFilter) > -1;
//         const dateMatch = selectedDate ? dateCell === selectedDate : true;
//         const actionMatch = actionFilter ? detailCell.indexOf(actionFilter) > -1 : true;

//         const isVisible = userMatch && dateMatch && actionMatch;
//         $(this).toggle(isVisible);

//         if (isVisible) {
//             anyVisible = true;
//         }
//     });

//     $('#addHistoryTable + .no-records').toggle(!anyVisible);
// }


// $('#addHistoryUserFilter').on('input', filterAddHistoryTable);
// $('#addHistoryDateFilter').on('change', filterAddHistoryTable);
// $('#filterDropdown').on('change', filterAddHistoryTable);



// function filterSalesHistoryTable() {
//     const userFilter = $('#salesUserFilter').val().toLowerCase();
//     const selectedDate = $('#salesDateFilter').val();
//     let anyVisible = false;

//     $('#salesHistoryTable tbody tr').each(function() {
//         const dateCell = $(this).find('td:nth-child(2)').text(); 
//         const userCell = $(this).find('td:nth-child(3)').text().toLowerCase(); 

        
//         const userMatch = userCell.indexOf(userFilter) > -1;
//         const dateMatch = selectedDate ? dateCell.startsWith(selectedDate) : true; 

//         const isVisible = userMatch && dateMatch;
//         $(this).toggle(isVisible);

//         if (isVisible) {
//             anyVisible = true;
//         }
//     });

//     $('#salesHistoryTable + .no-records').toggle(!anyVisible);
// }

// $('#salesUserFilter').on('input', filterSalesHistoryTable);
// $('#salesDateFilter').on('change', filterSalesHistoryTable);
