<?php 
session_start();
include('../../includes/config.php');

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../../index.php");
    exit();
}

$query = "SELECT * FROM ShopInfo";
$result = mysqli_query($conn, $query);
$shop_info = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_email = mysqli_real_escape_string($conn, $_POST['shop_email']);
    $updated_contact = mysqli_real_escape_string($conn, $_POST['shop_contact']);

    $update_query = "UPDATE ShopInfo SET shop_email = '$updated_email', shop_contact = '$updated_contact' WHERE shop_name = 'Urban Grail'";
    mysqli_query($conn, $update_query);

    $_SESSION['success'] = "Shop information updated successfully.";
}

if (isset($_GET['edit'])) {
    $_SESSION['edit'] = true;
} elseif (isset($_GET['save'])) {
    $_SESSION['edit'] = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/promo.css">
    <title>Settings</title>
    <style>
        .shop-info-container {
            background-color: #ffffff;
            padding: 30px;
            margin: 20px;
            border-radius: 8px;
            border: 1px solid rgb(201, 201, 201);
            /* box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1); */
            max-width: 1200px;
            margin: 20px auto;
            position: relative;
        }

        .shop-info-container h2 {
            color: #333333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .input-blocks {
            margin-bottom: 20px;
        }

        .shop-info-container h2 a{
            color: #333333;
            text-decoration: none;
            font-weight: bold;
        }
        .form-label {
            font-size: 14px;
            color: #333333;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            color: #333;
            transition: border 0.3s ease;
        }

        .form-control.read-only {
            background-color: #f0f0f0;
            border: none;
            pointer-events: none;
        }

        .form-control:focus {
            border: 1px solid #000;
            background-color: #fff;
        }

        .edit-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #555;
        }

        .save-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .save-button:hover {
            background-color: #45a049;
        }

        .cancel-button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cancel-button:hover {
            background-color: #d32f2f;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .manage-promo-button {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 12px 24px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .manage-promo-button:hover {
            background-color: #555;
        }


        @media (max-width: 768px) {
            .shop-info-container {
                padding: 20px;
                margin: 10px;
            }

            .shop-info-container h2 {
                font-size: 1.5rem;
            }

            .form-control {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include('../../includes/sidebar.php'); ?>

    <div class="shop-info-container">

        <h2>Shop Information</h2>
        <?php if ($shop_info): ?>
            <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($shop_info['shop_name']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($shop_info['shop_address']); ?></p>

            <?php if (isset($_SESSION['edit']) && $_SESSION['edit']): ?>
                <form action="" method="POST">
                    <div class="input-blocks">
                        <label class="form-label">Email</label>
                        <input type="email" name="shop_email" value="<?= htmlspecialchars($shop_info['shop_email']); ?>" class="form-control">
                    </div>
                    <div class="input-blocks">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="shop_contact" value="<?= htmlspecialchars($shop_info['shop_contact']); ?>" class="form-control">
                    </div>
                    <button type="submit" class="save-button">Save</button>
                    <a href="?save=true" class="cancel-button">Cancel</a>
                </form>
            <?php else: ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($shop_info['shop_email']); ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($shop_info['shop_contact']); ?></p>
                <a href="?edit=true" class="edit-button">Edit</a>
            <?php endif; ?>
        <?php else: ?>
            <p>No shop information available.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="shop-info-container">
        <h2>Manage Promotion</h2>
        <a href="promo.php" class="manage-promo-button">Go to Promotions</a>
    </div>



</body>
</html>
