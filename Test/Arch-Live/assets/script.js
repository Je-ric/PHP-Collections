$(document).ready(function () {
    var editor = $("#editor");
    var status = $("#status");

    // Load research content
    function loadContent() {
        $.get("load.php", { id: researchId }, function (data) {
            var response = JSON.parse(data);
            editor.val(response.content);
        });
    }

    // Save content periodically
    function saveContent() {
        $.post("save.php", { id: researchId, content: editor.val() }, function (response) {
            if (response === "success") {
                status.text("Saved at " + new Date().toLocaleTimeString());
            }
        });
    }

    // Track active users
    function trackUsers() {
        $.post("track_users.php", { research_id: researchId, user_id: userId });
    }

    // Auto-load and save every few seconds
    loadContent();
    setInterval(saveContent, 5000); // Save every 5s
    setInterval(loadContent, 3000); // Load every 3s
    setInterval(trackUsers, 5000);  // Track active users
});
