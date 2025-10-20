window.openAddModal = function() { $("#addModal").show(); }
window.closeAddModal = function() { $("#addModal").hide(); }

window.openUpdateModal = function(data) {
    $("#update_id").val(data.id);
    $("#update_user_id").val(data.user_id);
    $("#update_full_name").val(data.full_name);
    $("#update_email").val(data.email);
    $("#update_position").val(data.position);
    $("#update_password").val("");
    $("#update_emp_pic_preview").attr("src", data.emp_pic ? "pics/" + data.emp_pic : "pics/default.png");
    $("#update_emp_pic").val("");
    loadEmployeeAttendancePreview(data.id);
    $("#updateModal").show();
}
window.closeupdateModal = function() { $("#updateModal").hide(); }

window.deleteEmployees = function(empId, userId, fullName) {
    if (confirm(`Are you sure you want to delete Employee: ${fullName}? All attendance records will also be deleted.`)) {
        $.ajax({
            url: "db/request.php",
            method: "POST",
            data: { delete_employees: true, id: empId, user_id: userId },
            dataType: "json",
            success: function(res) {
                alert(res.message);
                if (res.success) window.loadEmployees($("#searchInput").val().trim());
            },
            error: function(xhr) { console.error(xhr.responseText); alert("AJAX error!"); }
        });
    }
}
window.loadEmployeeAttendancePreview = function(empId) {
            $.ajax({
            url: "db/request.php",
            method: "POST", 
            data: { 
                get_employee_attendance_preview: true, 
                emp_id: empId,
                limit: 10
            },
            dataType: "json",
            success: function(records) {
                renderAttendancePreview(records);
            },
            error: function() {
                $("#attendancePreview").html('<p class="text-muted">Unable to load attendance records</p>');
            }
        });
    }

    function renderAttendancePreview(records) {
        if (records.length === 0) {
            $("#attendancePreview").html('<p class="text-muted">No attendance records found</p>');
            return;
        }
        
        let html = `
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        records.forEach(record => {
            html += `
                <tr>
                    <td>${record.date}</td>
                    <td>${record.time_in_fmt || '-'}</td>
                    <td>${record.time_out_fmt || '-'}</td>
                </tr>`;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-2">
                <button type="button" class="btn view-btn" 
                        onclick="showFullAttendanceHistory()">
                    View Full Attendance History
                </button>
            </div>`;
        
        $("#attendancePreview").html(html);
    }

window.showFullAttendanceHistory = function() {
        $("#updateModal").hide();
        $("#attendanceSummaryModal").show();
        
        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);
        
        $("#fromDate").val(thirtyDaysAgo.toISOString().split('T')[0]);
        $("#toDate").val(today.toISOString().split('T')[0]);
        $("#employeeSearch").val("");
    }
$(function() {

    let employeeRecords = [];
    let empCurrentPage = 1;
    const empRowsPerPage = 7;

    function renderEmployeeTable(filteredRecords = null) {
        const data = filteredRecords || employeeRecords;
        const start = (empCurrentPage - 1) * empRowsPerPage;
        const end = start + empRowsPerPage;
        const pageData = data.slice(start, end);

        let tBody = "";
        if (pageData.length === 0) {
            tBody = `<tr><td colspan="6" style="text-align:center;">No records found.</td></tr>`;
        } else {
            pageData.forEach((data, idx) => {
                tBody += `<tr>
                    <td>${start + idx + 1}</td>
                    <td><img src="pics/${data.emp_pic || 'default.png'}" width="80" height="50"></td>
                    <td>${data.full_name}</td>
                    <td>${data.email}</td>
                    <td>${data.position}</td>
                    <td>
                        <button class="action-btn edit" onclick='openUpdateModal(${JSON.stringify(data)})'>
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn" onclick='deleteEmployees(${data.id}, ${data.user_id}, "${data.full_name.replace(/"/g, '&quot;')}")'>
                            Delete
                        </button>
                    </td>
                </tr>`;
            });
        }

        $("#tBodyEmployees").html(tBody);
        const totalPages = Math.ceil(data.length / empRowsPerPage);
        $("#empPageInfo").text(`Page ${empCurrentPage} of ${totalPages || 1}`);
    }


    window.loadEmployees = function(searchQuery = "") {
        $.ajax({
            url: "db/request.php",
            method: "POST",
            data: { get_employees: true },
            dataType: "json",
            success: function(datas) {
                employeeRecords = datas;
                empCurrentPage = 1;
                if (searchQuery) {
                    const filtered = employeeRecords.filter(r => 
                        (`${r.full_name} ${r.position}`).toLowerCase().includes(searchQuery.toLowerCase())
                    );
                    renderEmployeeTable(filtered);
                } else {
                    renderEmployeeTable();
                }
            },
            error: function(err) { console.error(err); alert("Error fetching employees."); }
        });
    };

    loadEmployees();

    $(document).on("input", "#searchInput", function() {
        const query = $(this).val().trim();
        empCurrentPage = 1;
        loadEmployees(query);
    });

    $("#empPrevPage").click(function() {
        if (empCurrentPage > 1) {
            empCurrentPage--;
            renderEmployeeTable();
        }
    });
    $("#empNextPage").click(function() {
        const totalPages = Math.ceil(employeeRecords.length / empRowsPerPage);
        if (empCurrentPage < totalPages) {
            empCurrentPage++;
            renderEmployeeTable();
        }
    });


    function ajaxFormSubmit(formId, appendKey, modalCloseFn) {
        $(formId).on("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append(appendKey, true);
            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    alert(res.message);
                    if (res.success) {
                        $(formId)[0].reset();
                        modalCloseFn();
                        window.loadEmployees($("#searchInput").val().trim());
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert("Something went wrong! Possibly duplicate email.");
                }
            });
        });
    }

    ajaxFormSubmit("#addEmployeeForm", "add_employees", window.closeAddModal);
    ajaxFormSubmit("#updateForm", "update_employees", window.closeupdateModal);

    $("#logoutBtn").click(function() {
        if (confirm("Are you sure you want to logout?")) {
            $.post("db/log.php")
                .done(() => window.location.href = "index.php")
                .fail(() => alert("Logout failed. Try again."));
        }
    });

    $("#attendanceSummaryBtn").click(function() {
        $("#attendanceSummaryModal").show();
    });

    $("#attendanceSummaryModal .close").click(() => $("#attendanceSummaryModal").hide());
    $(window).click(function(e) {
        if ($(e.target).is("#attendanceSummaryModal")) $("#attendanceSummaryModal").hide();
    });

let attendanceRecords = [];
let attFilteredRecords = [];
let attCurrentPage = 1;
const attRowsPerPage = 5;

$(document).on('input', '#employeeSearch', function() {
    const query = $(this).val().toLowerCase().trim();

    if (query === "") {
        attFilteredRecords = []; 
    } else {
        attFilteredRecords = attendanceRecords.filter(r =>
            r.full_name.toLowerCase().includes(query) ||
            (r.date && r.date.toLowerCase().includes(query))
        );
    }
    attCurrentPage = 1;
    renderAttendanceTable();
});

function renderAttendanceTable() {
    const query = $("#employeeSearch").val().trim();
    const data = query === "" 
        ? attendanceRecords 
        : attFilteredRecords;

    const start = (attCurrentPage - 1) * attRowsPerPage;
    const end = start + attRowsPerPage;
    const pageData = data.slice(start, end);

    let tBody = "";
    if (pageData.length === 0) {
        tBody = `<tr><td colspan="7" style="text-align:center;">No records found.</td></tr>`;
    } else {
        pageData.forEach((row, idx) => {
            tBody += `
              <tr>
                <td>${start + idx + 1}</td>
                <td><img src="${row.profile || 'default.png'}" width="80" height="50"></td>             
                <td>${row.full_name}</td>
                <td>${row.date}</td>
                <td>${row.time_in_fmt || ''}</td>
                <td>${row.time_out_fmt || ''}</td>
                <td>
                  <button class="action-btn" onclick="openEditAttendanceModal(${row.id}, '${row.time_in || ''}', '${row.time_out || ''}')">
                  <i class="fas fa-pen"></i></button>
                </td>
              </tr>
            `;
        });
    }

    $("#attendanceTable tbody").html(tBody);

    const totalPages = Math.ceil(data.length / attRowsPerPage) || 1;
    $("#attPageInfo").text(`Page ${attCurrentPage} of ${totalPages}`);
}
$("#filterBtn").click(function() {
    const from = $("#fromDate").val(), to = $("#toDate").val();
    if (!from || !to) {
        alert("Please select both dates.");
        return;
    }
    $.post("db/request.php", { attendance_summary: true, from: from, to: to }, function(res) {
        attendanceRecords = res;
        attFilteredRecords = []; 
        attCurrentPage = 1;
        renderAttendanceTable();
    }, "json").fail((xhr, status, error) => {
        alert("Something went wrong while fetching summary.");
    });
});

$("#attPrevPage").click(function() {
    if (attCurrentPage > 1) {
        attCurrentPage--;
        renderAttendanceTable();
    }
});

$("#attNextPage").click(function() {
    const data = attFilteredRecords.length ? attFilteredRecords : attendanceRecords;
    const totalPages = Math.ceil(data.length / attRowsPerPage);
    if (attCurrentPage < totalPages) {
        attCurrentPage++;
        renderAttendanceTable();
    }
}); 

window.openEditAttendanceModal = function(id, timeIn, timeOut) {
  function toHHMM(val) {
    if (!val) return "";
    return val.split(":").slice(0,2).join(":");
  }
  $("#edit_attendance_id").val(id);
  $("#edit_time_in").val(toHHMM(timeIn));
  $("#edit_time_out").val(toHHMM(timeOut));
  $("#editAttendanceModal").modal("show");
};

$("#editAttendanceForm").submit(function(e) {
  e.preventDefault();
  const id = $("#edit_attendance_id").val();
  let time_in = $("#edit_time_in").val();
  let time_out = $("#edit_time_out").val();

  if (time_in && time_in.length === 5) time_in += ":00";
  if (time_out && time_out.length === 5) time_out += ":00";

  let data = { update_attendance: true, id: id };
  if (time_in) data.time_in = time_in;
  if (time_out) data.time_out = time_out;

  $.ajax({
    url: "db/request.php",
    method: "POST",
    data: data,
    dataType: "json",
    success: function(res) {
      alert(res.message);
      $("#editAttendanceModal").modal("hide");
      const from = $("#fromDate").val();
      const to = $("#toDate").val();
      if (from && to) {
          $.post("db/request.php", { attendance_summary: true, from: from, to: to }, function(res) {
              attendanceRecords = res;
              attFilteredRecords = [];
              attCurrentPage = 1;
              renderAttendanceTable();
          }, "json").fail(() => alert("Something went wrong while fetching summary."));
      }
    },
    error: function(xhr, status, error) {
      alert("Failed to update attendance.");
    }
  });
});

});
