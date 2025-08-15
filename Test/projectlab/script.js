$(document).ready(function() {
    // Check session on page load
    checkSession();

    // Show register form
    $('#show-register-btn').on('click', function() {
        $('.form-container').hide();
        $('#register-form-container').fadeIn();
    });

    // Show login form
    $('#show-login-btn').on('click', function() {
        $('.form-container').hide();
        $('#login-form-container').fadeIn();
    });

    // Handle user registration
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        const username = $('#reg-username').val();
        const password = $('#reg-password').val();

        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'register', username: username, password: password },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    $('#register-form')[0].reset();
                    $('#register-form-container').hide();
                    $('#login-form-container').fadeIn();
                }
            }
        });
    });

    // Handle user login
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        const username = $('#log-username').val();
        const password = $('#log-password').val();

        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'login', username: username, password: password },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    $('#login-form')[0].reset();
                    checkSession();
                }
            }
        });
    });

    // Handle user logout
    $('#logout-btn').on('click', function() {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'logout' },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, 'success');
                checkSession();
            }
        });
    });

    // Handle search input
    $('#search-input').on('keyup', function() {
        const query = $(this).val();
        if (query.length > 2) {
            searchMovies(query);
        } else {
            getAllMovies();
        }
    });

    // Function to render a movie card
    function renderMovie(movie) {
        return `
            <div class="movie-card">
                <img src="${movie.poster_url}" alt="${movie.title} poster">
                <div class="movie-details">
                    <h3>${movie.title}</h3>
                    <p>${movie.release_year} | ${movie.genre}</p>
                </div>
                <div class="movie-actions">
                    <button class="movie-btn rate-btn" data-movie-id="${movie.id}" data-movie-title="${movie.title}">Rate/Review</button>
                    <button class="movie-btn favorite-btn" data-movie-id="${movie.id}">Add to Favorites</button>
                </div>
            </div>
        `;
    }

    // Function to display movies
    function displayMovies(movies, containerId) {
        const container = $(containerId);
        container.empty();
        if (movies.length > 0) {
            movies.forEach(movie => {
                container.append(renderMovie(movie));
            });
        } else {
            container.append('<p>No movies found.</p>');
        }
    }

    // Function to check user session and update UI
    function checkSession() {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'checkSession' },
            dataType: 'json',
            success: function(response) {
                if (response.isLoggedIn) {
                    $('#auth-buttons').hide();
                    $('#user-info').css('display', 'flex').show();
                    $('#welcome-message').text('Welcome, ' + response.user.username + '!');
                    $('.form-container').hide();
                    $('#main-content').show();
                    getAllMovies();
                    getUserFavorites();
                    getRecommendations();
                } else {
                    $('#auth-buttons').css('display', 'flex').show();
                    $('#user-info').hide();
                    $('#main-content').hide();
                    $('#login-form-container').show();
                }
            }
        });
    }

    // Function to get all movies
    function getAllMovies() {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'getMovies' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMovies(response.data, '#movie-list');
                }
            }
        });
    }

    // Function to search for movies
    function searchMovies(query) {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'searchMovies', query: query },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMovies(response.data, '#movie-list');
                }
            }
        });
    }

    // Function to get user favorites
    function getUserFavorites() {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'getFavorites' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMovies(response.data, '#favorites-list');
                }
            }
        });
    }

    // Function to get recommendations
    function getRecommendations() {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'getRecommendations' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMovies(response.data, '#recommendations-list');
                }
            }
        });
    }

    // Handle Add to Favorites
    $(document).on('click', '.favorite-btn', function() {
        const movieId = $(this).data('movie-id');
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'addFavorite', movie_id: movieId },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    getUserFavorites();
                }
            }
        });
    });

    // Handle message box display
    function showMessage(message, type) {
        const box = $('#message-box');
        box.text(message).removeClass('success error').addClass(type).fadeIn();
        setTimeout(() => box.fadeOut(), 3000);
    }
    
    // Open rating modal when "Rate/Review" button is clicked
    $(document).on('click', '.rate-btn', function() {
        const movieId = $(this).data('movie-id');
        const movieTitle = $(this).data('movie-title');
        $('#rating-movie-id').val(movieId);
        $('#modal-movie-title').text(`Rate and Review: ${movieTitle}`);
        $('#rating-modal').fadeIn();
    });

    // Close rating modal
    $('.close-btn').on('click', function() {
        $('#rating-modal').fadeOut();
    });

    // Close modal if user clicks outside of it
    $(window).on('click', function(event) {
        if ($(event.target).is('#rating-modal')) {
            $('#rating-modal').fadeOut();
        }
    });

    // Handle rating and review form submission
    $('#rating-form').on('submit', function(e) {
        e.preventDefault();
        const movieId = $('#rating-movie-id').val();
        const rating = $('input[name="rating"]:checked').val();
        const review = $('#review-text').val();
        
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { action: 'rateMovie', movie_id: movieId, rating: rating, review: review },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    $('#rating-modal').fadeOut();
                    $('#rating-form')[0].reset();
                    // You might want to refresh the recommendations or movie list here
                    getRecommendations();
                }
            }
        });
    });
});