<?php
session_start();
include('../../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all content requests
$query_requests = "SELECT cr.*, u.name AS requested_by FROM content_requests cr 
                   JOIN users u ON cr.requested_by = u.id ORDER BY cr.created_at DESC";
$result_requests = $conn->query($query_requests);

// Fetch all published content
$query_content = "SELECT * FROM content ORDER BY created_at DESC";
$result_content = $conn->query($query_content);

// Handle Content Archiving
if (isset($_GET['archive_content'])) {
    $content_id = $_GET['archive_content'];
    $archive_query = "UPDATE content SET status = 'archived' WHERE id = ?";
    $stmt = $conn->prepare($archive_query);
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $_SESSION['message'] = "Content archived successfully!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


// Handle Status Update (AJAX)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['status'])) {
    $request_id = $_POST['id'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE content_requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $request_id);
    $success = $stmt->execute();

    echo json_encode(["success" => $success]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Requests & Published Content</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
</head>
<body class="bg-gray-100">

<?php include('../../includes/sidebar.php'); ?>

<!-- Main Container -->
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Content Requests</h2>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="table table-zebra w-full">
            <thead class="text-gray-600 bg-gray-200">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Requested By</th>
                    <th class="p-3">Created At</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_requests->fetch_assoc()): ?>
                    <tr>
                        <td class="p-3 text-gray-800"><?= htmlspecialchars($row['title']) ?></td>
                        <td class="p-3 text-gray-600 status-<?= $row['id'] ?>"><?= htmlspecialchars($row['status']) ?></td>
                        <td class="p-3 text-gray-600"><?= htmlspecialchars($row['requested_by']) ?></td>
                        <td class="p-3 text-gray-600"><?= date("F j, Y (g:i a)", strtotime($row['created_at'])) ?></td>
                        <td class="p-3 flex gap-2">
                            <label for="view-request-modal-<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</label>
                            <?php if ($row['status'] === 'in_progress'): ?>
                                <a href="content_editor.php?request_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

   <!-- Published Content Table -->
<h2 class="text-3xl font-bold mt-10 mb-6 text-gray-800">Published Content</h2>
<div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="table table-zebra w-full">
        <thead class="text-gray-600 bg-gray-200">
            <tr>
                <th class="p-3">Title</th>
                <th class="p-3">Status</th>
                <th class="p-3">Created At</th>
                <th class="p-3">Updated At</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_content->fetch_assoc()): ?>
                <tr>
                    <td class="p-3 text-gray-800"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="p-3 text-gray-600"><?= htmlspecialchars($row['status']) ?></td>
                    <td class="p-3 text-gray-600"><?= date("F j, Y (g:i a)", strtotime($row['created_at'])) ?></td>
                    <td class="p-3 text-gray-600"><?= date("F j, Y (g:i a)", strtotime($row['updated_at'])) ?></td>
                    <td class="p-3">
                        <a href="content_editor.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                        <?php if ($row['status'] !== 'archived'): ?>
                            <a href="?archive_content=<?= $row['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to archive this content?');">Archive</a>
                        <?php else: ?>
                            <span class="text-gray-500">Archived</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div>

<!-- Modals for Content Requests -->
<?php
$result_requests->data_seek(0);
while ($request_row = $result_requests->fetch_assoc()):
    $request_id = $request_row['id'];
?>
    <input type="checkbox" id="view-request-modal-<?= $request_id ?>" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h2 class="text-2xl font-bold mb-4">Content Request: <?= htmlspecialchars($request_row['title']) ?></h2>
            <p><strong>Status:</strong> <span class="status-<?= $request_id ?>"><?= htmlspecialchars($request_row['status']) ?></span></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($request_row['description'])) ?></p>
            <p><strong>Requested By:</strong> <?= htmlspecialchars($request_row['requested_by']) ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($request_row['notes']) ?></p>
            <p><strong>Created At:</strong> <?= date("F j, Y (g:i a)", strtotime($request_row['created_at'])) ?></p>
            <div class="modal-action">
                <?php if ($request_row['status'] !== 'completed'): ?>
                    <!-- <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $request_id ?>, 'pending')">Pending</button>  -->
                    <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $request_id ?>, 'in_progress')">In Progress</button>
                    <!-- <button class="btn btn-sm btn-primary" onclick="updateStatus(<?= $request_id ?>, 'completed')">Completed</button> -->
                <?php else: ?>
                    <span class="text-green-600 font-bold">This request is already completed.</span>
                <?php endif; ?>
                <label for="view-request-modal-<?= $request_id ?>" class="btn btn-sm btn-secondary">Close</label>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<!-- JavaScript -->
<script>
function updateStatus(requestId, newStatus) {
    fetch('content.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${requestId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll(`.status-${requestId}`).forEach(el => el.textContent = newStatus);
            alert("Status updated successfully!");
        } else {
            alert('Failed to update status');
        }
    });
}
</script>

</body>
</html>
