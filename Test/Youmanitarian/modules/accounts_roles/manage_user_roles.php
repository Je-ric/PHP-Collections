<?php
session_start();
include('../../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];


// Sorting
$order_by = isset($_GET['sort']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['sort']) : 'name';
$order_dir = isset($_GET['order']) ? (in_array($_GET['order'], ['asc', 'desc']) ? $_GET['order'] : 'asc') : 'asc';

// Pagination
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Users with roles
$query = "SELECT users.id, users.name, 
                 COALESCE(GROUP_CONCAT(DISTINCT roles.role_name ORDER BY roles.role_name SEPARATOR ', '), 'Member') AS roles
          FROM users 
          LEFT JOIN user_roles ON users.id = user_roles.user_id
          LEFT JOIN roles ON user_roles.role_id = roles.id
          GROUP BY users.id
          ORDER BY $order_by $order_dir
          LIMIT $limit OFFSET $offset";
$users = $conn->query($query);

// Get all roles
$roles = $conn->query("SELECT * FROM roles");
$all_roles = [];
while ($role = $roles->fetch_assoc()) {
    $all_roles[$role['id']] = $role['role_name'];
}

// Pagination 
$total_pages = ceil($total_users / $limit);
$pagination_start = max(1, min($page - 2, $total_pages - 4));
$pagination_end = min($pagination_start + 4, $total_pages);

// Handle role assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_role'])) {
    $user_id = $_POST['user_id'];
    $role_ids = $_POST['roles'] ?? [];

    // Fetch 'Member' role_id
    $stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = 'Member'");
    $stmt->execute();
    $member_role = $stmt->get_result()->fetch_assoc();
    $member_role_id = $member_role['id'];

    // Always ensure "Member" role is assigned
    if (!in_array($member_role_id, $role_ids)) {
        $role_ids[] = $member_role_id;
    }

    // Remove all existing roles before assigning new ones
    $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Assign new roles
    foreach ($role_ids as $role_id) {
        $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $role_id);
        $stmt->execute();
    }

    $_SESSION['message'] = "Roles assigned successfully!";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle role removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_role'])) {
    $user_id = $_POST['user_id'];
    $role_name = $_POST['role_id']; // This is role name, not role id
    
    // Fetch role id based on role name
    $stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
    $stmt->bind_param("s", $role_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $role = $result->fetch_assoc();
    
    if ($role) {
        $role_id = $role['id'];
        
        // Now delete the role using the correct role_id
        $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = ? AND role_id = ?");
        $stmt->bind_param("ii", $user_id, $role_id);
        $stmt->execute();
        
        $_SESSION['message'] = "Role removed successfully!";
    } else {
        $_SESSION['message'] = "Role not found!";
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Roles</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/manage_roles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>

    <?php include('../../includes/sidebar.php'); ?>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Manage User Roles</h2>

        <input type="text" id="searchUser" placeholder="Search by name..." class="my-4 p-2 border border-gray-300 rounded-md">

        <div id="roleFilterContainer" class="mb-4">
            <strong>Filter by Roles:</strong>
            <?php foreach ($all_roles as $role_id => $role_name): ?>
                <button class="filter-role-btn px-4 py-2 bg-gray-200 rounded-full m-1" data-role="<?= htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8') ?>
                </button>
            <?php endforeach; ?>
        </div>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">User Name</th>
                    <th class="px-4 py-2 text-left">Assigned Roles</th>
                    <th class="px-4 py-2 text-left">Assign New Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()) { ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-4 py-2">
                            <?php
                            $user_id = $user['id'];
                            $assigned_roles = explode(",", $user['roles']);
                            $has_admin_role = false;
                            
                            foreach ($assigned_roles as $role_name) {
                                if ($role_name === 'Admin') {
                                    $has_admin_role = true;
                                }
                                
                                // Don't display 'x' for the 'Member' role
                                if ($role_name !== 'Member') {
                                    echo "<span class='inline-block px-2 py-1 bg-gray-200 rounded-full text-xs text-gray-700'>
                                            " . htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8') . 
                                            "<form method='POST' style='display:inline;'>
                                                <input type='hidden' name='user_id' value='$user_id'>
                                                <input type='hidden' name='role_id' value='$role_name'>
                                                <button type='submit' name='remove_role' class='text-red-600 hover:text-red-800 ml-2'>&times;</button>
                                            </form>
                                          </span>";
                                } else {
                                    echo "<span class='inline-block px-2 py-1 bg-gray-200 rounded-full text-xs text-gray-700'>" . htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8') . "</span>";
                                }
                            }
                            ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php if (!$has_admin_role): ?>
                                <button onclick="openAssignModal(<?= $user['id'] ?>)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Assign Role
                                </button>
                            <?php else: ?>
                                <span class="text-gray-500">Admin - Full access</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>" class="px-4 py-2 bg-gray-300 rounded-full">Previous</a>
            <?php endif; ?>

            <?php for ($i = $pagination_start; $i <= $pagination_end; $i++): ?>
                <a href="?page=<?= $i ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>" class="px-4 py-2 <?= ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200' ?> rounded-full">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&sort=<?= $order_by ?>&order=<?= $order_dir ?>" class="px-4 py-2 bg-gray-300 rounded-full">Next</a>
            <?php endif; ?>
        </div>

    </div>

    <!-- Assign Role Modal -->
    <div id="assignRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-2/3 max-w-3xl p-8">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800">Assign Role</h3>

            <form method="POST">
                <input type="hidden" name="user_id" id="modal_user_id">
                <div class="mb-6">
                    <label class="block text-lg font-medium text-gray-700">Select Roles</label>
                    <div id="roleOptions" class="grid grid-cols-2 gap-4 mt-2">
                        <?php foreach ($all_roles as $role_id => $role_name): ?>
                            <?php if ($role_name !== 'Member'): ?>
                                <label class="flex items-center space-x-2 bg-gray-100 p-3 rounded-lg hover:bg-gray-200 transition cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="<?= $role_id ?>" class="form-checkbox text-blue-600">
                                    <span class="text-gray-700"><?= htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeAssignModal()" class="px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                        Cancel
                    </button>
                    <button type="submit" name="assign_role" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(userId) {
            document.getElementById('modal_user_id').value = userId;
            document.getElementById('assignRoleModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignRoleModal').classList.add('hidden');
        }
    </script>

</body>
</html>
