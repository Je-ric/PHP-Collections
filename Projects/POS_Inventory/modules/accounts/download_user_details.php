<?php
include('../../includes/config.php');
session_start();

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $data = [];
    $headers = [];
    $filename = '';

    if ($action === 'all_users') {
        $sql = "SELECT user_id, name, username, email, phone, role, status FROM Users";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        $row['user_id'],
                        $row['name'],
                        $row['username'],
                        $row['email'],
                        $row['phone'],
                        $row['role'],
                        ucfirst($row['status']),
                    ];
                }

                $headers = ['User ID', 'Name', 'Username', 'Email', 'Phone', 'Role', 'Status'];
                $filename = 'all_users.csv';
            } else {
                echo "No users found.";
                exit();
            }
        } else {
            echo "SQL query failed: " . $conn->error;
            exit();
        }

    } elseif ($action === 'attendance_history' && isset($_GET['user_id'])) {
        $userId = intval($_GET['user_id']);
        echo "User ID: $userId"; 
        
        $userSql = "SELECT user_id, name, username, email, phone, role, status FROM Users WHERE user_id = ?";
        $stmt = $conn->prepare($userSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $userResult = $stmt->get_result();  

        if ($userResult->num_rows > 0) {
            $user = $userResult->fetch_assoc();
            $userDetails = [
                'User ID' => $user['user_id'],
                'Name' => $user['name'],
                'Username' => $user['username'],
                'Email' => $user['email'],
                'Phone' => $user['phone'],
                'Role' => $user['role'],
                'Status' => ucfirst($user['status']),
            ];

            $filename = "{$user['name']}_attendance_with_details.csv";
        } else {
            echo "No user found with ID: $userId";
            exit();
        }

        $attendanceSql = "SELECT attendance_date, time_in, time_out, attendance_status FROM Attendance WHERE user_id = ?";
        $stmt = $conn->prepare($attendanceSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $attendanceResult = $stmt->get_result();
        
        // echo "Attendance records found: " . $attendanceResult->num_rows; 

        if ($attendanceResult->num_rows > 0) {
            $data[] = ['User Details'];
            foreach ($userDetails as $key => $value) {
                $data[] = [$key, $value];
            }

            $data[] = ['Attendance History'];
            $data[] = ['Date', 'Time In', 'Time Out', 'Status']; 

            while ($row = $attendanceResult->fetch_assoc()) {
                $data[] = [
                    $row['attendance_date'],
                    $row['time_in'],
                    $row['time_out'],
                    ucfirst($row['attendance_status']),
                ];
            }

        } else {
            echo "No attendance records found for user ID: $userId";
            exit();
        }

    } else {
        echo "Invalid request.";
        exit();
    }

    if (!empty($data) && !empty($filename)) {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");

        $output = fopen('php://output', 'w');
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    } else {
        echo "No data available for download.";
        exit();
    }

} else {
    echo "No action specified.";
    exit();
}
?>
