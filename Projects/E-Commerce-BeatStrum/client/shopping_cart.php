<?php
ob_start();
session_start();
include('config.php');

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">

    <title>Shopping Cart</title> 
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\cart.css'); ?>
    </style>
</head>
<body>    
<?php include ('1.header.php'); ?>
    
    <div class="tab-navigation">
        <div class="tab-btn"><button onclick="showTab('cartTab')" id="cartBtn" class="active-tab-btn">Cart</button></div>
        <div class="tab-btn"><button onclick="showTab('toShipTab')" id="toShipBtn">To Ship</button></div>
        <div class="tab-btn"><button onclick="showTab('toReceiveTab')" id="toReceiveBtn">To Receive</button></div>
        <div class="tab-btn"><button onclick="showTab('successfulTab')" id="successfulBtn">Received Items</button></div>
    </div>

    <div id="cartTab" class="tab-content" style="display: none;">
        <h3>Your Cart Items</h3>
        <?php include 'tabCart.php'; ?>
    </div>

    <div id="toShipTab" class="tab-content" style="display: none;">
    <h3>Items to Ship</h3>
        <?php include 'tabShip.php'; ?>
    </div>

    <div id="toReceiveTab" class="tab-content" style="display: none;">
    <h3>Items to Receive</h3>
        <?php include 'tabReceive.php'; ?>
    </div>

    <div id="successfulTab" class="tab-content" style="display: none;">
    <h3>Items Received Successfully</h3>
        <?php include 'tabSuccessful.php'; ?>
    </div>

    <script src="/PHP-Projects/E-Commerce-BeatStrum/script/shopping_cartTab.js"></script>

</body>
</html>
