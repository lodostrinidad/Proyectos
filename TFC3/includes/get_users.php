<?php
// Incluye el archivo de conexión a la base de datos
require '../includes/db_connect.php';

// Intenta ejecutar la consulta para obtener todos los usuarios
try {
    $stmt = $conn->prepare("SELECT id, username, email, role, created_at, last_login, is_active FROM users");
    $stmt->execute();

    // Obtiene todos los resultados en modo asociativo
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Establece el tipo de contenido como JSON
    header('Content-Type: application/json');
    echo json_encode($users);
} catch (PDOException $e) {
    // En caso de error, se devuelve un mensaje de error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error al obtener usuarios: ' . $e->getMessage()]);
} finally {
    // Cierra la conexión
    $conn = null;
}
?>
