<?php
include 'db_conn.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['research_id'])) {
    die("Unauthorized");
}

$research_id = intval($_GET['research_id']);
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Guest'; // Ensure username is set

// ✅ Handle AJAX request for saving messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);

    if (empty($message)) {
        die("Error: Empty message");
    }

    // ✅ Insert into database with created_at
    $query = "INSERT INTO research_chat (research_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("iis", $research_id, $user_id, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "timestamp" => date('Y-m-d H:i:s')]);
    } else {
        die("Error: " . $stmt->error);
    }

    exit;
}

// ✅ Fetch chat history
$query = "SELECT c.message, c.created_at, u.username 
          FROM research_chat c 
          JOIN users u ON c.user_id = u.id 
          WHERE c.research_id = ? 
          ORDER BY c.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $research_id);
$stmt->execute();
$result = $stmt->get_result();

$chatHistory = [];
while ($row = $result->fetch_assoc()) {
    $chatHistory[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Chat</title>
    <style>
        #chat-container {
            border: 1px solid #ccc;
            width: 300px;
            height: 400px;
            overflow-y: auto;
            padding: 10px;
        }
        #chat-messages { height: 350px; overflow-y: auto; }
    </style>
</head>
<body>

<div id="chat-container">
    <div id="chat-messages">
        <?php foreach ($chatHistory as $msg): ?>
            <div>
                <strong><?= htmlspecialchars($msg['username']) ?>:</strong> 
                <?= htmlspecialchars($msg['message']) ?> 
                <span style="font-size:10px; color:gray;">(<?= $msg['created_at'] ?>)</span>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="text" id="chatInput" placeholder="Type a message..." oninput="sendTypingStatus()">
    <button onclick="sendMessage()">Send</button>
    <p id="chatTypingIndicator"></p>
</div>

<script>
var researchId = <?= $research_id ?>;
var userId = <?= $user_id ?>;
var username = "<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest' ?>";

// ✅ Debug WebSocket Connection
var ws = new WebSocket("ws://localhost:8080");

ws.onopen = function () {
    console.log("Connected to chat WebSocket");
};

ws.onmessage = function (event) {
    let data = JSON.parse(event.data);
    
    if (data.type === "message") {
        displayMessage(data.username, data.message, data.timestamp);
    } else if (data.type === "typing") {
        document.getElementById('chatTypingIndicator').innerText = data.username + " is typing...";
        setTimeout(() => document.getElementById('chatTypingIndicator').innerText = "", 3000);
    }
};

// ✅ Send message via WebSocket & save to database
function sendMessage() {
    let message = document.getElementById('chatInput').value;
    if (!message.trim()) return;

    let timestamp = new Date().toISOString();

    let payload = JSON.stringify({
        type: "message",
        research_id: researchId,
        user_id: userId,
        username: username,
        message: message,
        timestamp: timestamp
    });

    ws.send(payload);
    document.getElementById('chatInput').value = '';

    saveChatToDatabase(message);
}

// ✅ Save message via AJAX
function saveChatToDatabase(message) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "chat.php?research_id=" + researchId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Server Response:", xhr.responseText);

            if (xhr.status === 200) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    displayMessage(username, message, response.timestamp);
                } catch (error) {
                    console.error("Invalid JSON response:", xhr.responseText);
                }
            } else {
                console.error("Error saving message:", xhr.responseText);
            }
        }
    };

    xhr.send("message=" + encodeURIComponent(message));
}

// ✅ Display message in chat window
function displayMessage(username, message, timestamp) {
    let chatBox = document.getElementById('chat-messages');
    let msgDiv = document.createElement('div');

    // Convert timestamp to readable format
    let date = timestamp ? new Date(timestamp).toLocaleString() : "Just now";

    msgDiv.innerHTML = `<strong>${username}:</strong> ${message} <span style="font-size:10px; color:gray;">(${date})</span>`;
    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}
</script>

</body>
</html>
