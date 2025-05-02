<?php
include('../../includes/config.php');
session_start();


if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../../index.php");
    exit();
}

date_default_timezone_set('Asia/Manila');


$user_role = isset($_SESSION['admin_log']) ? 'Admin' : (isset($_SESSION['log_emp']) ? 'Employee' : '');

$sql_users = "SELECT * FROM Users";

if ($user_role === 'Employee') {
    $sql_users .= " WHERE role = 'Employee'";
} else {
    
    $sql_users .= " WHERE 1=1";
}


$searchName = $_POST['search_name'] ?? '';
$searchRole = $_POST['search_role'] ?? '';
$searchStatus = $_POST['search_status'] ?? '';


if (!empty($searchName)) {
    $sql_users .= " AND name LIKE ?";
}
if (!empty($searchRole)) {
    $sql_users .= " AND role = ?";
}
if (!empty($searchStatus)) {
    $sql_users .= " AND status = ?";
}


$stmt_users = $conn->prepare($sql_users);

$bind_params = [];
$types = "";

if (!empty($searchName)) {
    $bind_params[] = "%" . $searchName . "%";
    $types .= "s";
}
if (!empty($searchRole)) {
    $bind_params[] = $searchRole;
    $types .= "s";
}
if (!empty($searchStatus)) {
    $bind_params[] = $searchStatus;
    $types .= "s";
}


if (!empty($bind_params)) {
    $stmt_users->bind_param($types, ...$bind_params);
}


$stmt_users->execute();
$result_users = $stmt_users->get_result();


$attendance_history = [];
$current_date = date('Y-m-d');
$current_day = date('l');


function markAbsentUsers($conn, $attendance_date) {
    
    $sql_users = "SELECT user_id FROM Users WHERE status = 'active'";
    $result_users = $conn->query($sql_users);
    
    
    while ($row = $result_users->fetch_assoc()) {
        $user_id = $row['user_id'];

        
        $sql_attendance = "SELECT * FROM Attendance WHERE user_id = ? AND attendance_date = ?";
        $stmt_attendance = $conn->prepare($sql_attendance);
        $stmt_attendance->bind_param("is", $user_id, $attendance_date);
        $stmt_attendance->execute();
        $result_attendance = $stmt_attendance->get_result();

        
        if ($result_attendance->num_rows === 0) {
            $insert_sql = "INSERT INTO Attendance (user_id, attendance_date, attendance_status) VALUES (?, ?, 'Absent')";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param("is", $user_id, $attendance_date);
            $stmt_insert->execute();
        }
    }
}

markAbsentUsers($conn, $current_date);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($user_id && $action) {
        $check_user_sql = "SELECT status FROM Users WHERE user_id=?";
        $stmt = $conn->prepare($check_user_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user_data = $user_result->fetch_assoc();

        $response = '';  

        switch ($user_data['status']) {
            case 'inactive':
                $response = 'This account is inactive and cannot clock in or out.';
                break;

            default:
                $attendance_date = date('Y-m-d');

                switch ($action) {
                    case 'time_in':
                        $response = handleTimeIn($conn, $user_id, $attendance_date);
                        break;

                    case 'time_out':
                        $response = handleTimeOut($conn, $user_id, $attendance_date);
                        break;

                    default:
                        $response = 'Invalid action.';
                        break;
                }
                break;
        }
        
        
        echo $response;
        exit();
    }
}

while ($user = $result_users->fetch_assoc()) {
    $user_id = $user['user_id'];
    $sql_attendance = "SELECT * FROM Attendance WHERE user_id=? AND attendance_date=? ORDER BY attendance_date DESC";
    $stmt_attendance = $conn->prepare($sql_attendance);
    $stmt_attendance->bind_param("is", $user_id, $current_date);
    $stmt_attendance->execute();
    $result_attendance = $stmt_attendance->get_result();
    
    $attendance_data = $result_attendance->fetch_all(MYSQLI_ASSOC);
    $attendance_history[$user_id] = calculateAttendance($attendance_data, $user);
}

function handleTimeIn($conn, $user_id, $attendance_date) {
    $time_in = date('H:i:s');

    $check_sql = "SELECT * FROM Attendance WHERE user_id=? AND attendance_date=? AND time_in IS NOT NULL";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("is", $user_id, $attendance_date);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        return 'You have already clocked in today.';
    } else {
        $insert_sql = "UPDATE Attendance SET time_in=?, attendance_status='Present' WHERE user_id=? AND attendance_date=?";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sis", $time_in, $user_id, $attendance_date);
        if ($stmt->execute()) {
            return 'Clocked in successfully.';
        } else {
            return 'Error executing query: ' . $stmt->error;
        }
    }
}

function handleTimeOut($conn, $user_id, $attendance_date) {
    $time_out = date('H:i:s');

    $check_sql = "SELECT * FROM Attendance WHERE user_id=? AND attendance_date=? AND time_in IS NOT NULL AND time_out IS NULL";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("is", $user_id, $attendance_date);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        return 'You must clock in before clocking out.';
    } else {
        $update_sql = "UPDATE Attendance SET time_out=? WHERE user_id=? AND attendance_date=? AND time_out IS NULL";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sis", $time_out, $user_id, $attendance_date);
        if ($stmt->execute()) {
            return 'Clocked out successfully.';
        } else {
            return 'Error executing query: ' . $stmt->error;
        }
    }
}


function calculateAttendance($attendance_data, $user) {
    $time_in = null;
    $time_out = null;
    $total_seconds = 0;  

    foreach ($attendance_data as $entry) {
        if ($entry['time_in']) {
            if (!$time_in || $entry['time_in'] < $time_in) {
                $time_in = $entry['time_in'];
            }
        }
        if ($entry['time_out']) {
            if (!$time_out || $entry['time_out'] > $time_out) {
                $time_out = $entry['time_out'];
            }
        }
    }

    
    if ($time_in && $time_out) {
        $start = new DateTime($time_in);
        $end = new DateTime($time_out);
        $interval = $start->diff($end);

        
        $total_seconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
    }

    
    $hours = floor($total_seconds / 3600);
    $minutes = floor(($total_seconds % 3600) / 60);
    $seconds = $total_seconds % 60;

    return [
        'user' => $user,
        'attendance' => $attendance_data,
        'time_in' => $time_in,
        'time_out' => $time_out,
        'total_hours' => sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds), 
    ];
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <title>Attendance Tracking</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/attendance.css">
    <link rel="stylesheet" href="../../assets/css/indicators.css">
    <link rel="stylesheet" href="../../assets/css/table.css">
    <link rel="stylesheet" href="../../assets/css/search_filter.css">
</head>
<body>

<?php include('../../includes/sidebar.php');  ?>

<div class="container">
    
    <div class="page-header">
    <!-- <h5>Records</h5> -->
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="attendanceFilterForm">
            <div style="display: flex;">
                <input class="userSearch" type="text" name="search_name" placeholder="Search by name" value="<?= htmlspecialchars($searchName) ?>">
                <button class="search" type="submit"><i class='bx bx-search-alt'></i></button>
            </div>
            <div style="display: flex;">
            <select name="search_role" id="role_filter" onchange="this.form.submit()">
                    <option value="">Select Roles to filter</option>
                    <option value="Admin" <?= $searchRole === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Employee" <?= $searchRole === 'Employee' ? 'selected' : '' ?>>Employee</option>
                </select>
                <select name="search_status" id="status_filter" onchange="this.form.submit()">
                <option value="">Select Status to filter</option>
                    <option value="active" <?= $searchStatus === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $searchStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </form>
    </div>

    
<!-- table -->
        <div class="attendance-container">

                <div class="card-body">
                    <div id="attendance" class="tab-content active">
                    <p>
                        <span class="attendance-date"><b>Date:</b></span> 
                        <?= date("F j, Y", strtotime($current_date)) ?> 
                        (<?= htmlspecialchars($current_day) ?>)
                    </p>
                        <table class="attendance-table">
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Name</th>
                                <th>Indicator</th>
                                <th>Time In & Out</th>
                                <th>Total Hours</th>
                                <th>Action</th>
                            </tr>
                            <tbody id="attendanceTableBody">
                                <?php  $index = 1;
                                foreach ($attendance_history as $entry): ?>
                                    <?php 
                                        $user = $entry['user'];
                                        $is_clocked_in = !empty($entry['time_in']) && empty($entry['time_out']); 
                                        $is_inactive = $user['status'] === 'inactive'; 
                                        $is_complete = !empty($entry['time_in']) && !empty($entry['time_out']);
            
                                        $indicator = $is_complete ? 'Complete' : ($is_clocked_in ? 'Clocked In' : ($is_inactive ? 'Inactive' : 'Not Clocked In'));
                                        $indicator_class = $is_complete ? 'green-background' : ($is_clocked_in ? 'blue-background' : ($is_inactive ? 'inactive' : 'yellow-background'));
                                        ?>
                                    <tr class="attendance-row" data-role="<?= htmlspecialchars($user['role']) ?>" data-status="<?= htmlspecialchars($user['status']) ?>">
                                    <td></td>
                                        <td><?= $index++ ?></td>
                                        <td c>
                                            <div class="user-attendance">
                                            <span class="user-attendance-name"><?= htmlspecialchars($user['name']) ?></span>
                                                <button class="view-history-button" 
                                                        onclick="location.href='attendance_history.php?user_id=<?= htmlspecialchars($user['user_id']) ?>'">
                                                    <i class='bx bx-history'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="<?= $indicator_class ?>"><span><?= htmlspecialchars($indicator) ?></span></td>
                                        <td>
                                            <?= !empty($entry['time_in']) ? date("h:i:s A", strtotime($entry['time_in'])) : '-' ?>
                                            <?= !empty($entry['time_in']) && !empty($entry['time_out']) ? ' - ' : '' ?>
                                            <?= !empty($entry['time_out']) ? date("h:i:s A", strtotime($entry['time_out'])) : '-' ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($entry['total_hours']) ?>
                                        </td>

                                        <td>
                                            <?php if (!$is_inactive): ?>
                                                <form method="post" class="clock-button">
                                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                                    
                                                    <button type="submit" name="action" value="time_in" class="clock-button" 
                                                            <?= $is_clocked_in || $is_complete ? 'disabled' : '' ?>>Clock In</button>
                                                    <button type="submit" name="action" value="time_out" class="clock-button" 
                                                            <?= !$is_clocked_in || $is_complete ? 'disabled' : '' ?>>Clock Out</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="red-background">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
          </div>
</div>

<script src="../../assets/js/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    function clockAction(userId, action) {
        $.ajax({
            url: 'attendance.php', 
            type: 'POST',
            data: {
                user_id: userId,
                action: action
            },
            success: function(response) {
                
                alert(response);
                location.reload(); 
            },
            success: function(response) {
                console.log('Response:', response);  
                alert(response); 
                location.reload(); 
            },

            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while processing your request.');
            }
        });
    }

    
    $('button[name="action"][value="time_in"]').on('click', function(event) {
        event.preventDefault(); 
        const userId = $(this).closest('form').find('input[name="user_id"]').val();
        clockAction(userId, 'time_in');
    });

    
    $('button[name="action"][value="time_out"]').on('click', function(event) {
        event.preventDefault(); 
        const userId = $(this).closest('form').find('input[name="user_id"]').val();
        clockAction(userId, 'time_out');
    });
});
</script>

</body>
</html>
