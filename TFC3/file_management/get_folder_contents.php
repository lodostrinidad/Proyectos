<?php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['folder_id'])) {
    echo json_encode([]);
    exit;
}

$folder_id = $_GET['folder_id'];
$user_id = $_SESSION['user_id'];

try {
    // Obtener archivos en la carpeta
    $stmt_files = $conn->prepare("SELECT * FROM files WHERE user_id = :user_id AND folder_id = :folder_id AND is_deleted = 0");
    $stmt_files->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_files->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
    $stmt_files->execute();
    $files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

    // Obtener subcarpetas
    $stmt_folders = $conn->prepare("SELECT * FROM folders WHERE user_id = :user_id AND parent_folder_id = :folder_id");
    $stmt_folders->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_folders->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
    $stmt_folders->execute();
    $folders = $stmt_folders->fetchAll(PDO::FETCH_ASSOC);

    // Combinar archivos y carpetas
    $contents = array_merge($folders, $files);
    
    echo json_encode($contents);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}