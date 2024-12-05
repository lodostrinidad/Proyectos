<?php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    if ($data['type'] === 'folder') {
        $stmt = $conn->prepare("DELETE FROM folders WHERE id = :id AND user_id = :user_id");
    } else {
        $stmt = $conn->prepare("UPDATE files SET is_deleted = 1 WHERE id = :id AND user_id = :user_id");
    }
    
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $result = $stmt->execute();

    echo json_encode(['success' => $result]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}