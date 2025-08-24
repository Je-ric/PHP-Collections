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


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    $response = ["status" => false, "message" => ""];

    $action = $_POST['action'];

    if ($action == "fetch_roles") {
        ob_start();
        fetchRoles(); 
        $tableHTML = ob_get_clean();
        echo json_encode(["status" => true, "tableHTML" => $tableHTML]);
        exit();
    }

    if ($action == "add") {
        $role_name = $_POST['role_name'];
        $query = mysqli_query($conn, "INSERT INTO roles (role_name) VALUES ('$role_name')");
        if ($query) {
            $response = ["status" => true, "message" => "Role added successfully!"];
        } else {
            $response["message"] = "Failed to add role.";
        }
    } elseif ($action == "edit") {
        $role_id = $_POST['role_id'];
        $role_name = $_POST['role_name'];
        $query = mysqli_query($conn, "UPDATE roles SET role_name='$role_name' WHERE id=$role_id");
        if ($query) {
            $response = ["status" => true, "message" => "Role updated successfully!"];
        } else {
            $response["message"] = "Failed to update role.";
        }
    } elseif ($action == "delete") {
        $role_id = $_POST['role_id'];
        $query = mysqli_query($conn, "DELETE FROM roles WHERE id=$role_id");
        if ($query) {
            $response = ["status" => true, "message" => "Role deleted successfully!"];
        } else {
            $response["message"] = "Failed to delete role.";
        }
    }

    echo json_encode($response);
    exit();
}

function fetchRoles()
{
    global $conn;
    echo '<table class="w-full border-collapse border border-gray-300 text-center text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">#</th>
                    <th class="border border-gray-300 px-4 py-2">Role Name</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>';
    $query = mysqli_query($conn, "SELECT * FROM roles");
    $count = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr data-role-id='{$row['id']}' class='border border-gray-300'>
                <td class='border border-gray-300 px-4 py-2'>{$count}</td>
                <td class='border border-gray-300 px-4 py-2 role-name'>{$row['role_name']}</td>
                <td class='border border-gray-300 px-4 py-2'>
                    <button class='bg-yellow-500 text-white px-2 py-1 rounded editRole'>Edit</button>
                    <button class='bg-red-500 text-white px-2 py-1 rounded deleteRole'>Delete</button>
                </td>
              </tr>";
        $count++;
    }
    echo '</tbody></table>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #toast-container {
            top: 20px !important;
            right: 20px !important;
        }
    </style>    
</head>

<body>


    <?php include('../../includes/sidebar.php'); ?>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-5">
        <h2 class="text-2xl font-bold mb-4">Manage Roles</h2>
        
        <div class="flex space-x-2 mb-4">
            <input type="text" id="new_role_name" class="w-full p-2 border rounded" placeholder="Enter new role">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" id="addRoleBtn">Add</button>
        </div>
        
        <div id="rolesContainer">
            <?php fetchRoles(); ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Set Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            function fetchRoles() {
                $.post("manage_roles.php", {
                    action: "fetch_roles"
                }, function(response) {
                    if (response.status) {
                        $("#rolesContainer").html(response.tableHTML);
                    }
                }, "json");
            }

            // Add Role
            $("#addRoleBtn").click(function() {
                let roleName = $("#new_role_name").val().trim();
                if (roleName === "") {
                    toastr.error("Role name cannot be empty!");
                    return;
                }
                $.post("manage_roles.php", {
                    action: "add",
                    role_name: roleName
                }, function(response) {
                    if (response.status) {
                        fetchRoles();
                        $("#new_role_name").val("");
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }, "json");
            });

            // Edit Role (Event Delegation)
            $(document).on("click", ".editRole", function() {
                let row = $(this).closest("tr");
                let roleId = row.data("role-id");
                let roleName = row.find(".role-name").text();
                let newRoleName = prompt("Edit Role Name:", roleName);
                if (newRoleName && newRoleName !== roleName) {
                    $.post("manage_roles.php", {
                        action: "edit",
                        role_id: roleId,
                        role_name: newRoleName
                    }, function(response) {
                        if (response.status) {
                            fetchRoles();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }, "json");
                }
            });

            // Delete Role (Event Delegation)
            $(document).on("click", ".deleteRole", function() {
                let row = $(this).closest("tr");
                let roleId = row.data("role-id");
                if (confirm("Are you sure you want to delete this role?")) {
                    $.post("manage_roles.php", {
                        action: "delete",
                        role_id: roleId
                    }, function(response) {
                        if (response.status) {
                            fetchRoles();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }, "json");
                }
            });
        });
    </script>

</body>

</html>