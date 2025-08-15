<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendation System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Movie Recommender</h1>
        <div id="auth-buttons">
            <button id="show-register-btn" class="auth-btn">Register</button>
            <button id="show-login-btn" class="auth-btn">Login</button>
        </div>
        <div id="user-info" style="display:none;">
            <span id="welcome-message"></span>
            <button id="logout-btn" class="auth-btn">Logout</button>
        </div>
    </header>

    <div class="container">
        <!-- Message Box for alerts -->
        <div id="message-box" style="display:none;"></div>

        <!-- Registration Form -->
        <div id="register-form-container" class="form-container" style="display:none;">
            <h2>Register</h2>
            <form id="register-form">
                <input type="text" id="reg-username" name="username" placeholder="Username" required>
                <input type="password" id="reg-password" name="password" placeholder="Password" required>
                <button type="submit" class="submit-btn">Register</button>
            </form>
        </div>

        <!-- Login Form -->
        <div id="login-form-container" class="form-container" style="display:none;">
            <h2>Login</h2>
            <form id="login-form">
                <input type="text" id="log-username" name="username" placeholder="Username" required>
                <input type="password" id="log-password" name="password" placeholder="Password" required>
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>

        <!-- Main Content Area (visible after login) -->
        <main id="main-content" style="display:none;">
            <div id="search-container">
                <input type="text" id="search-input" placeholder="Search for a movie...">
            </div>
            
            <div id="recommendations-container">
                <h2>Recommended for you</h2>
                <div id="recommendations-list" class="movie-list"></div>
            </div>

            <div id="favorites-container">
                <h2>My Favorite Movies</h2>
                <div id="favorites-list" class="movie-list"></div>
            </div>

            <div id="movies-container">
                <h2>All Movies</h2>
                <div id="movie-list" class="movie-list"></div>
            </div>
        </main>
    </div>

    <!-- Rating and Review Modal -->
    <div id="rating-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modal-movie-title">Rate and Review Movie</h2>
            <form id="rating-form">
                <input type="hidden" id="rating-movie-id">
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">&#9733;</label>
                </div>
                <textarea id="review-text" placeholder="Write your review here..."></textarea>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>