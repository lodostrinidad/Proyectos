<?php
session_start();
require 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_id = $_POST['file_id'];
    $shared_user_id = $_POST['shared_user_id'];
    $permissions = $_POST['permissions']; // 'view' o 'edit'

    $stmt = $conn->prepare("INSERT INTO shares (file_id, user_id, shared_by, permissions) VALUES (:file_id, :shared_user_id, :shared_by, :permissions)");
    $stmt->bindParam(':file_id', $file_id);
    $stmt->bindParam(':shared_user_id', $shared_user_id);
    $stmt->bindParam(':shared_by', $_SESSION['user_id']);
    $stmt->bindParam(':permissions', $permissions);

    if ($stmt->execute()) {
        echo "Archivo compartido con éxito.";
    } else {
        echo "Error al compartir el archivo.";
    }
}
?>
