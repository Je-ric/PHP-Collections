<?php
include('../../includes/config.php'); 
session_start();

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../../index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userResult = $conn->query("SELECT * FROM Users WHERE user_id = $userId");
$userInfo = $userResult->fetch_assoc();

$updateMessage = ''; 
$errorMessage = '';  
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch (true) {
        case isset($_POST['edit']):
            
            $name = $_POST['name'] ?? $userInfo['name'];
            $email = $_POST['email'] ?? $userInfo['email'];
            $phone = $_POST['phone'] ?? $userInfo['phone'];
            $username = $_POST['username'] ?? $userInfo['username'];
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $user_image = $userInfo['user_image']; 

            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
                $image_name = $_FILES['user_image']['name'];
                $image_tmp = $_FILES['user_image']['tmp_name'];
                $user_image = '../../upload_images/user_images/' . time() . '_' . $image_name;
                move_uploaded_file($image_tmp, $user_image);
            }

            if (!empty($password) && $password !== $confirmPassword) {
                $errorMessage = "Passwords do not match. Please try again.";
            } else {
                if (!empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                    $updatePasswordQuery = "UPDATE Users SET password = '$passwordHash' WHERE user_id = $userId";
                    if ($conn->query($updatePasswordQuery)) {
                        $updateMessage = "Password updated successfully!";
                    } else {
                        $errorMessage = "Error updating password: " . $conn->error;
                    }
                }

                $updateQuery = "UPDATE Users SET name = '$name', email = '$email', phone = '$phone', username = '$username', user_image = '$user_image' WHERE user_id = $userId";
                if ($conn->query($updateQuery)) {
                    if (empty($updateMessage) && empty($success)) {
                        $updateMessage = "Profile updated successfully!";
                    }
                    header("Location: profile.php");
                    exit();
                } else {
                    $errorMessage = "Error updating profile: " . $conn->error;
                }
            }
            break;

        case isset($_POST['cancel']):
            header("Location: profile.php");
            break;

        default:
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <title>Profile</title>
</head>
<body>

<?php include('../../includes/sidebar.php');  ?>

<input type="checkbox" class="edit-checkbox" id="editToggle">

<div class="container">
    
    <div class="card">
        <div class="card-body">
            <?php if ($updateMessage): ?>
                <div class="alert alert-success"><?= htmlspecialchars($updateMessage); ?></div>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="profile-set">
                <div class="profile-head"></div>
                <div class="profile-top">
                    <div class="profile-content">
                        <div class="profile-contentimg">
                            <?php if (isset($userInfo['user_image']) && !empty($userInfo['user_image'])): ?>
                                <img src="<?= htmlspecialchars($userInfo['user_image']) ?>" alt="User Image" id="blah" />
                            <?php else: ?>
                                <div class="user-avatar-default"><?= strtoupper(substr($userInfo['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="profile-contentname">
                            <h2><?= htmlspecialchars($userInfo['name']) ?></h2>
                            <h4><?= htmlspecialchars($userInfo['role']); ?></h4>
                        </div>
                    </div>
                    <label for="editToggle" class="edit-mode-btn">Edit Profile</label>
                </div>
            </div>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Full Name</label>
                            <span class="form-control read-only"><?= htmlspecialchars($userInfo['name']); ?></span>
                            <input type="text" name="name" value="<?= htmlspecialchars($userInfo['name']); ?>" class="form-control editable">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Email</label>
                            <span class="form-control read-only"><?= htmlspecialchars($userInfo['email']); ?></span>
                            <input type="email" name="email" value="<?= htmlspecialchars($userInfo['email']); ?>" class="form-control editable">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Phone</label>
                            <span class="form-control read-only"><?= htmlspecialchars($userInfo['phone']); ?></span>
                            <input type="text" name="phone" value="<?= htmlspecialchars($userInfo['phone']); ?>" class="form-control editable">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Username</label>
                            <span class="form-control read-only"><?= htmlspecialchars($userInfo['username']); ?></span>
                            <input type="text" name="username" value="<?= htmlspecialchars($userInfo['username']); ?>" class="form-control editable">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12 password">
                        <div class="input-blocks">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control editable" placeholder="New Password">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12 password-confirmation">
                        <div class="input-blocks">
                            <label class="form-label">Re-enter New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter Password">
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12 profile-image" >
                        <div class="input-blocks">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="user_image" class="form-control editable">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-submit me-2 editable" name="edit">Save Changes</button>
                    <button class="btn btn-cancel editable" name="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
