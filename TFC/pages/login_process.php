<?php
session_start();
require_once '../config/db.php'; // Asegúrate de que la conexión esté bien configurada

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario
    $query = "
        SELECT u.*, r.nombre AS role 
        FROM usuarios u 
        JOIN usuario_roles ur ON u.id = ur.usuario_id 
        JOIN roles r ON ur.rol_id = r.id 
        WHERE u.username = ? LIMIT 1
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Comparar directamente las contraseñas en texto plano
        if ($password === $user['password']) {
            // Establecer la sesión
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Asignar el rol obtenido de la tabla 'roles'

            // Enviar respuesta JSON con éxito y rol
            echo json_encode(['success' => true, 'role' => $user['role']]);
        } else {
            // Contraseña incorrecta
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        // Usuario no encontrado
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
