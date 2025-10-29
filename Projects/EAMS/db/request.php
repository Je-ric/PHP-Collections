<?php
date_default_timezone_set('Asia/Manila');
require_once 'db.php';

$mydb = new myDB();

if (isset($_POST['update_attendance'])) {
    $id = $_POST['id'];
    $fields = [];
    $params = [];
    $types = "";

    if (isset($_POST['time_in']) && $_POST['time_in'] !== "") {
        $fields[] = "time_in = ?";
        $params[] = $_POST['time_in'];
        $types .= "s";
    }
    if (isset($_POST['time_out']) && $_POST['time_out'] !== "") {
        $fields[] = "time_out = ?";
        $params[] = $_POST['time_out'];
        $types .= "s";
    }
    if (empty($fields)) {
        echo json_encode([
            "success" => false,
            "message" => "No changes provided."
        ]);
        exit;
    }
    $params[] = $id;
    $types .= "i";
    $sql = "UPDATE tbl_attendance SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $mydb->conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $ok = $stmt->execute();

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Attendance updated successfully!" : "Failed to update attendance."
    ]);
    exit;
}
if (isset($_POST['time_in'])) {
    $emp_id   = $_POST['id'];
    $password = $_POST['password'];

    $stmt = $mydb->conn->prepare("SELECT email FROM tbl_employees WHERE id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
        exit;
    }
    $emp = $res->fetch_assoc();

    $stmt = $mydb->conn->prepare("
        SELECT id FROM tbl_users 
        WHERE email = ? AND password = SHA2(?, 256) AND role = 'employee'
    ");
    $stmt->bind_param("ss", $emp['email'], $password);
    $stmt->execute();
    $userRes = $stmt->get_result();
    if ($userRes->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Incorrect password, please try again."]);
        exit;
    }

    $stmt = $mydb->conn->prepare("SELECT id FROM tbl_attendance WHERE emp_id = ? AND date = CURDATE()");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $checkRes = $stmt->get_result();
    if ($checkRes->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "You have already timed in today."]);
        exit;
    }

    $stmt = $mydb->conn->prepare("
        INSERT INTO tbl_attendance (emp_id, date, time_in) 
        VALUES (?, CURDATE(), CURTIME())
    ");
    $stmt->bind_param("i", $emp_id);
    $ok = $stmt->execute();

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Time In recorded!" : "Failed to record Time In."
    ]);
    exit;
}

if (isset($_POST['time_out'])) {
    $emp_id   = $_POST['id'];
    $password = $_POST['password'];

    $stmt = $mydb->conn->prepare("SELECT email FROM tbl_employees WHERE id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
        exit;
    }
    $emp = $res->fetch_assoc();

    $stmt = $mydb->conn->prepare("
        SELECT id FROM tbl_users 
        WHERE email = ? AND password = SHA2(?, 256) AND role = 'employee'
    ");
    $stmt->bind_param("ss", $emp['email'], $password);
    $stmt->execute();
    $userRes = $stmt->get_result();
    if ($userRes->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Incorrect password, please try again."]);
        exit;
    }

    $stmt = $mydb->conn->prepare("SELECT id FROM tbl_attendance WHERE emp_id = ? AND date = CURDATE()");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $checkRes = $stmt->get_result();
    if ($checkRes->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Please Time In first before Time Out."]);
        exit;
    }

    $stmt = $mydb->conn->prepare("
        UPDATE tbl_attendance 
        SET time_out = CURTIME() 
        WHERE emp_id = ? AND date = CURDATE() AND time_out IS NULL
    ");
    $stmt->bind_param("i", $emp_id);
    $ok = $stmt->execute();

    if ($ok && $stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Time Out recorded!"]);
    } else {
        echo json_encode(["success" => false, "message" => "You have already timed out today."]);
    }
    exit;
}

if (isset($_POST['get_employee']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $mydb->conn->prepare("SELECT id, full_name, emp_pic FROM tbl_employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if ($data) {
        $data['emp_pic'] = !empty($data['emp_pic']) ? "pics/" . $data['emp_pic'] : "pics/default.png";
        echo json_encode($data);
    } else {
        echo json_encode(["id" => $id, "full_name" => null, "emp_pic" => "pics/default.png"]);
    }
    exit;
}

if (isset($_POST['get_employees'])) {
    $stmt = $mydb->conn->prepare("
        SELECT e.*,
               a.time_in  IS NOT NULL AS time_in_done,
               a.time_out IS NOT NULL AS time_out_done
        FROM tbl_employees e
        LEFT JOIN tbl_attendance a
          ON e.id = a.emp_id AND a.date = CURDATE()
    ");
    $stmt->execute();
    $res = $stmt->get_result();
    $employees = [];
    while ($row = $res->fetch_assoc()) {
        $employees[] = $row;
    }
    echo json_encode($employees);
    exit;
}

if (isset($_POST['search_employees'])) {
    $searchValue = "%" . $_POST['search_employees'] . "%";
    $stmt = $mydb->conn->prepare("
        SELECT * FROM tbl_employees
        WHERE full_name LIKE ? OR email LIKE ? OR position LIKE ?
    ");
    $stmt->bind_param("sss", $searchValue, $searchValue, $searchValue);
    $stmt->execute();
    $result = $stmt->get_result();

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode($employees);
    exit;
}

if (isset($_POST['add_employees'])) {
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $position  = $_POST['position'];
    $password  = !empty($_POST['password']) ? trim($_POST['password']) : "1234";

    $emp_pic = null;
    if (!empty($_FILES['emp_pic']['name'])) {
        $targetDir  = "../pics/";
        $baseName   = basename($_FILES['emp_pic']['name']);      
        $fileName   = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $baseName);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['emp_pic']['tmp_name'], $targetFile)) {
            $emp_pic = $fileName;
        }
    }

    $stmtUser = $mydb->conn->prepare("
        INSERT INTO tbl_users (email, password, role) 
        VALUES (?, SHA2(?, 256), 'employee')
    ");
    $stmtUser->bind_param("ss", $email, $password);
    $stmtUser->execute();
    $user_id = $mydb->conn->insert_id;

    $stmtEmp = $mydb->conn->prepare("
        INSERT INTO tbl_employees (full_name, email, position, emp_pic, user_id) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmtEmp->bind_param("ssssi", $full_name, $email, $position, $emp_pic, $user_id);
    $empInserted = $stmtEmp->execute();

    echo json_encode([
        "success" => $empInserted,
        "message" => $empInserted ? "Employee added successfully" : "Failed to add employee"
    ]);
    exit;
}

if (isset($_POST['update_employees'])) {
    $id       = $_POST['id'];
    $user_id  = $_POST['user_id'];
    $full_name= $_POST['full_name'];
    $email    = $_POST['email'];
    $position = $_POST['position'];
    $password = trim($_POST['password']); // may be empty

    $emp_pic = null;
    if (!empty($_FILES['emp_pic']['name'])) {
        $targetDir  = "../pics/";
        $baseName   = basename($_FILES['emp_pic']['name']);
        $fileName   = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $baseName);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['emp_pic']['tmp_name'], $targetFile)) {
            $emp_pic = $fileName;
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Picture upload failed. Error code: " . ($_FILES['emp_pic']['error'] ?? 'unknown')
            ]);
            exit;
        }
    }

    if ($emp_pic) {
        $stmtEmp = $mydb->conn->prepare("
            UPDATE tbl_employees 
            SET full_name = ?, email = ?, position = ?, emp_pic = ?
            WHERE id = ?
        ");
        $stmtEmp->bind_param("ssssi", $full_name, $email, $position, $emp_pic, $id);
    } else {
        $stmtEmp = $mydb->conn->prepare("
            UPDATE tbl_employees 
            SET full_name = ?, email = ?, position = ?
            WHERE id = ?
        ");
        $stmtEmp->bind_param("sssi", $full_name, $email, $position, $id);
    }
    $empUpdated = $stmtEmp->execute();

    if ($password !== "") {
        $stmtUser = $mydb->conn->prepare("
            UPDATE tbl_users 
            SET email = ?, password = SHA2(?, 256)
            WHERE id = ?
        ");
        $stmtUser->bind_param("ssi", $email, $password, $user_id);
    } else {
        $stmtUser = $mydb->conn->prepare("
            UPDATE tbl_users 
            SET email = ?
            WHERE id = ?
        ");
        $stmtUser->bind_param("si", $email, $user_id);
    }
    $userUpdated = $stmtUser->execute();

    echo json_encode([
        "success" => ($empUpdated && $userUpdated),
        "message" => ($empUpdated && $userUpdated) ? "Employee & User updated successfully" : "Failed to update"
    ]);
    exit;
}

if (isset($_POST['delete_employees']) && $_POST['delete_employees'] == true) {
    $emp_id  = $_POST['id'];
    $user_id = $_POST['user_id'];

    $stmt = $mydb->conn->prepare("SELECT emp_pic FROM tbl_employees WHERE id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && !empty($row['emp_pic'])) {
        $picPath = realpath(__DIR__ . "/../pics/" . $row['emp_pic']);
        $picsDir = realpath(__DIR__ . "/../pics");
        if ($picPath && strpos($picPath, $picsDir) === 0 && file_exists($picPath)) {
            @unlink($picPath);
        }
    }

    $stmt = $mydb->conn->prepare("DELETE FROM tbl_employees WHERE id = ?");
    $stmt->bind_param("i", $emp_id);
    $empDeleted = $stmt->execute();

    $stmt = $mydb->conn->prepare("DELETE FROM tbl_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $userDeleted = $stmt->execute();

    if ($empDeleted && $userDeleted) {
        echo json_encode([
            "success" => true,
            "message" => "Employee record and user account have been deleted."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete employee.",
            "error"   => $mydb->conn->error
        ]);
    }
    exit;
}
if (isset($_POST['get_employee_attendance_preview'])) {
    $emp_id = intval($_POST['emp_id']);
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 7;
    
    $stmt = $mydb->conn->prepare("
        SELECT a.*, e.full_name
        FROM tbl_attendance a
        JOIN tbl_employees e ON a.emp_id = e.id
        WHERE a.emp_id = ?
        ORDER BY a.date DESC, a.time_in DESC
        LIMIT ?
    ");
    
    $stmt->bind_param("ii", $emp_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = [
            'id'           => $row['id'],
            'emp_id'       => $row['emp_id'],
            'full_name'    => $row['full_name'],
            'date'         => $row['date'],
            'time_in'      => $row['time_in'],
            'time_out'     => $row['time_out'],
            'time_in_fmt'  => $row['time_in']  ? date("g:i A", strtotime($row['time_in']))  : null,
            'time_out_fmt' => $row['time_out'] ? date("g:i A", strtotime($row['time_out'])) : null
        ];
    }
    
    echo json_encode($records);
    exit;
}
if (isset($_POST['attendance_summary'])) {
    $from = $_POST['from'];
    $to   = $_POST['to'];

    $stmt = $mydb->conn->prepare("
        SELECT a.*, e.full_name, e.emp_pic
        FROM tbl_attendance a
        JOIN tbl_employees e ON a.emp_id = e.id
        WHERE a.date BETWEEN ? AND ?
        ORDER BY a.date ASC
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = [
            'id'        => $row['id'],
            'profile'   => $row['emp_pic'] ? "pics/" . $row['emp_pic'] : "pics/default.png",
            'full_name' => $row['full_name'],
            'date'      => $row['date'],
            'time_in'   => $row['time_in'], 
            'time_out'  => $row['time_out'], 
            'time_in_fmt'  => $row['time_in']  ? date("g:i A", strtotime($row['time_in']))   : null,
            'time_out_fmt' => $row['time_out'] ? date("g:i A", strtotime($row['time_out']))  : null
        ];
    }

    echo json_encode($records);
    exit;
}

if (isset($_POST['admin_login'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        echo json_encode(['success' => false, 'message' => 'Email and password cannot be empty']);
        exit;
    }

    $stmt = $mydb->conn->prepare("
        SELECT id, email, role 
        FROM tbl_users 
        WHERE email = ? AND password = SHA2(?, 256)
        LIMIT 1
    ");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        session_start();
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        echo json_encode(['success' => true, 'message' => 'Login successful', 'role' => $user['role']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
    exit;
}

?>
