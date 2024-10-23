<?php
header('Content-Type: application/json');
include 'db_connect.php';

// Verificar si se ha proporcionado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Devolver el usuario como JSON
    echo json_encode($user);
} else {
    // Respuesta si no se proporciona un ID
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>
