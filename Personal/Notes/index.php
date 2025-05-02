<?php
session_start();

include('config.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql_user_id = "SELECT id FROM users WHERE username='$username'";
$result_user_id = mysqli_query($conn, $sql_user_id);
$row_user_id = mysqli_fetch_assoc($result_user_id);
$user_id = $row_user_id['id'];

// Handle adding a new label
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_label'])) {
    $label_name = mysqli_real_escape_string($conn, $_POST['label_name']);
    $label_color = mysqli_real_escape_string($conn, $_POST['label_color']);

    $sql_add_label = "INSERT INTO labels (name, color) VALUES ('$label_name', '$label_color')";
    if (!mysqli_query($conn, $sql_add_label)) {
        die("Error adding label: " . mysqli_error($conn));
    }

    // Redirect to refresh the page and show the new label
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Update Label
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_label'])) {
    $label_id = $_POST['label_id'];
    $label_name = $_POST['label_name'];
    $label_color = $_POST['label_color'];

    $sql_update_label = "UPDATE labels SET name='$label_name', color='$label_color' WHERE id='$label_id'";
    mysqli_query($conn, $sql_update_label);
}

// Delete Label
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_label'])) {
    $label_id = $_POST['label_id'];

    $sql_delete_label = "DELETE FROM labels WHERE id='$label_id'";
    mysqli_query($conn, $sql_delete_label);
}

// Add Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $label_id = isset($_POST['label_id']) && $_POST['label_id'] ? $_POST['label_id'] : 'NULL';

    // Insert note with label
    $sql_add_note = "INSERT INTO notes (title, content, user_id, label_id) VALUES ('$title', '$content', '$user_id', $label_id)";
    if (!mysqli_query($conn, $sql_add_note)) {
        die("Error adding note: " . mysqli_error($conn));
    }

    // Redirect after adding note
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Delete Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_note'])) {
    $note_id = mysqli_real_escape_string($conn, $_POST['note_id']);

    $sql_delete_note = "DELETE FROM notes WHERE id='$note_id' AND user_id='$user_id'";
    if (!mysqli_query($conn, $sql_delete_note)) {
        die("Error deleting note: " . mysqli_error($conn));
    }

    echo json_encode(['status' => 'success']);
    exit();
}

// Update Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_note'])) {
    $note_id = mysqli_real_escape_string($conn, $_POST['note_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $label_id = isset($_POST['label_id']) && $_POST['label_id'] ? $_POST['label_id'] : 'NULL';

    // Update note with label
    $sql_update_note = "UPDATE notes SET title='$title', content='$content', label_id=$label_id WHERE id='$note_id' AND user_id='$user_id'";
    if (!mysqli_query($conn, $sql_update_note)) {
        die("Error updating note: " . mysqli_error($conn));
    }
}

// Fetch user's notes with optional label filter
$label_filter = isset($_GET['label_id']) ? "AND notes.label_id=" . intval($_GET['label_id']) : "";
$sql_notes = "SELECT notes.*, labels.name as label_name, labels.color as label_color 
              FROM notes 
              LEFT JOIN labels ON notes.label_id = labels.id 
              WHERE notes.user_id='$user_id' $label_filter
              ORDER BY notes.created_at DESC";
$result_notes = mysqli_query($conn, $sql_notes);
if (!$result_notes) {
    die("Error fetching notes: " . mysqli_error($conn));
}

// Fetch all labels
$sql_labels = "SELECT * FROM labels";
$result_labels = mysqli_query($conn, $sql_labels);
if (!$result_labels) {
    die("Error fetching labels: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <style>
        <?php include('index.css'); ?>
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <button id="addNoteBtn" class="add-note-btn">Add New Note</button>
    <button id="addLabelBtn" class="add-label-btn">Add New Label</button>
    <div class="labels">
        <h3>Labels</h3>
        <ul>
            <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">All Notes</a></li>
            <?php 
                mysqli_data_seek($result_labels, 0); 
                while ($row_label = mysqli_fetch_assoc($result_labels)) { ?>
            <li>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?label_id=<?php echo $row_label['id']; ?>" style="color:<?php echo $row_label['color']; ?>"><?php echo $row_label['name']; ?></a>
            <form method="post" class="label-form">
                <input type="hidden" name="label_id" value="<?php echo $row_label['id']; ?>">
            </form>
        </li>
        <?php } ?>
        </ul>
    </div>
    <button id="editLabelBtn" class="edit-label-btn">Edit Labels</button>
    <a class="logout "href="logout.php">Logout</a>
</div>

<!-- Modal for adding new note -->
<div id="noteModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Note</h2>
        <form id="addNoteForm" method="post">
            <input type="text" name="title" placeholder="Title" required><br>
            <textarea name="content" placeholder="Content" required></textarea><br>
            <div class="add-modal-buttons">
                <select name="label_id">
                    <option value="">Select Label</option>
                    <?php 
                    mysqli_data_seek($result_labels, 0); // Reset the result pointer to the start
                    while ($row_label = mysqli_fetch_assoc($result_labels)) { ?>
                        <option value="<?php echo $row_label['id']; ?>"><?php echo $row_label['name']; ?></option>
                    <?php } ?>
                </select>
                <input type="submit" name="add_note" value="Add Note">
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="notes-container">
        <h2>Your Notes</h2>
        <div class="notes-list">
            <?php while ($row_notes = mysqli_fetch_assoc($result_notes)) { ?>
                <div class="note" id="note_<?php echo $row_notes['id']; ?>">
                    <h3><?php echo htmlspecialchars($row_notes['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row_notes['content'])); ?></p>
                    <?php if ($row_notes['label_name']) { ?>
                        <div class="note-label" data-label-id="<?php echo htmlspecialchars($row_notes['label_id']); ?>" style="background-color: <?php echo htmlspecialchars($row_notes['label_color']); ?>;">
                            <?php echo htmlspecialchars($row_notes['label_name']); ?>
                        </div>
                    <?php } ?>
                    <div class="note-footer">
                        <span class="created-at"><?php echo date('F j, Y, g:i a', strtotime($row_notes['created_at'])); ?></span>
                        <button class="edit-btn" data-id="<?php echo $row_notes['id']; ?>">Edit</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal for editing note -->
<div id="noteModalEdit" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Note</h2>
        <form id="editNoteForm" class="edit-form" method="post">
            <input type="hidden" name="note_id" value="">
            <input type="text" name="title" value="" required><br>
            <textarea name="content" required></textarea><br>
            <select name="label_id">
                <option value="">Select Label</option>
                <?php 
                mysqli_data_seek($result_labels, 0); // Reset the result pointer to the start
                while ($row_label = mysqli_fetch_assoc($result_labels)) { ?>
                    <option value="<?php echo $row_label['id']; ?>"><?php echo $row_label['name']; ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="update_note" value="Save">
        </form>
        <div class="note-buttons">
    <form method="post" class="delete-form">
        <input type="hidden" name="note_id" value="">
        <input type="submit" name="delete_note" value="Delete" onclick="return confirm('Are you sure you want to delete this note?');">
    </form>
</div>

    </div>
</div>

<!-- Modal for adding new label -->
<div id="labelModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Label</h2>
        <form id="addLabelForm" method="post">
        <input type="text" name="label_name" placeholder="Label Name (max 12 characters)" required pattern=".{1,12}" title="Maximum 12 characters allowed"><br>
            <input type="color" name="label_color" value="#000000" required><br>
            <input type="submit" name="add_label" value="Add Label">
        </form>
    </div>
</div>

<!-- Modal for editing labels -->
<div id="editLabelModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Labels</h2>
        <div class="label-list">
            <?php
            $sql_labels = "SELECT * FROM labels";
            $result_labels = mysqli_query($conn, $sql_labels);
            if (!$result_labels) {
                die("Error fetching labels: " . mysqli_error($conn));
            }

            while ($row_label = mysqli_fetch_assoc($result_labels)) { ?>
                <div class="label-item">
                    <form method="post" class="edit-label-form">
                        <input type="hidden" name="label_id" value="<?php echo $row_label['id']; ?>">
                        <input type="text" name="label_name" value="<?php echo $row_label['name']; ?>" pattern=".{1,12}" title="Maximum 12 characters allowed">
                        <input type="color" name="label_color" value="<?php echo $row_label['color']; ?>">
                        <input type="submit" name="update_label" value="Save">
                        <input type="submit" name="delete_label" value="Delete" onclick="return confirm('Are you sure you want to delete this label?');">
                    </form>
                </div>
            <?php } ?>
        </div>
        <button id="editLabelDoneBtn" class="done-btn">Done</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
   // Open Add Note Modal
   $("#addNoteBtn").click(function() {
        $("#noteModal").css("display", "block");
    });

    // Open Add Label Modal
    $("#addLabelBtn").click(function() {
        $("#labelModal").css("display", "block");
    });

    // Close Modal
    $(".close").click(function() {
        $("#noteModal").css("display", "none");
        $("#noteModalEdit").css("display", "none");
        $("#labelModal").css("display", "none");
        $("#editLabelModal").css("display", "none"); // Close label modal as well
    });

    // Click outside of modal to close
    $(window).click(function(e) {
        if (e.target.id === "noteModal") {
            $("#noteModal").css("display", "none");
        }
        if (e.target.id === "noteModalEdit") {
            $("#noteModalEdit").css("display", "none");
        }
        if (e.target.id === "labelModal") { // Close label modal as well
            $("#labelModal").css("display", "none");
        }
    });

    $("#editLabelBtn").click(function() {
        $("#editLabelModal").css("display", "block");

        $.get("fetch_labels.php", function(data) {
            $(".label-list").html(data);
        });
    });

    // Edit Note
   // Edit Note
$(".edit-btn").click(function() {
    var note_id = $(this).data("id");
    var title = $("#note_" + note_id + " h3").text();
    var content = $("#note_" + note_id + " p").text();
    var label_id = $("#note_" + note_id + " .note-label").data("label-id");

    // Set note ID in the edit form
    $("#editNoteForm input[name='note_id']").val(note_id);
    $("#editNoteForm input[name='title']").val(title);
    $("#editNoteForm textarea[name='content']").val(content);
    $("#editNoteForm select[name='label_id']").val(label_id);

    // Set note ID in the delete form
    $("#noteModalEdit .delete-form input[name='note_id']").val(note_id);

    $("#noteModalEdit").css("display", "block");
});

    // Update Note
    $(".edit-form").submit(function(e) {
        e.preventDefault();
        var note_id = $(this).find('input[name="note_id"]').val();
        var title = $(this).find('input[name="title"]').val();
        var content = $(this).find('textarea[name="content"]').val();
        var label_id = $(this).find('select[name="label_id"]').val();

        $.post("", { update_note: true, note_id: note_id, title: title, content: content, label_id: label_id }, function() {
            location.reload();
        });
        // Hide the modal after saving
        $("#noteModalEdit").css("display", "none");
    });

    // Delete Note
    $(".delete-form").submit(function(e) {
        e.preventDefault();
        var note_id = $(this).find('input[name="note_id"]').val();

        $.post("", { delete_note: true, note_id: note_id }, function(response) {
            var result = JSON.parse(response);
            if (result.status == 'success') {
                $("#note_" + note_id).remove();
            } else {
                alert("Error deleting note");
            }
        });
        // Hide the modal after deleting
        $("#noteModalEdit").css("display", "none");
    });
});
</script>
</body>
</html>
