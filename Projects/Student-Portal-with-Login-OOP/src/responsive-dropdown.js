document.addEventListener("DOMContentLoaded", function() {
  var dropdowns = document.querySelectorAll(".dropdown-btn");
  dropdowns.forEach(function(dropdown) {
    dropdown.addEventListener("click", function(event) {
      var content = this.nextElementSibling;
      if (content.style.display === "block") {
        content.style.display = "none";
      } else {
        content.style.display = "block";
        event.stopPropagation(); // Prevent the dropdown from closing immediately after opening
        document.addEventListener("click", closeDropdown); // Listen for clicks outside the dropdown to close it
      }
    });
  });

  function closeDropdown(event) {
    var dropdowns = document.querySelectorAll(".dropdown-content");
    dropdowns.forEach(function(dropdown) {
      if (!dropdown.contains(event.target)) {
        dropdown.style.display = "none";
        document.removeEventListener("click", closeDropdown); // Remove the event listener after closing the dropdown
      }
    });
  }
});