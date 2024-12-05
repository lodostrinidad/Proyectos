<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Primero, obtenemos el archivo para verificar si el usuario tiene permisos para eliminarlo
    $stmt = $conn->prepare("SELECT user_id FROM files WHERE id = :id");
    $stmt->bindParam(':id', $file_id);
    $stmt->execute();
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        // Comprobar si el usuario es admin o el propietario del archivo
        if ($_SESSION['role'] === 'admin' || $file['user_id'] == $_SESSION['user_id']) {
            // Eliminar archivo del sistema de archivos
            $stmt = $conn->prepare("SELECT file_path FROM files WHERE id = :id");
            $stmt->bindParam(':id', $file_id);
            $stmt->execute();
            $file_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($file_data) {
                // Eliminar el archivo físico
                if (file_exists($file_data['file_path'])) {
                    unlink($file_data['file_path']); // Eliminar el archivo
                }

                // Eliminar archivo de la base de datos
                $stmt = $conn->prepare("DELETE FROM files WHERE id = :id");
                $stmt->bindParam(':id', $file_id);
                $stmt->execute();
            }
        } else {
            // Mensaje de error si no tiene permisos
            echo "No tienes permiso para eliminar este archivo.";
            exit;
        }
    }
    header("Location: manage_files.php");
    exit;
}
?>
