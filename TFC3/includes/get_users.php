<?php
// Incluye el archivo de conexión a la base de datos
require 'db_connect.php';

try {
    $stmt = $conn->prepare("SELECT id, username, email, role FROM users");
    $stmt->execute();

    // Obtiene todos los resultados en modo asociativo
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener usuarios: ' . $e->getMessage()]);
} finally {
    $conn = null;
}
?>
