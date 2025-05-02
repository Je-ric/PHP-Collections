<?php
include('../../includes/config.php'); 
session_start();

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['user_role'];
    $status = $_POST['status'];

    $checkUserSql = "SELECT COUNT(*) FROM Users WHERE username = ?";
    $checkUserStmt = $conn->prepare($checkUserSql);
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->bind_result($userCount);
    $checkUserStmt->fetch();
    $checkUserStmt->close();

    if ($userCount > 0) {
        $error = "The username '$username' is already taken. Please choose another.";
    } else {
        $userImage = null;
        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../upload_images/user_images/';
            $uploadFile = $uploadDir . basename($_FILES['user_image']['name']);

            if (move_uploaded_file($_FILES['user_image']['tmp_name'], $uploadFile)) {
                $userImage = $uploadFile;
            } else {
                $error = "Error uploading image.";
            }
        }

        $sql = "INSERT INTO Users (name, username, password, email, phone, role, status, user_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $username, $password, $email, $phone, $role, $status, $userImage);

        if ($stmt->execute()) {
            $success = "User created successfully.";
        } else {
            $error = "Error creating user: " . $conn->error;
        }
        $stmt->close();
    }
}

$sql_users = "SELECT * FROM Users";
$result_users = $conn->query($sql_users);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';  

    $update_sql = "UPDATE Users SET status=? WHERE user_id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $newStatus, $userId);
    $update_stmt->execute();
    $update_stmt->close();

    echo json_encode(['success' => true]);
    exit();
}



function fetchUserInfo($userId) {
    global $conn;
    $user_sql = "SELECT * FROM Users WHERE user_id=?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $userId);
    $user_stmt->execute();
    return $user_stmt->get_result()->fetch_assoc();
}


$userDetails = null;
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $userDetails = fetchUserInfo($userId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_role']) && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];
    
    error_log("Received user_id: $userId, new_role: $newRole");

    $updateRoleSql = "UPDATE Users SET role=? WHERE user_id=?";
    $stmt = $conn->prepare($updateRoleSql);
    $stmt->bind_param("si", $newRole, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error updating user role: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
}



$roleFilter = isset($_POST['role_filter']) ? $_POST['role_filter'] : '';
$statusFilter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';
$searchQuery = isset($_POST['userSearch']) ? $_POST['userSearch'] : '';

$whereSql = '';
$params = [];

if ($roleFilter) {
    $whereSql .= "role = ?";
    $params[] = $roleFilter;
}
if ($statusFilter) {
    $whereSql .= ($whereSql ? " AND " : "") . "status = ?";
    $params[] = $statusFilter;
}
if ($searchQuery) {
    $whereSql .= ($whereSql ? " AND " : "") . "name LIKE ?";
    $params[] = "%$searchQuery%";
}

if ($whereSql) {
    $sql_users .= " WHERE $whereSql";
}

$stmt = $conn->prepare($sql_users);
$paramTypes = str_repeat('s', count($params));
if ($paramTypes) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result_users = $stmt->get_result();



if (isset($_POST['reset_all'])) {
    unset($_POST['role_filter']);
    unset($_POST['status_filter']);
    unset($_POST['userSearch']);
    unset($_POST['date_filter']); 
    unset($_POST['action_filter']); 
    unset($_POST['search_filter']);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_username'])) {
    $username = $_POST['username'];

    $sql = "SELECT COUNT(*) AS count FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['exists' => $count > 0]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <title>Manage Accounts</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/modal.css">
    <link rel="stylesheet" href="../../assets/css/accounts.css">
    <link rel="stylesheet" href="../../assets/css/search_filter.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <link rel="stylesheet" href="../../assets/css/indicators.css">
    <link rel="stylesheet" href="../../assets/css/table.css">
</head>
<body>



<?php 
    include('../../includes/sidebar.php'); 
    $usersCount = $conn->query("SELECT COUNT(*) FROM Users WHERE role = 'employee'")->fetch_row()[0]; 
?>

<div class="container">

    <div class="page-header">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="accountFilterForm">
                <div style="display:flex;">
                    <input class="userSearch" type="text" name="userSearch" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by name...">
                    <button class="search" type="submit"><i class='bx bx-search-alt'></i></button>
                    </div>

                    <div style="display:flex;">
                <select name="role_filter" id="role_filter" onchange="this.form.submit()">
                        <option value="">Select Roles to filter</option>
                        <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="employee" <?= $roleFilter === 'employee' ? 'selected' : '' ?>>Employee</option>
                    </select>
                    <select name="status_filter" id="status_filter" onchange="this.form.submit()">
                        <option value="">Select Status to filter</option>
                        <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </form>

        <div class="button-addDownload" style="display:flex;">
            <div class="bx-con" >
                <button class="bx-bt" onclick="openModal()">
                    <i class="bx bx-plus"></i>
                </button>  
            </div>
            
            <div class="filter-note">
                <a href="download_user_details.php?action=all_users" class="download-button" onclick="return confirmDownloadAll()"> 
                    <span class="download-text"> Download </span>
                    <span class="download-icon">
                        <i class="bx bx-download"></i> 
                    </span>
                </a>
            </div>
        </div>

    </div>

    <div class="account-container">
        <div class="card-body">
        <p><i class='bx bx-group'></i> Total Employees <span class="users-count"><?= ($usersCount) ?></span></p>
            <table>
                <thead>
                    <tr><th></th>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Contact Info</th>
                        <th>Role</th>
                    </tr>
                </thead> 
                <tbody id="userTableBody">
                        <?php 
                        $index = 1;
                        while ($row = $result_users->fetch_assoc()): ?>
                        
                        <tr class="attendance-row" data-role="<?= htmlspecialchars($row['role']) ?>" data-status="<?= htmlspecialchars($row['status']) ?>">
                        <td></td>
                        <td>
                            <!-- <a href="download_user_details.php?action=attendance_history&user_id=<?= $row['user_id'] ?>" onclick="return confirmDownloadUser()"><i class='bx bx-download'></i></a> -->
                            <?= $index++ ?>
                        </td>
                        <td class="user-details"> 
                                    <div class="user-image">
                                        <?php if (!empty($row['user_image'])): ?>
                                            <img src="<?= htmlspecialchars($row['user_image']) ?>" alt="User Image" class="user-img" />
                                        <?php else: ?>
                                            <?= strtoupper(substr($row['name'], 0, 1)) ?> 
                                        <?php endif; ?>
                                    </div>
                                    <div class="user-info">
                                        <span><?= htmlspecialchars($row['name']) ?></span><br>
                                        <small><?= htmlspecialchars($row['role']) ?></small>
                                    </div>
                        </td>
                        <td>
                            <div class="status">
                                <form action="accounts.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    <input type="hidden" name="status" value="inactive">
                                    <label for="status_checkbox_<?= $row['user_id'] ?>" class="status-label">
                                        <input type="checkbox" class="input" id="status_checkbox_<?= $row['user_id'] ?>" 
                                        data-user-id="<?= $row['user_id'] ?>" name="status" value="active" 
                                            <?= $row['status'] === 'active' ? 'checked' : '' ?> 
                                            onchange="changeUserStatus(<?= $row['user_id'] ?>)">
                                        <span class="status-badge <?= $row['status'] === 'active' ? 'active-status' : 'inactive-status' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </label>
                                </form>
                            </div>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['email']) ?><br>
                            <?= htmlspecialchars($row['phone']) ?>
                        </td>
                        <td>
                            <form id="changeRoleForm_<?= $row['user_id'] ?>" onsubmit="event.preventDefault(); changeUserRole(<?= $row['user_id'] ?>)">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <select name="new_role" class="role-select" onchange="changeUserRole(<?= $row['user_id'] ?>)">
                                    <option value="">Select Roles</option>
                                    <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="employee" <?= $row['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
                                </select>
                            </form>
                        </td>
                            </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add User</h3>
        <form action="accounts.php" method="POST" enctype="multipart/form-data"> 
            <input type="hidden" name="role" id="roleInput">
            <div class="form-row">
                <div>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" pattern="[A-Za-z ]+" title="Please enter only letters" required>
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required>
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" pattern="[0-9]{11}" title="Please enter 11-digit numbers only" required>
                </div>
            </div>
            <div class="form-row">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" pattern="^[A-Za-z0-9_]{5,15}$" title="Username must be 5-15 characters long and can only contain letters, numbers, and underscores." required>
                <small id="usernameFeedback"></small>
            </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long, include one uppercase letter, one lowercase letter, and one number." required>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="user_role">User Role</label>
                    <select id="user_role" name="user_role" required>
                        <option value="admin">Admin</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
                <div>
                    <label for="user_image">User Image</label>
                    <input type="file" id="user_image" name="user_image" accept="image/*" required> 
                </div>
            </div>
            <button class="button" type="submit" id= "addUser" name="add_user">Create User</button>
        </form>
    </div>

</div>




<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="accounts.php"]');
    const submitButton = document.querySelector('button[name="add_user"]');
    const usernameField = document.getElementById('username');
    const usernameFeedback = document.getElementById('usernameFeedback');
    
    function validateForm() {
        let isValid = true;
        
        form.querySelectorAll('input, select').forEach(input => {
            if (input.required && !input.checkValidity()) {
                isValid = false;
            }
        });

        if (usernameFeedback.textContent === 'Username is already taken.') {
            isValid = false;
        }
        submitButton.disabled = !isValid;
    }

    form.addEventListener('input', validateForm);
    form.addEventListener('change', validateForm);

    usernameField.addEventListener('blur', function () {
        const username = this.value;
        if (username.trim() === '') return;
        const formData = new FormData();
        formData.append('check_username', true);
        formData.append('username', username);

        fetch('accounts.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                usernameFeedback.textContent = 'Username is already taken.';
                usernameFeedback.style.color = 'red';
            } else {
                usernameFeedback.textContent = 'Username is available.';
                usernameFeedback.style.color = 'green';
            }
            validateForm(); 
        })
        .catch(error => console.error('Error:', error));
    });

    
    validateForm();
});




    function changeUserStatus(userId) {
    const statusCheckbox = document.getElementById('status_checkbox_' + userId);
    const newStatus = statusCheckbox.checked ? 'active' : 'inactive';

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('status', newStatus);

    fetch('accounts.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusBadge = document.querySelector(`#status_checkbox_${userId} + .status-badge`);
            statusBadge.classList.toggle('active-status', newStatus === 'active');
            statusBadge.classList.toggle('inactive-status', newStatus === 'inactive');
            statusBadge.textContent = capitalizeFirstLetter(newStatus);
        } else {
            alert(data.error || "Error updating status.");
        }
    })
    .catch(error => console.error('Error:', error));
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function changeUserRole(userId) {
    const form = document.getElementById('changeRoleForm_' + userId);
    const newRole = form.querySelector('select[name="new_role"]').value;

    console.log("User ID: ", userId);  
    console.log("New Role: ", newRole);  

    if (!newRole) {
        alert("Please select a role.");
        return;
    }

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('new_role', newRole);

    fetch('accounts.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Role updated successfully.");
            location.reload();  
        } else {
            alert(data.error || "Error updating role.");
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script>
    function openModal() {
        document.getElementById('userModal').style.display = 'block'; 
    }

    function closeModal() {
        document.getElementById('userModal').style.display = 'none'; 
    }
</script>
<script>
    function confirmDownloadAll() {
        return confirm("Are you sure you want to download the details of all users? This will include all user information.");
    }

    function confirmDownloadUser() {
        return confirm("Are you sure you want to download the details of selected user including attendance history?");
    }


</script>
<script src="../../assets/js/confirmations.js"></script>
</body>
</html>