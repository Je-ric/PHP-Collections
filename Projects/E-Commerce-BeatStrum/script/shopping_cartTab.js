function showTab(tabId) {
    // Hide all tab contents
    var tabContents = document.getElementsByClassName('tab-content');
    for (var i = 0; i < tabContents.length; i++) {
        tabContents[i].style.display = 'none';
    }
    
    // Remove active class from all tab buttons
    var tabBtns = document.getElementsByClassName('tab-btn');
    for (var i = 0; i < tabBtns.length; i++) {
        tabBtns[i].querySelector('button').classList.remove('active-tab-btn');
    }

    // Show the selected tab content
    document.getElementById(tabId).style.display = 'block';

    // Add active class to the clicked tab button
    document.getElementById(tabId + 'Btn').classList.add('active-tab-btn');
}

showTab('cartTab');