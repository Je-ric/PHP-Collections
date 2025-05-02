<?php
session_start();
include('../../includes/config.php');

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../../index.php");
    exit();
}

include('promo_modal.php');


$current_date = date('Y-m-d');
$discounts_query = "SELECT d.id, d.name, GROUP_CONCAT(dp.percentage ORDER BY dp.percentage ASC) AS percentages, 
                    d.start_date, d.end_date, d.promo_status, d.promo_type
                    FROM Discounts d
                    LEFT JOIN DiscountPercentages dp ON d.id = dp.discount_id
                    GROUP BY d.id
                    ORDER BY d.start_date DESC";
$discounts_result = $conn->query($discounts_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/promo.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <title>Manage Discounts</title>
</head>
<body>
    <?php include('../../includes/sidebar.php'); ?>

    <div class="container">
    
    <div class="page-header">
        <h5>Existing Offers</h5>
        <div class="button-addDownload" style="display:flex;">
            <div class="bx-con" >
                <button class="bx-bt" id="addDiscountBtn" onclick="openAddModal()">
                <i class="bx bx-plus"></i></button>  
            </div>
        </div>

    </div>


        <div class="discount-list">
            <?php if ($discounts_result->num_rows > 0): ?>
                <table class="discounts-table">
                    <thead>
                        <tr>
                            <th>Discount Name</th>
                            <th>Percentages</th>
                            <th>Start-End Date</th>
                            <th>Promo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($discount = $discounts_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($discount['name']); ?></td>
                                <td><?php echo htmlspecialchars($discount['percentages']); ?></td>
                                <td><?php echo htmlspecialchars($discount['start_date']) . ' - ' . htmlspecialchars($discount['end_date']); ?></td>

                                <td><?php echo htmlspecialchars($discount['promo_type']); ?></td>
                                <td class="<?php echo ($discount['promo_status'] == 'Available') ? 'status-available' : (($discount['promo_status'] == 'Expired') ? 'status-expired' : 'status-soon'); ?>">
                                    <?php echo $discount['promo_status']; ?>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" onclick="openUpdateModal(<?php echo $discount['id']; ?>, '<?php echo htmlspecialchars($discount['name']); ?>', '<?php echo htmlspecialchars($discount['percentages']); ?>', '<?php echo $discount['start_date']; ?>', '<?php echo $discount['end_date']; ?>')">Edit</a> | 
                                    <a href="javascript:void(0)" onclick="openDeleteModal(<?php echo $discount['id']; ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No discounts available.</p>
            <?php endif; ?>
        </div>


        <?php include('promo_modal.php');?>


    </div>


</body>
</html>
