<?php 
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Fetch username from session
$research_id = null;
$research_title = "Untitled Research";
$research_content = "";

// Check if creating a new research project
if (isset($_GET['new']) && $_GET['new'] == "true") {
    // Insert a new research project (TEMPORARY DRAFT)
    $insertQuery = "INSERT INTO research (title, content, status, year, author_id) VALUES ('Untitled Research', '', 'draft', YEAR(NOW()), ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $research_id = $stmt->insert_id; // Get newly created research ID

        // Give the creator full edit access
        $shareQuery = "INSERT INTO research_sharing (research_id, user_id, access_level) VALUES (?, ?, 'edit')";
        $stmt = $conn->prepare($shareQuery);
        $stmt->bind_param("ii", $research_id, $user_id);
        $stmt->execute();
    } else {
        die("Error creating new research.");
    }
} 
// If editing an existing project
elseif (isset($_GET['research_id'])) {
    $research_id = intval($_GET['research_id']);

    // Check if user has edit access
    $accessQuery = "SELECT access_level FROM research_sharing WHERE research_id = ? AND user_id = ?";
    $stmt = $conn->prepare($accessQuery);
    $stmt->bind_param("ii", $research_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($access_level);
    $stmt->fetch();

    if ($stmt->num_rows === 0 || $access_level !== 'edit') {
        die("You do not have permission to edit this research.");
    }

    // Fetch existing research data
    $query = "SELECT title, content FROM research WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $research_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $research = $result->fetch_assoc();

    if ($research) {
        $research_title = $research['title'];
        $research_content = $research['content'];
    } else {
        die("Research not found.");
    }
}

// Update user's online status
$updateOnlineStatus = "REPLACE INTO active_users (research_id, user_id, last_active) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($updateOnlineStatus);
$stmt->bind_param("ii", $research_id, $user_id);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Real-Time Research Editor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="editor.css">
</head>
<body>

<h2>Editing: <span id="researchTitle"><?= htmlspecialchars($research_title) ?></span></h2>
<h2>Editing: <input type="text" id="researchTitleInput" value="<?= htmlspecialchars($research_title) ?>" oninput="markUnsaved()"></h2>

<?php #include 'toolbar.php'; ?>
<!-- <div id="editor-container">
    <div contenteditable="true" id="editor" class="page"><?= htmlspecialchars($research_content) ?></div>
</div> -->

<textarea id="editor" rows="10" cols="80"><?= htmlspecialchars($research_content) ?></textarea>
<p id="status">Saving...</p>
<button onclick="saveContent()">Save Changes</button>

<p id="typingIndicator"></p>


<h3>Share Research</h3>
<input type="text" id="shareUser" placeholder="Enter username or email">
<select id="accessLevel">
    <option value="edit">Can Edit</option>
    <option value="view">Can View</option>
</select>
<button onclick="shareResearch()">Share</button>
<p id="shareStatus"></p>



<!-- Online Users Section -->
<h3>Active Users</h3>
<ul id="activeUsers"></ul>

<h3>All Users with Access</h3>
<ul id="allUsersWithAccess"></ul>

<?php include "chat.php"; ?>
<script>
var researchId = <?= $research_id ?>;
var lastUpdated = "1970-01-01 00:00:00";
var lastContent = document.getElementById('editor').value;
var isTyping = false; // Flag to prevent fetching when user is typing
var typingTimeout;

// Monitor typing activity
document.getElementById('editor').addEventListener('input', function () {
    isTyping = true; // Stop fetching while typing
    sendTypingStatus('typing');

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(function () {
        isTyping = false; // Resume fetching after inactivity
        sendTypingStatus('idle');
    }, 3000);
});

// Save content if changed
function syncContent() {
    let content = document.getElementById('editor').value;

    if (content !== lastContent) {
        fetch('save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `research_id=${researchId}&content=${encodeURIComponent(content)}&last_updated=${lastUpdated}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                lastUpdated = data.last_updated;
                lastContent = content;
            } else if (data.status === "conflict") {
                console.warn("Conflict detected! Fetching latest content...");
                fetchUpdatedContent();
            }
        })
        .catch(error => console.error('Error saving content:', error));
    }
}

// Fetch content only if updated and user is not typing
function fetchUpdatedContent() {
    if (isTyping) return; // Skip fetching while user is typing

    fetch('fetch_content.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}&last_fetch_time=${lastUpdated}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.content !== null && data.content !== lastContent) {
            document.getElementById('editor').value = data.content;
            lastContent = data.content;
            lastUpdated = data.last_updated;
        }
    })
    .catch(error => console.error('Error fetching content:', error));
}

// Interval for saving and fetching
setInterval(syncContent, 2000);  // Save content every 2 seconds
setInterval(fetchUpdatedContent, 2000); // Fetch updates every 2 seconds




// ------------------------------------------------------------------------------------------------
function sendTypingStatus(status) {
    fetch('update_typing_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}&status=${status}`
    }).catch(error => console.error('Error updating typing status:', error));
}

// Fetch typing users every second
setInterval(fetchTypingUsers, 1000);

// ------------------------------------------------------------------------------------------------
function fetchTypingUsers() {
    fetch('fetch_typing_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}`
    })
    .then(response => response.json())
    .then(data => {
        let typingIndicator = document.getElementById('typingIndicator');
        if (data.typing_users.length > 0) {
            typingIndicator.innerText = data.typing_users.join(", ") + " is typing...";
        } else {
            typingIndicator.innerText = "";
        }
    })
    .catch(error => console.error('Error fetching typing users:', error));
}


// ------------------------------------------------------------------------------------------------
var researchId = <?= $research_id ?>;
var userId = <?= $user_id ?>;

function fetchOnlineUsers() {
    fetch('track_online_users.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}`
    })
    .then(response => response.json())
    .then(data => {
        console.log("Online Users Data:", data); // ✅ Debugging: See what data is returned

        let activeList = document.getElementById('activeUsers');
        let accessList = document.getElementById('allUsersWithAccess');

        if (!activeList || !accessList) {
            console.error("Error: Missing activeUsers or allUsersWithAccess elements.");
            return;
        }

        // Clear previous lists
        activeList.innerHTML = '';
        accessList.innerHTML = '';

        // ✅ Display Active Users
        if (data.active_users.length > 0) {
            data.active_users.forEach(username => {
                let listItem = document.createElement('li');
                listItem.textContent = username;
                activeList.appendChild(listItem);
            });
        } else {
            activeList.innerHTML = '<li>No active users</li>';
        }

        // ✅ Display All Users with Access (excluding active users)
        if (data.inactive_users.length > 0) {
            data.inactive_users.forEach(username => {
                let listItem = document.createElement('li');
                listItem.textContent = username;
                accessList.appendChild(listItem);
            });
        } else {
            accessList.innerHTML = '<li>No inactive users</li>';
        }
    })
    .catch(error => console.error('Error fetching users:', error));
}

// ✅ Call every 10 seconds
setInterval(fetchOnlineUsers, 10000);
fetchOnlineUsers(); // Initial call


// ------------------------------------------------------------------------------------------------
// Auto-save content
function saveContent() {
    let title = document.getElementById('researchTitleInput').value;
    let content = document.getElementById('editor').value;
    
    fetch('save_research.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}&title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`
    }).then(response => response.text())
      .then(data => document.getElementById('status').innerText = 'Saved!')
      .catch(error => console.error('Error saving:', error));
}

function markUnsaved() {
    document.getElementById('status').innerText = 'Unsaved changes...';
}

setInterval(saveContent, 5000); // Auto-save every 5 seconds
// ------------------------------------------------------------------------------------------------
function shareResearch() {
    let usernameOrEmail = document.getElementById('shareUser').value;
    let accessLevel = document.getElementById('accessLevel').value;

    fetch('share_research.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `research_id=${researchId}&username=${encodeURIComponent(usernameOrEmail)}&access_level=${accessLevel}`
    }).then(response => response.text())
      .then(data => document.getElementById('shareStatus').innerText = data)
      .catch(error => console.error('Error sharing:', error));
}

</script>

<script>
var ws = new WebSocket("ws://localhost:8080");

// Show connection status
ws.onopen = function () {
    console.log("Connected to WebSocket server");
};

// Handle incoming messages
ws.onmessage = function (event) {
    let data = JSON.parse(event.data);

    // Update typing indicator
    if (data.type === "typing") {
        document.getElementById('typingIndicator').innerText = data.username + " is typing...";
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            document.getElementById('typingIndicator').innerText = "";
        }, 3000);
    }

    // Update editor if user is NOT typing
    if (data.type === "content" && !isTyping) {
        document.getElementById('editor').value = data.content;
    }
};

// Send typing status on input
document.getElementById('editor').addEventListener('input', function () {
    isTyping = true;
    let content = this.value;
    let message = JSON.stringify({ type: "content", content: content });
    ws.send(message);

    sendTypingStatus(); // Notify other users
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        isTyping = false;
    }, 3000);
});

// Notify others when typing
function sendTypingStatus() {
    let message = JSON.stringify({ type: "typing", username: "<?= $username ?>" });
    ws.send(message);
}

</script>
</body>
</html>
