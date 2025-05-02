
// Accounts - Attendance (Filter)
document.addEventListener('DOMContentLoaded', function() {
    const roleFilter = document.getElementById('role_filter');
    const statusFilter = document.getElementById('status_filter');
    const attendanceRows = document.querySelectorAll('.attendance-row');

    function filterRows() {
        const selectedRole = roleFilter.value;
        const selectedStatus = statusFilter.value;
        
        let visibleIndex = 1; 

        attendanceRows.forEach(row => {
            const userRole = row.getAttribute('data-role');
            const userStatus = row.getAttribute('data-status');

            const matchesRole = !selectedRole || userRole === selectedRole;
            const matchesStatus = !selectedStatus || userStatus === selectedStatus;

            if (matchesRole && matchesStatus) {
                row.style.display = ''; 
                row.querySelector('td').textContent = visibleIndex++; 
            } else {
                row.style.display = 'none'; 
            }
        });
    }

    roleFilter.addEventListener('change', filterRows);
    statusFilter.addEventListener('change', filterRows);

    filterRows();
});


// Search


{/* <form id="filterForm">
<select name="role_filter" id="role_filter">
    <option value="">Select Roles to filter</option>
    <option value="admin">Admin</option>
    <option value="employee">Employee</option>
</select>
<select name="status_filter" id="status_filter">
    <option value="">Select Status to filter</option>
    <option value="active" selected>Active</option>
    <option value="inactive">Inactive</option>
</select>
<input type="text" id="userSearch" onkeyup="searchUsersAtt()" placeholder="Search by name...">
</form> */}


    
function searchUsers() {
    const input = document.getElementById('userSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#userTableBody tr');

    rows.forEach(row => {
        const nameCell = row.querySelector('td.user-details .user-info span').textContent.toLowerCase();
        const usernameCell = row.querySelector('td.user-details .user-info small').textContent.toLowerCase();

        if (nameCell.includes(input) || usernameCell.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}


{/* <form id="filterForm">
    <select name="role_filter" id="role_filter">
        <option value="">Select Roles to filter</option>
        <option value="admin">Admin</option>
        <option value="employee">Employee</option>
    </select>
    <select name="status_filter" id="status_filter">
        <option value="">Select Status to filter</option>
        <option value="active" selected>Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <input type="text" id="userSearch" onkeyup="searchUsersAtt()" placeholder="Search by name...">
</form> */}
function searchUsersAtt() {
    const input = document.getElementById('userSearch').value.toLowerCase();
    const roleFilter = document.getElementById('role_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const rows = document.querySelectorAll('#attendanceTableBody tr');

    rows.forEach(row => {
        const nameCell = row.querySelector('td:nth-child(2) .user-attendance-name').textContent.toLowerCase();
        const roleCell = row.getAttribute('data-role').toLowerCase();
        const statusCell = row.getAttribute('data-status').toLowerCase();

        const matchesSearch = nameCell.includes(input);

        const matchesRole = roleFilter ? roleCell === roleFilter : true;
        const matchesStatus = statusFilter ? statusCell === statusFilter : true;

        if (matchesSearch && matchesRole && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
