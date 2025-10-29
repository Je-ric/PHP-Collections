let employeesData = []; 
let currentPage = 1;
const recordsPerPage = 7;

function loadEmployees(searchQuery = "") {
    $.ajax({
        url: "db/request.php",
        method: "POST",
        data: { get_employees: true },
        dataType: "json",
        success: function(datas) {
            employeesData = datas.filter(emp => {
                let fullText = `${emp.full_name} ${emp.position}`.toLowerCase();
                return !searchQuery || fullText.includes(searchQuery.toLowerCase());
            });
            currentPage = 1; 
            renderTable();
        },
    error: function(xhr, status, error) {
        console.error("Load Employees Error:", {
            status: status,
            error: error,
            response: xhr.responseText
        });
    }
    });
}

function renderTable() {
    let start = (currentPage - 1) * recordsPerPage;
    let end = start + recordsPerPage;
    let pageData = employeesData.slice(start, end);

    let tBody = "";
    pageData.forEach((data, index) => {
        let timeInDisabled = data.time_in_done ? "disabled" : "";
        let timeOutDisabled = (!data.time_in_done || data.time_out_done) ? "disabled" : "";
        tBody += `<tr>
            <td>${start + index + 1}</td>
            <td><img src="${data.emp_pic ? 'pics/' + data.emp_pic : 'pics/default.png'}" width="80" height="50"></td>
            <td>${data.full_name}</td>
            <td>${data.position}</td>
            <td>
                <button class="btn timein-btn" onclick="askPassword('in', ${data.id})" ${timeInDisabled}>Time In</button>
                <button class="btn delete-btn" onclick="askPassword('out', ${data.id})" ${timeOutDisabled}>Time Out</button>
            </td>
        </tr>`;
    });

    $("#tBodyEmployees").html(tBody);
    $("#noMatchRow").toggle(pageData.length === 0);

    let totalPages = Math.ceil(employeesData.length / recordsPerPage) || 1;
    $("#pageInfo").text(`Page ${currentPage} of ${totalPages}`);

    $("#prevPage").prop("disabled", currentPage === 1);
    $("#nextPage").prop("disabled", currentPage === totalPages);
}

$(document).ready(function() {
    $("#prevPage").click(function() {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    });

    $("#nextPage").click(function() {
        let totalPages = Math.ceil(employeesData.length / recordsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
        }
    });

    $("#searchInput").on("input", function() {
        loadEmployees($(this).val().trim());
    });

});

function askPassword(action, id) {
    $("#modalAction").val(action);
    $("#modalEmpId").val(id);
    $("#modalPassword").val("");
    $("#passwordModalTitle").text(action === "in" ? "Enter Password to Time In" : "Enter Password to Time Out");

    $.ajax({
        url: "db/request.php",
        method: "POST",
        data: { get_employee: true, id: id },
        dataType: "json",
        success: function(emp) {
            $("#modalEmpPic").attr("src", emp.emp_pic ? emp.emp_pic : "pics/default.png");
            $("#modalEmpName").text(emp.full_name || "");
        },
        error: function() {
            $("#modalEmpPic").attr("src", "pics/default.png");
            $("#modalEmpName").text("");
        }
    });

    new bootstrap.Modal(document.getElementById("passwordModal")).show();
    $("#modalPassword").focus();
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector("i");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

function submitPassword() {
    const action = $("#modalAction").val();
    const id = $("#modalEmpId").val();
    const password = $("#modalPassword").val().trim();

    if (!password) return alert("Password is required!");

    action === "in" ? doTimeIn(id, password) : doTimeOut(id, password);
    bootstrap.Modal.getInstance(document.getElementById("passwordModal")).hide();
}

$(document).on("keydown", "#modalPassword", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        submitPassword();
    }
});

function doTimeIn(id, password) {
    $.post("db/request.php", { time_in: true, id, password }, function(res) {
        alert(res.message);
        loadEmployees($("#searchInput").val().trim());
    }, "json").fail(function(xhr, status, error) {
        console.error("Time In Error:", status, error, xhr.responseText);
        alert("Failed to record Time In.");
    });
}

function doTimeOut(id, password) {
    $.post("db/request.php", { time_out: true, id, password }, function(res) {
        alert(res.message);
        loadEmployees($("#searchInput").val().trim());
    }, "json").fail(function(xhr, status, error) {
        console.error("Time Out Error:", status, error, xhr.responseText);
        alert("Failed to record Time Out.");
    });
}

function openAdminLogin() { $("#adminLoginModal").show(); }
function closeAdminLogin() { $("#adminLoginModal").hide(); }

$(function() {
    loadEmployees();

    $("#searchInput").on("input", function() {
        loadEmployees($(this).val().trim());
    });

    $("#adminLoginForm").on("submit", function(e) {
        e.preventDefault();
        $.post("db/request.php", {
            email: $('input[name="email"]').val(),
            password: $('input[name="password"]').val(),
            admin_login: true
        }, function(res) {
            if (res.success) {
                closeAdminLogin();
                window.location.href = "admin.php";
            } else {
                alert(res.message);
            }
        }, "json").fail(() => alert("Login request failed!"));
    });
});
