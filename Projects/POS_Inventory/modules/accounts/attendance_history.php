<?php
include('../../includes/config.php'); 
session_start();

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../../index.php");
    exit();
}
$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo "User ID is required.";
    exit();
}

$sql_user_name = "SELECT name FROM Users WHERE user_id=?";
$stmt_user_name = $conn->prepare($sql_user_name);
$stmt_user_name->bind_param("i", $user_id);
$stmt_user_name->execute();
$result_user_name = $stmt_user_name->get_result();
$user = $result_user_name->fetch_assoc();
$user_name = htmlspecialchars($user['name']); 


$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$attendance_status = $_GET['attendance_status'] ?? '';


$sql_attendance_history = "SELECT * FROM Attendance WHERE user_id=?";

if ($start_date) {
    $sql_attendance_history .= " AND attendance_date >= ?";
}
if ($end_date) {
    $sql_attendance_history .= " AND attendance_date <= ?";
}
if ($attendance_status) {
    $sql_attendance_history .= " AND attendance_status = ?";
}

$sql_attendance_history .= " ORDER BY attendance_date DESC";


$stmt_attendance_history = $conn->prepare($sql_attendance_history);


$bind_params = [$user_id];
$types = "i";

if ($start_date) {
    $bind_params[] = $start_date;
    $types .= "s";
}
if ($end_date) {
    $bind_params[] = $end_date;
    $types .= "s";
}
if ($attendance_status) {
    $bind_params[] = $attendance_status;
    $types .= "s";
}


$stmt_attendance_history->bind_param($types, ...$bind_params);
$stmt_attendance_history->execute();
$result_attendance_history = $stmt_attendance_history->get_result();

$attendance_history = [];
$total_seconds = 0; 

while ($row = $result_attendance_history->fetch_assoc()) {
    $attendance_history[] = $row;

    if (!empty($row['time_in']) && !empty($row['time_out'])) {
        $time_in_obj = new DateTime($row['time_in']);
        $time_out_obj = new DateTime($row['time_out']);
        $interval = $time_in_obj->diff($time_out_obj);
        
        $total_seconds += $interval->h * 3600 + $interval->i * 60 + $interval->s; 
    }
}

function format_seconds($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf("%d hours, %d minutes, %d seconds", $hours, $minutes, $seconds);
}

function format_work_hours($time_in, $time_out) {
    if (!empty($time_in) && !empty($time_out)) {
        $time_in_obj = new DateTime($time_in);
        $time_out_obj = new DateTime($time_out);
        $interval = $time_in_obj->diff($time_out_obj);
        return $interval->format('%h hours, %i minutes, %s seconds');
    }
    return 'N/A';
}

$sql_monthly_summary = "
    SELECT 
        YEAR(attendance_date) AS year, 
        MONTH(attendance_date) AS month, 
        SUM(TIMESTAMPDIFF(SECOND, time_in, time_out)) AS total_seconds 
    FROM Attendance 
    WHERE user_id = ? 
    GROUP BY YEAR(attendance_date), MONTH(attendance_date) 
    ORDER BY YEAR(attendance_date) DESC, MONTH(attendance_date) DESC
";

$stmt_monthly_summary = $conn->prepare($sql_monthly_summary);
$stmt_monthly_summary->bind_param("i", $user_id);
$stmt_monthly_summary->execute();
$result_monthly_summary = $stmt_monthly_summary->get_result();

$monthly_summary = [];
while ($row = $result_monthly_summary->fetch_assoc()) {
    $monthly_summary[] = $row;
}

$sql_weekly_summary = "
    SELECT 
        YEAR(attendance_date) AS year, 
        WEEK(attendance_date, 1) AS week, 
        MIN(attendance_date) AS week_start, 
        MAX(attendance_date) AS week_end, 
        SUM(TIMESTAMPDIFF(SECOND, time_in, time_out)) AS total_seconds 
    FROM Attendance 
    WHERE user_id = ? 
    GROUP BY YEAR(attendance_date), WEEK(attendance_date, 1) 
    ORDER BY YEAR(attendance_date) DESC, WEEK(attendance_date, 1) DESC
";

$stmt_weekly_summary = $conn->prepare($sql_weekly_summary);
$stmt_weekly_summary->bind_param("i", $user_id);
$stmt_weekly_summary->execute();
$result_weekly_summary = $stmt_weekly_summary->get_result();

$weekly_summary = [];
while ($row = $result_weekly_summary->fetch_assoc()) {
    $weekly_summary[] = $row;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_id = $_POST['attendance_id'] ?? null;
    $manual_time_out = $_POST['manual_time_out'] ?? null;

    if ($attendance_id && $manual_time_out) {
        $time_out = date('Y-m-d H:i:s', strtotime($manual_time_out));

        $sql = "UPDATE Attendance SET time_out = ? WHERE attendance_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $time_out, $attendance_id);

        if ($stmt->execute()) {
            header("Location: attendance_history.php?user_id=" . $_GET['user_id']); 
        } else {
            echo "Error updating time-out.";
        }
    } else {
        echo "Invalid data.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance History</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/attendance.css">
    <link rel="stylesheet" href="../../assets/css/search_filter.css">
    <link rel="stylesheet" href="../../assets/css/indicators.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <link rel="stylesheet" href="../../assets/css/table.css">
</head>
<body>
<?php include('../../includes/sidebar.php'); ?>
<div class="container">

<div class="page-header">
    <form action="" method="GET" id="attendanceFilterForm" class="filter-form">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">

        <div class="filter-group">
            <label for="start_date">Start Date:</label>
            <input onchange="this.form.submit()" type="date" name="start_date" id="start_date" 
                   value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>">
        </div>

        <div class="filter-group">
            <label for="end_date">End Date:</label>
            <input onchange="this.form.submit()" type="date" name="end_date" id="end_date" 
                   value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>">
        </div>

        <div>
            <select name="attendance_status" id="attendance_status" onchange="this.form.submit()">
                <option value="">Attendance Status</option>
                <option value="Present" <?= isset($_GET['attendance_status']) && $_GET['attendance_status'] === 'Present' ? 'selected' : '' ?>>Present</option>
                <option value="Absent" <?= isset($_GET['attendance_status']) && $_GET['attendance_status'] === 'Absent' ? 'selected' : '' ?>>Absent</option>
            </select>
        </div>
    </form>

    <div class="filter-note">
        <a href="download_user_details.php?action=attendance_history&user_id=<?= $user_id ?>" class="download-button" onclick="return confirmDownloadUser()">
            <span class="download-text">Download</span>
            <span class="download-icon">
                <i class="bx bx-download"></i>
            </span>
        </a>
    </div>
</div>


<div class="attendance-container">

    <div class="card-body">
        <div class="scrollable-attendance">
            
        <h5>Attendance history of <?= $user_name ?></h5>
            <table class="attendance-table">
                <tr>
                    <thead>
                        <th></th>
                        <th>Date</th>
                        <th>Clocked Hours</th>
                        <th>Work Hours</th>
                        <th>Attendance Status</th>
                    </thead>
                </tr>
                <?php
                    foreach ($attendance_history as $attendance): 
                        $attendance_date = date("F j, Y", strtotime($attendance['attendance_date']));
                        $time_in = !empty($attendance['time_in']) ? date("h:i:s A", strtotime($attendance['time_in'])) : '-';
                        $time_out = !empty($attendance['time_out']) ? date("h:i:s A", strtotime($attendance['time_out'])) : '';

                        $current_date = date("Y-m-d"); 
                        $attendance_day = date("Y-m-d", strtotime($attendance['attendance_date']));

                        $show_manual_time_out = !empty($attendance['time_in']) && empty($attendance['time_out']) && $attendance_day !== $current_date;

                        $status_class = strtolower($attendance['attendance_status']) === 'present' ? 'present' : 'absent';
                ?>
                <tr>
                    <td></td>
                    <td><?= $attendance_date ?></td>
                    <td style="display: flex">
                        <?= $time_in ?> <?= $time_out ? ' - ' . $time_out : '-' ?>
                        <?php if ($show_manual_time_out): ?>
                            <form action=" " method="POST">
                                <input type="hidden" name="attendance_id" value="<?= $attendance['attendance_id'] ?>">
                                <input type="time" name="manual_time_out" required>
                                <button type="submit" class="save-time-out-button">Save Time-Out</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= format_work_hours($attendance['time_in'], $attendance['time_out']) ?>
                    </td>
                    <td class="<?= $status_class ?>"><span><?= htmlspecialchars($attendance['attendance_status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
    
        </div>
        <a href="attendance.php" class="back-button">
            <i class="bx bx-arrow-back"></i> Back to Attendance
        </a>
    </div>
</div>


<p><strong>Total Hours Worked:</strong> <?= format_seconds($total_seconds) ?></p>
<div class="summary-container">
    <div class="summary-table-container">
        <h3>Monthly Summary</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Total Hours Worked</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthly_summary as $month): ?>
                    <tr>
                        <td><?= $month['year'] ?></td>
                        <td><?= date('F', mktime(0, 0, 0, $month['month'], 10)) ?></td>
                        <td><?= format_seconds($month['total_seconds']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="summary-table-container">
        <h3>Weekly Summary</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Week Range</th>
                    <th>Total Hours Worked</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weekly_summary as $week): ?>
                    <?php
                        $week_start = date("F j, Y", strtotime($week['week_start']));
                        $week_end = date("F j, Y", strtotime($week['week_end']));
                        $total_worked_hours = format_seconds($week['total_seconds']);
                    ?>
                    <tr>
                        <td><?= $week['week'] ?></td>
                        <td><?= $week_start ?> - <?= $week_end ?></td>
                        <td><?= $total_worked_hours ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.save-time-out-button {
    background-color: #000;
    color: white;
    padding:6px;
    font-size: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-time-out-button:hover {
    background-color: #333;
}

.save-time-out-button:focus {
    outline: none;
}


.summary-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 20px;
}

.summary-table-container {
    width: 48%;
    padding: 10px;
    border: 1px solid black;
    border-radius: 4px;
}

.summary-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    color: black;
}

.summary-table th,
.summary-table td {
    padding: 8px 12px;
    border: 1px solid black;
    text-align: left;
}

.summary-table th {
    text-transform: uppercase;
    font-weight: bold;
    border-bottom: 2px solid black;
}

.summary-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.summary-table tbody tr:hover {
    background-color: #e6e6e6;
    cursor: default;
}


.summary-table-container h3 {
    margin-bottom: 10px;
    font-size: 16px;
    text-transform: uppercase;
    font-weight: bold;
    color: black;
    border-bottom: 1px solid black;
    padding-bottom: 5px;
}

@media (max-width: 768px) {
    .summary-container {
        width: 100%;
        flex-direction: column;
        gap: 15px;
    }

    .summary-table-container {
        width: 100%;
    }

    .summary-table th,
    .summary-table td {
        font-size: 12px;
    }
}
</style>


</div>

<script src="../../assets/js/confirmations.js"></script>

</body>
</html>
