<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
require 'db/db.php';
$db = new myDB();
$db->select('tbl_employees');
$employees_to_display = $db->srs;
$start = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Side</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/admin.js"></script>
</head>
<body class="bg-light">

<div class="container my-4 p-4 shadow rounded bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold text-dark">Employee Records</h1>
        <div class="d-flex gap-2">

            <div class="search-bar">
                <button class="search-btn"><i class="fas fa-search"></i></button>
                <input type="text" id="searchInput" placeholder="Search here...">
            </div>
            <button id="attendanceSummaryBtn" class="btn view-btn">
                <i class="fas fa-clipboard-list"></i> View Attendance Summary
            </button>
            <button class="btn add-btn" onclick="openAddModal()">
                <i class="fas fa-user-plus"></i> Add Employee
            </button>
            <button id="logoutBtn" class="btn icon-btn">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tBodyEmployees">
            </tbody>
            <tr id="noMatchRow" style="display: none;">
                <td colspan="6" class="text-muted">No matching records found.</td>
            </tr>
        </table>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <button id="empPrevPage" class="btn log-btn">Previous</button>
        <span id="empPageInfo">Page 1 of 1</span>
        <button id="empNextPage" class="btn icon-btn">Next</button>
    </div>
    </div>
</div>

<div id="addModal" class="form">
    <div class="form-content">
        <span class="close" onclick="closeAddModal()">×</span>
        <h2 class="font-weight-bold text-center fw-bold" style="color: rgb(6, 88, 6);">Add New Employee</h2>
        <form id="addEmployeeForm" method="post" action="db/request.php" enctype="multipart/form-data" class="mt-3">
            <div class="form-group">
                <label for="emp_pic">Upload Picture</label>
                <input type="file" class="form-control" name="emp_pic" accept="image/*">
            </div>
            <input type="text" class="form-control mb-2" name="full_name" placeholder="Full Name" required>
            <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
            <input type="password" class="form-control mb-2" name="password" placeholder="Password" >
            <input type="text" class="form-control mb-3" name="position" placeholder="Position" required>
            <input type="submit" name="add_employees" value="Add" class="btn add-btn w-100">
        </form>
    </div>
</div>

<div id="updateModal" class="form">
    <div class="form-content" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <span class="close" onclick="closeupdateModal()">×</span>
        <h3 class="text-primary text-center fw-bold mb-4" style="color: rgb(3, 53, 135);">Update Employee Record</h3>
        
        <div class="row">
            <div class="col-md-7">
                <form method="post" action="db/request.php" id="updateForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="update_id">
                    <input type="hidden" name="user_id" id="update_user_id">
                    
                    <div class="mb-3 text-center">
                        <img id="update_emp_pic_preview" src="pics/default.png" 
                             alt="Profile Picture" width="100" height="100" 
                             style="object-fit:cover; border-radius:50%; border:1px solid #ccc;">
                    </div>

                    <div class="mb-3">
                        <label for="update_emp_pic" class="form-label">Change Picture</label>
                        <input type="file" class="form-control" id="update_emp_pic" name="emp_pic" accept="image/*">
                    </div>
                    
                    <input type="text" class="form-control mb-2" name="full_name" id="update_full_name" placeholder="Full Name" required>
                    <input type="email" class="form-control mb-2" name="email" id="update_email" placeholder="Email" required>
                    <input type="password" class="form-control mb-2" name="password" id="update_password" placeholder="New Password (leave blank to keep old)">
                    <input type="text" class="form-control mb-3" name="position" id="update_position" placeholder="Position" required>
                    
                    <input type="submit" name="update_employees" value="Update Record" class="btn view-btn w-100">
                </form>
            </div>
            <div class="col-md-5">
                <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted">Recent Attendance</h6>
                    </div>
                    <div id="attendancePreview">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0">Loading attendance...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="attendanceSummaryModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title">Attendance Summary</h1>
            <span class="close">&times;</span>
        </div>

        <div class="modal-body">
            <div class="filter-search-container">
                <div class="filter-left">
                    From: <input type="date" id="fromDate">
                    To: <input type="date" id="toDate">
                    <button id="filterBtn" class="btn view-btn">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="search-right">
                    <div class="search-bar">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                        <input type="text" id="employeeSearch" placeholder="Search by employee/date...">
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table id="attendanceTable" class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <button id="attPrevPage" class="btn log-btn">Previous</button>
                <span id="attPageInfo">Page 1 of 1</span>
                <button id="attNextPage" class="btn icon-btn">Next</button>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="editAttendanceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editAttendanceForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Attendance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_attendance_id" name="attendance_id">
          <div class="mb-3">
            <label for="edit_time_in" class="form-label">Time In</label>
            <input type="time" class="form-control" id="edit_time_in" name="time_in">
          </div>
          <div class="mb-3">
            <label for="edit_time_out" class="form-label">Time Out</label>
            <input type="time" class="form-control" id="edit_time_out" name="time_out">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="action-btn">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
