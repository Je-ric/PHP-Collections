<?php
include('../../includes/config.php'); 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['log_emp']) && !isset($_SESSION['admin_log'])) {
    header("Location: index.php");
    exit();
}

// -------------------------------------------------------------------------------
// Reset all filters
if (isset($_POST['reset_all'])) {
    unset($_POST['user_filter']);
    unset($_POST['date_filter']);
    unset($_POST['action_filter']);
    unset($_POST['search_filter']);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management History</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/history.css">
    <link rel="stylesheet" href="../../assets/css/modals.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <link rel="stylesheet" href="../../assets/css/tab.css">
    <link rel="stylesheet" href="../../assets/css/indicators.css">
</head>
<body>

<?php include('../../includes/sidebar.php'); ?>

<div class="container"> 
<div class="tabs">
    <div class="tab" data-tab="receipt" onclick="showTab('receipt')"><i class='bx bxs-receipt'></i> <span>Receipts</span> </div>
    <?php if (!isset($_SESSION['log_emp'])){ ?>
        <div class="tab" data-tab="products" onclick="showTab('products')"><i class='bx bxs-shopping-bag'></i> <span>Products</span> </div>
        <div class="tab" data-tab="attendance" onclick="showTab('attendance')"><i class='bx bxs-user'></i> <span>Employee</span></div>
        <div class="tab" data-tab="deleted-items" onclick="showTab('deleted-items')"><i class='bx bxs-trash'></i> <span>Trashed</span></div>
    <?php } ?>
</div>




    <!-- Receipt Tab -->
    <div id="receipt" class="tab-content">
        <?php include 'tab_receipt.php'; ?>
    </div>

    <!-- Product Tab -->
    <div id="products" class="tab-content">
        <?php include 'tab_products.php'; ?>
    </div>

    <!-- Employee Tab -->
    <div id="attendance" class="tab-content">
        <?php include 'tab_attendance.php'; ?>
    </div>

    <!-- Deleted Items Tab -->
    <div id="deleted-items" class="tab-content">
    <?php include 'tab_deleted.php'; ?>
    </div>
    
</div>

<script>
function showTab(tab, event = null) {
    localStorage.setItem('activeTab', tab);

    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach((tabLink) => tabLink.classList.remove('active'));
    tabContents.forEach((tabContent) => tabContent.classList.remove('active'));

    const activeTab = document.querySelector(`.tab[data-tab='${tab}']`);
    if (activeTab) {
        activeTab.classList.add('active');  
    }
    const activeTabContent = document.getElementById(tab);
    if (activeTabContent) {
        activeTabContent.classList.add('active'); 
    }

    document.body.classList.remove('active-receipt', 'active-products', 'active-attendance', 'active-deleted-items');
    if (tab === 'receipt') {
        document.body.classList.add('active-receipt');
    } else if (tab === 'products') {
        document.body.classList.add('active-products');
    } else if (tab === 'attendance') {
        document.body.classList.add('active-attendance');
    } else if (tab === 'deleted-items') {
        document.body.classList.add('active-deleted-items');
    }
}

window.onload = function() {
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        const activeTabLink = document.querySelector(`.tab[data-tab="${activeTab}"]`);
        if (activeTabLink) {
            activeTabLink.classList.add('active');
        }

        document.body.classList.add(`active-${activeTab}`);
        
        const activeTabContent = document.getElementById(activeTab);
        if (activeTabContent) {
            activeTabContent.classList.add('active');
        }
    } else {
        showTab('receipt');
    }
};
</script>

</body>
</html>
