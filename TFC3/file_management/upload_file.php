<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['file'];
    $folder_id = $_POST['folder_id'];

    // Validar el archivo
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($file['name']);
        $file_path = "../uploads/" . $file_name; // Cambia la ruta según tu estructura
        $file_size = $file['size'];
        
        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insertar el archivo en la base de datos
            $user_id = $_SESSION['role'] === 'admin' ? $_POST['user_id'] : $_SESSION['user_id']; // Si es admin, tomar el ID del usuario al que sube el archivo

            $stmt = $conn->prepare("INSERT INTO files (user_id, folder_id, file_name, file_path, file_size, file_type_id) VALUES (:user_id, :folder_id, :file_name, :file_path, :file_size, :file_type_id)");
            $file_type_id = 1; // Asignar el tipo de archivo (puedes modificar esto según tu lógica)

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':file_name', $file_name);
            $stmt->bindParam(':file_path', $file_path);
            $stmt->bindParam(':file_size', $file_size);
            $stmt->bindParam(':file_type_id', $file_type_id);
            $stmt->execute();

            // Redirigir o mostrar un mensaje de éxito
            header("Location: {$_SESSION['role']}_dashboard.php"); // Redirigir al dashboard correspondiente
            exit;
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
