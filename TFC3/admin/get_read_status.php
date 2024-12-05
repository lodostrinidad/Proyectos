<?php
require_once '../includes/db_connect.php'; // Connect to the database

header('Content-Type: application/json');

$sql = "SELECT u.username, IF(mr.read_at IS NOT NULL, 1, 0) AS read_status
        FROM users u
        LEFT JOIN message_reads mr ON u.id = mr.user_id
        LEFT JOIN admin_messages am ON mr.message_id = am.id
        ORDER BY u.username";

$result = $conn->query($sql);

$readStatuses = [];
while ($row = $result->fetch_assoc()) {
    $readStatuses[] = ['user' => $row['username'], 'read' => (bool)$row['read_status']];
}

echo json_encode($readStatuses);

$conn->close();
?>
