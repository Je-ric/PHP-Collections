<?php
session_start();
include('../../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';

// $allowed_roles = ['Admin']; 

// if (!in_array($user_role, $allowed_roles)) {
//     header('Location: ../../unauthorized.php'); 
//     exit();
// }

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Sorting
$order_by = $_GET['sort'] ?? 'name';
$order_dir = $_GET['order'] ?? 'asc';
$valid_columns = ['name'];
if (!in_array($order_by, $valid_columns)) $order_by = 'name';
$order_dir = ($order_dir === 'desc') ? 'desc' : 'asc';

// Pagination
$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

// Get total filtered users count
$total_filtered_users = $conn->query("SELECT COUNT(DISTINCT users.id) AS total
                                            FROM users
                                            LEFT JOIN user_roles ON users.id = user_roles.user_id
                                            LEFT JOIN roles ON user_roles.role_id = roles.id")->fetch_assoc()['total'];

// Get all roles
$roles = $conn->query("SELECT * FROM roles");
$all_roles = [];
while ($role = $roles->fetch_assoc()) {
    $all_roles[$role['id']] = $role['role_name'];
}

// Pagination 

$total_pages = ceil($total_filtered_users / $limit);
$pagination_start = max(1, min($page - 2, $total_pages - 4));
$pagination_end = min($pagination_start + 4, $total_pages);

// Fetch user counts
$pending_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE status='pending'")->fetch_assoc()['count'];
$approved_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE status='approved'")->fetch_assoc()['count'];
$declined_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE status='rejected'")->fetch_assoc()['count'];

// Fetch users based on tab
$status = $_GET['status'] ?? 'pending';
$page_param = 'page_' . $status;
$page = $_GET[$page_param] ?? 1;

$sql = "SELECT id, name, email 
        FROM users 
        WHERE status='$status'  
        ORDER BY $order_by $order_dir 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);


// Approve / Decline / Restore = Individual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    // Approve action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
    $user_id = $_POST['user_id'];
    $conn->query("UPDATE users SET status='approved' WHERE id=$user_id");

    // Fetch the 'Member' role ID
    $member_role = $conn->query("SELECT id FROM roles WHERE role_name = 'Member'")->fetch_assoc();
    
    // Check if role exists and assign it
    if ($member_role) {
        $role_id = $member_role['id'];
        // Insert into user_roles table
        $conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ($user_id, $role_id)");
        echo "User approved and role assigned successfully.";
    } else {
        echo "Error: Member role not found.";
    }

    // Redirect after approval
    echo "<script>window.location='account_approval.php?status=pending';</script>";



        echo "<script>window.location='account_approval.php?status=pending';</script>";
    } elseif (isset($_POST['decline'])) {
        $user_id = $_POST['user_id'];
        $conn->query("UPDATE users SET status='rejected' WHERE id=$user_id");

        $conn->query("DELETE FROM user_roles WHERE user_id = $user_id");

        echo "<script>alert('User declined and role removed.'); window.location='account_approval.php?status=pending';</script>";
    } elseif (isset($_POST['restore'])) {
        $user_id = $_POST['user_id'];
        $conn->query("UPDATE users SET status='pending' WHERE id=$user_id");

        $conn->query("DELETE FROM user_roles WHERE user_id = $user_id");

        echo "<script>alert('User restored to pending and role removed.'); window.location='account_approval.php?status=rejected';</script>";
    }
}

// Approve / Decline / Restore = Multiple
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && isset($_POST['selected_users'])) {
    $selected_users = implode(",", $_POST['selected_users']);
    if ($_POST['bulk_action'] == 'approve') {
        $selected_users = implode(",", $_POST['selected_users']);

        $conn->query("UPDATE users SET status='approved' WHERE id IN ($selected_users)");

        // Get the 'member' role ID
        $member_role = $conn->query("SELECT id FROM roles WHERE role_name = 'Member'")->fetch_assoc()['id'];

        // Debugging: Check if the role is fetched correctly
        if ($member_role) {
            echo "Member role ID: $member_role<br>";
        } else {
            echo "Error: Member role not found.<br>";
        }

        if ($member_role) { 
            $conn->query("INSERT INTO user_roles (user_id, role_id) 
                        SELECT id, $member_role FROM users WHERE id IN ($selected_users)");
            echo "<script>alert('Selected users approved and roles assigned.'); window.location='account_approval.php?status=pending';</script>";
        } else {
            echo "Failed to assign the 'member' role.";
        }
    } elseif ($_POST['bulk_action'] == 'decline') {
        $selected_users = implode(",", $_POST['selected_users']);

        $conn->query("UPDATE users SET status='rejected' WHERE id IN ($selected_users)");

        $conn->query("DELETE FROM user_roles WHERE user_id IN ($selected_users)");

        echo "<script>alert('Selected users declined and roles removed.'); window.location='account_approval.php?status=pending';</script>";
    } elseif ($_POST['bulk_action'] == 'restore') {
        $selected_users = implode(",", $_POST['selected_users']);

        $conn->query("UPDATE users SET status='pending' WHERE id IN ($selected_users)");

        $conn->query("DELETE FROM user_roles WHERE user_id IN ($selected_users)");

        echo "<script>alert('Selected users restored to pending and roles removed.'); window.location='account_approval.php?status=rejected';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Approvals</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.3/dist/full.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php include('../../includes/sidebar.php'); ?>

    <div class="container mx-auto p-6">

        <h2 class="text-3xl font-semibold mb-6">User Approvals</h2>

        <div class="tabs mb-4">
            <a href="account_approval.php?status=pending" class="tab tab-lifted <?= $status == 'pending' ? 'tab-active' : '' ?>">Pending (<?= $pending_count ?>)</a>
            <a href="account_approval.php?status=approved" class="tab tab-lifted <?= $status == 'approved' ? 'tab-active' : '' ?>">Approved (<?= $approved_count ?>)</a>
            <a href="account_approval.php?status=rejected" class="tab tab-lifted <?= $status == 'rejected' ? 'tab-active' : '' ?>">Declined (<?= $declined_count ?>)</a>
        </div>

        <input type="text" id="searchUser" placeholder="Search by name..." class="input input-bordered w-full mb-6">

        <form method="post">
        <div class="flex items-center space-x-4 mb-6">
            <input type="checkbox" id="select_all" onclick="toggleSelectAll(this)" class="checkbox checkbox-primary" />
            
            <?php if ($status == 'pending'): ?>
                <button type="submit" name="bulk_action" value="approve" class="btn btn-success">Approve Selected</button>
                <button type="submit" name="bulk_action" value="decline" class="btn btn-error">Decline Selected</button>
            <?php elseif ($status == 'approved'): ?>
                <button type="submit" name="bulk_action" value="decline" class="btn btn-error">Decline Selected</button>
            <?php elseif ($status == 'rejected'): ?>
                <button type="submit" name="bulk_action" value="restore" class="btn btn-warning">Restore Selected</button>
            <?php endif; ?>
        </div>

            <label class="block mb-2">Show
                <select id="entriesPerPage" class="select select-bordered">
                    <option value="10" <?= ($limit == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="15" <?= ($limit == 15) ? 'selected' : ''; ?>>15</option>
                    <option value="20" <?= ($limit == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="25" <?= ($limit == 25) ? 'selected' : ''; ?>>25</option>
                </select>
                entries
            </label>

            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="toggleSelectAll(this)" class="checkbox checkbox-primary" /></th>
                        <th><a href="?sort=name&order=<?= ($order_dir === 'asc') ? 'desc' : 'asc'; ?>" class="text-xl">Name
                                <?= ($order_by === 'name') ? ($order_dir === 'asc' ? '^' : '⌄') : ''; ?>
                            </a></th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_users[]" value="<?= $row['id'] ?>" class="user_checkbox checkbox checkbox-primary"></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                    <?php if ($status == 'pending'): ?>
                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                        <button type="submit" name="decline" class="btn btn-error">Decline</button>
                                    <?php elseif ($status == 'approved'): ?>
                                        <button type="submit" name="decline" class="btn btn-error">Decline</button>
                                    <?php elseif ($status == 'rejected'): ?>
                                        <button type="submit" name="restore" class="btn btn-warning">Restore</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <p class="mt-4">Showing <?= min($offset + 1, $total_users) ?> to <?= min($offset + $limit, $total_users) ?> of <?= $total_users ?> entries</p>

            <div class="pagination flex justify-center space-x-2 mt-4">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>" class="btn btn-outline">Previous</a>
                <?php endif; ?>

                <?php for ($i = $pagination_start; $i <= $pagination_end; $i++): ?>
                    <a href="?page=<?= $i ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>"
                        class="btn btn-outline <?= ($i == $page) ? 'btn-active' : ''; ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>" class="btn btn-outline">Next</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
        function toggleSelectAll(source) {
            var checkboxes = document.querySelectorAll('.user_checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = source.checked;
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search User
            $("#searchUser").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("table tr").each(function() {
                    let userName = $(this).find("td:nth-child(2)").text().toLowerCase();
                    if (userName.includes(value) || $(this).find("th").length) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Change Entries Per Page
            $("#entriesPerPage").change(function() {
                let limit = $(this).val();
                let url = new URL(window.location.href);
                url.searchParams.set('limit', limit);
                url.searchParams.set('page', 1); // Reset to first page
                window.location.href = url.toString();
            });
        });
    </script>

</body>

</html>
