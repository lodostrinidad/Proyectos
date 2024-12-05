<?php
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT message_content FROM admin_messages ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
?>
