<?php
// Connect to database (adjust these to your connection details)
$conn = new mysqli('localhost', 'username', 'password', 'database_name');

// Count unread messages (assuming 'created_at' can determine unread status)
$result = $conn->query("SELECT COUNT(*) as unread FROM messages 
                       WHERE created_at > (SELECT last_message_check FROM users WHERE id = 1)");
$row = $result->fetch_assoc();

// Return the count
echo $row['unread'];
?>