<?php
session_start();
require 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $folder_name = $_POST['folder_name'];
    $parent_folder_id = $_POST['parent_folder_id'] ?: NULL;

    if (!empty($folder_name)) {
        $stmt = $conn->prepare("INSERT INTO folders (user_id, folder_name, parent_folder_id) VALUES (:user_id, :folder_name, :parent_folder_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':folder_name', $folder_name);
        $stmt->bindParam(':parent_folder_id', $parent_folder_id);
        if ($stmt->execute()) {
            echo "Carpeta creada con éxito.";
        } else {
            echo "Error al crear la carpeta.";
        }
    } else {
        echo "El nombre de la carpeta no puede estar vacío.";
    }
}
?>
