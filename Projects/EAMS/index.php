<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Attendance</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/emp.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-4 p-4 shadow rounded bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold text-dark">Employee Attendance</h1>
        <div class="d-flex gap-2">
            <div class="search-bar">
                <button class="search-btn"><i class="fas fa-search"></i></button>
                <input type="text" id="searchInput" placeholder="Search here...">
            </div>
            <button class="btn log-btn" onclick="openAdminLogin()">
                <i class="fas fa-user-shield"></i> Admin
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
                    <th>Position</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody id="tBodyEmployees">
            </tbody>
            <tr id="noMatchRow" style="display:none;">
                <td colspan="5" class="text-muted">No matching records found.</td>
            </tr>
        </table>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <button id="prevPage" class="btn log-btn">Previous</button>
        <span id="pageInfo">Page 1 of 1</span>
        <button id="nextPage" class="btn icon-btn">Next</button>
    </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-2">
            <img id="modalEmpPic" src="pics/default.png" width="150" height="120">
            <h5 id="modalEmpName" class="mt-2"></h5>
        </div>
        <input type="hidden" id="modalAction">
        <input type="hidden" id="modalEmpId">

    <div class="input-group mb-3">
        <input type="password" class="form-control" id="modalPassword" placeholder="Password" required>
        <button class="btn log-btn" type="button" onclick="togglePassword('modalPassword', this)">
            <i class="fa-regular fa-eye"></i>
        </button>
    </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn icon-btn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn log-btn" onclick="submitPassword()">Confirm</button>
      </div>
    </div>
  </div>
</div>


<div id="adminLoginModal" class="form">
    <div class="form-content">
        <span class="close" onclick="closeAdminLogin()">×</span>
        <h2 class="text-center fw-bold" style="color: rgb(3, 53, 135);"> 
        <i class="fas fa-user-shield"></i> ADMIN</h2>
        <form id="adminLoginForm" method="post" class="mt-3">
            <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
        <div class="input-group mb-3">
            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
            <button class="btn log-btn" type="button" onclick="togglePassword('loginPassword', this)">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>
            <input type="submit" value="Login" class="btn log-btn w-100">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
