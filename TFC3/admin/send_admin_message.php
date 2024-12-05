<?php
require_once '../includes/db_connect.php'; // Connect to the database

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$messageContent = $data['message'] ?? '';

if ($messageContent) {
    $stmt = $conn->prepare("INSERT INTO admin_messages (message_content) VALUES (?)");
    $stmt->bind_param('s', $messageContent);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send message.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No message content provided.']);
}

$conn->close();
?>
