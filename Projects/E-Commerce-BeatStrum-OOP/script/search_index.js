document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('#search');
    const searchResults = document.querySelector('#search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();
        if (query.length >= 2) {
            fetchPredictions(query);
        } else {
            searchResults.innerHTML = ''; // Clear search results
        }
    });

    function fetchPredictions(query) {
        fetch('search.php?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(predictions => {
                displayPredictions(predictions);
            });
    }

    function displayPredictions(predictions) {
        searchResults.innerHTML = '';
        predictions.forEach(prediction => {
            const listItem = document.createElement('li');
            listItem.textContent = prediction;
            searchResults.appendChild(listItem);
        });
    }
});