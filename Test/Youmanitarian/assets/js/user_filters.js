
$(document).ready(function() {
    // Search User
    $("#searchUser").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("table tr").each(function() {
            let userName = $(this).find("td:first").text().toLowerCase(); 
            if (userName.includes(value) || $(this).find("th").length) { 
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    $(".filter-role-btn").click(function() {
        let role = $(this).data("role");

        if (selectedRoles.includes(role)) {
            selectedRoles = selectedRoles.filter(r => r !== role);
            $(this).removeClass("active");
        } else {
            selectedRoles.push(role); 
            $(this).addClass("active");
        }

        filterUsers();
    });

    // Clear filter
    $("#clearFilter").click(function() {
        selectedRoles = [];
        $(".filter-role-btn").removeClass("active");
        filterUsers();
    });

    // Function to filter users
    function filterUsers() {
        $("table tr").each(function() {
            if ($(this).find("th").length) return; 

            let userRoles = $(this).find("td:eq(1)").text().toLowerCase();
            let hasAllRoles = selectedRoles.every(role => userRoles.includes(role.toLowerCase()));

            $(this).toggle(hasAllRoles || selectedRoles.length === 0);
        });
    }
});

$(document).ready(function() {
    // Change Entries Per Page
    $("#entriesPerPage").change(function() {
        let limit = $(this).val();
        let url = new URL(window.location.href);
        url.searchParams.set('limit', limit);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    });

    // Handle Sorting Clicks
    $("th a").click(function(e) {
        e.preventDefault();
        let url = new URL($(this).attr("href"), window.location.href);
        window.location.href = url.toString();
    });
});