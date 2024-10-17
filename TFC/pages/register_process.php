<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = 2; // Por defecto, asigna el rol de 'usuario'

    // Verificar si el usuario o email ya existe
    $query = "SELECT * FROM usuarios WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Hashear la contraseña antes de almacenar
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario
        $query = "INSERT INTO usuarios (nombre, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nombre, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Asignar rol de usuario
            $query = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $role_id); // Cambiado rol_id a role_id
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'El usuario o email ya existe.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
