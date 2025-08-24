<?php
session_start();
include('../../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];

// Fetch all users except the logged-in user
if (isset($_GET['get_users'])) {
    $stmt = $conn->prepare("
        SELECT users.id, users.name, users.profile_pic, 
               MAX(messages.created_at) AS last_message_time,
               IF(users.last_seen > NOW() - INTERVAL 5 MINUTE, 'Online', 'Offline') AS status
        FROM users
        LEFT JOIN messages 
            ON (users.id = messages.sender_id AND messages.receiver_id = ?) 
            OR (users.id = messages.receiver_id AND messages.sender_id = ?)
        WHERE users.id != ?
        GROUP BY users.id
        ORDER BY last_message_time DESC, users.last_seen DESC, users.name ASC
    ");
    $stmt->bind_param("iii", $logged_in_user_id, $logged_in_user_id, $logged_in_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit();
}


// Handle sending messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $sender_id = $logged_in_user_id;
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    if (!empty($receiver_id) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message_id" => $stmt->insert_id]);
        } else {
            echo json_encode(["status" => "error", "error" => $stmt->error]);
        }
        exit();
    } else {
        echo json_encode(["status" => "error", "error" => "Empty message or receiver"]);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['typing_status'])) {
    $typing_status = $_POST['typing_status'];
    $stmt = $conn->prepare("UPDATE users SET is_typing = ? WHERE id = ?");
    $stmt->bind_param("ii", $typing_status, $logged_in_user_id);
    $stmt->execute();
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['check_typing']) && isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];

    $stmt = $conn->prepare("SELECT is_typing FROM users WHERE id = ?");
    $stmt->bind_param("i", $receiver_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode(["is_typing" => $result["is_typing"]]);
    exit();
}



// Fetch messages between logged-in user and selected user
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];

    $stmt = $conn->prepare("SELECT * FROM messages WHERE 
        (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY created_at ASC");
    $stmt->bind_param("iiii", $logged_in_user_id, $receiver_id, $receiver_id, $logged_in_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.0/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-100 ">
    
<?php include('../../includes/sidebar.php'); ?>
<div class="flex h-screen">
    <!-- Sidebar: Users List -->
    <div class="w-1/4 bg-[#1a2235] p-4 text-white shadow-md flex flex-col overflow-hidden">
    <h2 class="text-xl font-bold mb-2">Chats</h2>
    <div class="sticky top-0 bg-[#1a2235] p-2 z-10">
        <input type="text" id="search" class="input input-bordered w-full text-black" placeholder="Search user...">
    </div>
    <ul id="user-list" class="flex-1 overflow-y-auto space-y-2"></ul>
</div>


<!-- Chat Window -->
<div class="w-3/4 flex flex-col h-full">
    <!-- Chat Header -->
    <div id="chat-header" class="bg-[#1a2235] shadow-md p-4 flex items-center text-white">
        <img id="chat-user-pic" class="w-10 h-10 rounded-full mr-2 hidden" />
        <span id="chat-username" class="text-lg font-bold">Select a user to chat</span>
    </div>

    <!-- Chat Messages -->
    <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-100"></div>

    <!-- Typing Indicator -->
    <div id="typing-indicator" class="text-gray-500 text-sm hidden px-4">Typing...</div>

    <!-- Message Input -->
    <form id="message-form" class="bg-white flex items-center p-4 hidden">
    <input type="hidden" id="receiver_id">
    <textarea id="message" class="textarea textarea-bordered flex-1" placeholder="Type your message"></textarea>
    <button type="submit" class="btn bg-[#ffb51b] text-black hover:bg-yellow-500 ml-2">Send</button>
</form>

</div>

</div>


 <script>
    // let socket = new WebSocket("ws://localhost:8080");

    // socket.onopen = function() {
    //     console.log("Connected to WebSocket server");
    // };

    // socket.onmessage = function(event) {
    //     let data = JSON.parse(event.data);

    //     if (data.type === "message") {
    //         if (data.receiver_id == $("#receiver_id").val() || data.sender_id == $("#receiver_id").val()) {
    //             appendMessage(data.sender_id, data.message);
    //         }
    //     } else if (data.type === "typing") {
    //         showTypingIndicator(data.sender_id, data.is_typing);
    //     }
    // };

    let socket = new WebSocket("ws://localhost:8080");
let websocketActive = false;
let pollingInterval = null;

// WebSocket Opened
socket.onopen = function() {
    console.log("Connected to WebSocket server");
    websocketActive = true;
    if (pollingInterval) clearInterval(pollingInterval); // Stop AJAX polling if WebSocket is active
};

// WebSocket Message Received
socket.onmessage = function(event) {
    let data = JSON.parse(event.data);

    if (data.type === "message") {
        if (data.receiver_id == $("#receiver_id").val() || data.sender_id == $("#receiver_id").val()) {
            appendMessage(data.sender_id, data.message);
        }
    } else if (data.type === "typing") {
        showTypingIndicator(data.sender_id, data.is_typing);
    }
};

// WebSocket Closed (Start AJAX Polling)
socket.onclose = function() {
    console.log("WebSocket disconnected, switching to AJAX polling.");
    websocketActive = false;
    startAjaxPolling();
};


// Function to Poll Messages using AJAX
function startAjaxPolling() {
    pollingInterval = setInterval(function() {
        let receiverId = $("#receiver_id").val();
        if (receiverId) {
            $.get("chat.php?receiver_id=" + receiverId, function(data) {
                let messages = JSON.parse(data);
                $("#chat-box").html("");
                messages.forEach(msg => appendMessage(msg.sender_id, msg.message));
            });
        }
    }, 3000); // Poll every 3 seconds
}

    // function loadUsers() {
    //     $.get("chat.php?get_users=1", function(data) {
    //         let users = JSON.parse(data);
    //         let userList = $("#user-list").html("");

    //         users.forEach(user => {
    //             let statusColor = user.status === "Online" ? "bg-green-500" : "bg-gray-400";

    //             userList.append(`
    //                 <li>
    //                     <button class="btn btn-outline w-full text-left flex items-center space-x-2" 
    //                         onclick="selectUser(${user.id}, '${user.name}')">
    //                         <span class="w-3 h-3 ${statusColor} rounded-full"></span>
    //                         <span>${user.name}</span>
    //                     </button>
    //                 </li>
    //             `);
    //         });
    //     });
    // }
    function loadUsers() {
    $.get("chat.php?get_users=1", function (data) {
        let users = JSON.parse(data);
        let userList = $("#user-list").html("");

        users.forEach(user => {
            let statusColor = user.status === "Online" ? "bg-green-500" : "bg-gray-400";
            let userInitial = user.name.charAt(0).toUpperCase();
            let profilePic = user.profile_pic
                ? `<img src="${user.profile_pic}" class="w-10 h-10 rounded-full border-2 border-[#ffb51b]" />`
                : '';

            // Background color for initials (primary or secondary)
            let randomColor = "#ffb51b"; // Default to secondary color
            let profileElement = profilePic || `
                <div class="w-10 h-10 flex items-center justify-center rounded-full text-[#1a2235] font-bold" 
                     style="background-color: ${randomColor};">
                    ${userInitial}
                </div>
            `;

            userList.append(`
                <li>
                    <button class="w-full text-left flex items-center space-x-3 p-2 rounded-md 
                        bg-[#1a2235] hover:bg-[#141a2b] border-2 border-white text-white transition-all duration-200"
                            onclick="selectUser(${user.id}, '${user.name}')">
                        ${profileElement}
                        <span class="font-semibold">${user.name}</span>
                        <span class="w-3 h-3 ${statusColor} rounded-full"></span>
                    </button>
                </li>
            `);
        });
    });
}


    // function selectUser(id, name) {
    //     $("#receiver_id").val(id);
    //     $("#chat-header").text(`Chatting with ${name}`);
    //     $("#chat-box").html(`<div class="text-center text-gray-500">Loading chat...</div>`);
    //     loadMessages(id);
    // }

    function selectUser(id, name) {
    $("#receiver_id").val(id);
    $("#chat-header").html(`
        <img id="chat-user-pic" class="w-10 h-10 rounded-full mr-2 hidden" />
        <span class="text-lg font-bold">${name}</span>
    `);
    $("#chat-box").html(`<div class="text-center text-gray-500">Loading chat...</div>`);
    $("#message-form").removeClass("hidden"); // Show input when user selected
    loadMessages(id);
}


    function loadMessages(receiverId) {
    $.get("chat.php?receiver_id=" + receiverId, function(data) {
        let messages = JSON.parse(data);
        let chatBox = $("#chat-box").html("");

        if (messages.length === 0) {
            chatBox.html(`
                <div class="text-center text-gray-500 py-4">
                    No conversation yet. Say hello!
                </div>
            `);
        } else {
            messages.forEach(msg => appendMessage(msg.sender_id, msg.message));
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        }
    });
}


    // function appendMessage(senderId, message) {
    //     let isMe = senderId == <?= $logged_in_user_id ?>;
    //     let msgClass = isMe ? "bg-blue-500 text-white self-end" : "bg-gray-300";
    //     let bubbleAlignment = isMe ? "chat-end" : "chat-start";

    //     $("#chat-box").append(`
    //         <div class="chat ${bubbleAlignment}">
    //             <div class="chat-bubble ${msgClass}">${message}</div>
    //         </div>
    //     `);
    //     $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
    // }

    function appendMessage(senderId, message) {
    let isMe = senderId == <?= $logged_in_user_id ?>;
    let msgClass = isMe ? "bg-[#ffb51b] text-white self-end" : "bg-gray-300 text-black";
    let bubbleAlignment = isMe ? "chat-end" : "chat-start";

    $("#chat-box").append(`
        <div class="chat ${bubbleAlignment}">
            <div class="chat-bubble ${msgClass} px-4 py-2 rounded-lg shadow-md">${message}</div>
        </div>
    `);
    $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
}



    function sendMessage() {
    let receiverId = $("#receiver_id").val();
    let messageText = $("#message").val().trim();

    if (!receiverId) {
        alert("Select a user to message.");
        return;
    }
    if (messageText === "") return;

    let messageData = {
        receiver_id: receiverId,
        message: messageText
    };

    // Save to database using AJAX
    $.post("chat.php", messageData, function(response) {
        try {
            let jsonResponse = JSON.parse(response);
            if (jsonResponse.status === "success") {
                let messageId = jsonResponse.message_id;

                // Broadcast the message via WebSocket
                if (websocketActive) {
                    socket.send(JSON.stringify({
                        type: "message",
                        sender_id: <?= $logged_in_user_id ?>,
                        receiver_id: receiverId,
                        message: messageText,
                        message_id: messageId // Include message ID
                    }));
                } else {
                    appendMessage(<?= $logged_in_user_id ?>, messageText);
                }
            } else {
                alert("Message failed to send.");
            }
        } catch (e) {
            console.error("Invalid JSON response:", response);
        }
    });

    $("#message").val("");
}


    // **Fix: Enter key functionality**
    $("#message").keypress(function (e) {
        if (e.which == 13 && !e.shiftKey) { // Enter key without Shift
            e.preventDefault(); // Prevent newline
            sendMessage(); // Send message
        }
    });

    $("#message-form").submit(function (e) {
        e.preventDefault();
        sendMessage();
    });
// ------------------------------
    $("#search").on("input", function() {
        let value = $(this).val().toLowerCase();
        $("#user-list li").each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(value));
        });
    });

    $(document).ready(function() {
        loadUsers();
    });

    // ----------------------------------------------------------------
    let typingTimer;

$("#message").on("input", function () {
    clearTimeout(typingTimer);
    updateTypingStatus(1); // User is typing
    typingTimer = setTimeout(() => updateTypingStatus(0), 2000); // Stop after 2s
});

function updateTypingStatus(isTyping) {
    $.post("chat.php", { typing_status: isTyping });
}

function checkTypingStatus() {
    let receiverId = $("#receiver_id").val();
    if (!receiverId) return;

    $.get("chat.php?check_typing=1&receiver_id=" + receiverId, function (data) {
        let response = JSON.parse(data);
        if (response.is_typing) {
            $("#typing-indicator").text("Typing...").removeClass("hidden");
        } else {
            $("#typing-indicator").addClass("hidden");
        }
    });
}

setInterval(checkTypingStatus, 2000);


</script>

</body>
</html>





