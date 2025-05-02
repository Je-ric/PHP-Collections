<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/style.css'; ?>
    </style>
    <title>User or Admin</title>
</head>
<body>
   
<div class="container">
<div class="image-container">
<img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
        <div class="overlay-text-1">C.R.U.D<br>with<br>LOGIN</div>
        <div class="overlay-text-2">Jeric J. Dela Cruz <br> BSIT_2-2 <br> INTECH 2201 </div>
    </div>
    <div class="form-container">
        <h2>Role Selection</h2>
        <form action="" method="post">
            <div class="user">
             <a href="user-login.php">
                <img src="src/User.png" alt="User">
            </a> 
                </button>
                USER
            </div>
            <div class="admin">
              <a href="admin-login.php">  
                <img src="src/Admin.png" alt="Admin">
            </a>
                </button>
                ADMIN
            </div>
        </form>
    </div>
</div>
</body>
</html>
