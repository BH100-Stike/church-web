<?php
// 1. DEBUGGING - Confirm script is executing
echo "Script started<br>";

// 2. Use absolute path with verification
$connect_path = __DIR__ . '/../../includes/connect.php';
if (!file_exists($connect_path)) {
    die("connect.php not found at: $connect_path");
}

// 3. Include with output buffering to catch any errors
ob_start();
require_once $connect_path;
$include_output = ob_get_clean();
if (!empty($include_output)) {
    die("connect.php produced output: $include_output");
}

// 4. Verify connection object
if (!isset($conn)) {
    die("\$conn variable not set in connect.php");
}

if (!($conn instanceof mysqli)) {
    die("\$conn is not a valid MySQLi connection");
}

// 5. Test connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connection successful!<br>"; // DEBUG

// 6. YOUR EXACT QUERY
$query = "SELECT id, name, address, phone, message, created_at FROM contact_messages ORDER BY created_at DESC";
$messages = $conn->query($query);

if ($messages === false) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<!-- Rest of your HTML remains exactly the same -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages</title>
     <script src="../assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="hero-container">
    <div class="hero-header">
        <div>
            <a href="../dashboard.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <h2><i class="bi bi-envelope"></i> Message Management</h2>
    </div>

    <?php include '../includes/alerts.php'; ?>

    <div class="hero-card">
        <div class="hero-card-body">
            <div class="table-container">
                <table class="hero-table">
                    <thead>
                    <tr>
                        <th><i class="bi bi-person"></i> Name</th>
                        <th><i class="bi bi-location"></i> Address</th>
                        <th><i class="bi bi-telephone"></i> Phone</th>
                        <th><i class="bi bi-chat-left-text"></i> Message</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($messages->num_rows > 0): ?>
                        <?php while($message = $messages->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($message['name']) ?></td>
                                <td><?= htmlspecialchars($message['address']) ?></td>
                                <td><?= htmlspecialchars($message['phone']) ?></td>
                                <td class="message-content"><?= htmlspecialchars(substr($message['message'], 0, 50)) ?><?= strlen($message['message']) > 50 ? '...' : '' ?></td>
                                <td><?= date('M j, Y g:i a', strtotime($message['created_at'])) ?></td>
                                <td class="action-btns">
                                    <button type="button" onclick="showDeleteModal(<?= $message['id'] ?>)" class="delete-btn">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No messages found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="delete-modal">
    <div class="modal-content">
        <h3><i class="bi bi-exclamation-triangle"></i> Confirm Deletion</h3>
        <p>Are you sure you want to delete this message?</p>
        <div class="modal-actions">
            <button id="confirmDelete" class="confirm-btn"><i class="bi bi-check-circle"></i> Delete</button>
            <button id="cancelDelete" class="cancel-btn"><i class="bi bi-x-circle"></i> Cancel</button>
        </div>
    </div>
</div>

<script src="../assets/js/theme.js"></script>
<script>
let currentIdToDelete = null;

function showDeleteModal(id) {
    currentIdToDelete = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = 'none';
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentIdToDelete) {
        this.innerHTML = '<i class="bi bi-hourglass"></i> Deleting...';
        this.disabled = true;

        fetch(`delete-message.php?id=${currentIdToDelete}`, {
            method: 'POST'
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
});
</script>

</body>
</html>