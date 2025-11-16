<?php
function log_activity($conn, $activity_type, $description) {
    $stmt = $conn->prepare("INSERT INTO activities (activity_type, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $activity_type, $description);
    return $stmt->execute();
}
?>