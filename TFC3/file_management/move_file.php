<?php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $conn->prepare("UPDATE files SET folder_id = :folder_id WHERE id = :file_id AND user_id = :user_id");
    $stmt->bindParam(':folder_id', $data['folderId'], PDO::PARAM_INT);
    $stmt->bindParam(':file_id', $data['fileId'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $result = $stmt->execute();

    echo json_encode(['success' => $result]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}