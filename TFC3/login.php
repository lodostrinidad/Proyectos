<?php
session_start();
require 'includes/db_connect.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica si el usuario existe
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica la contraseña
        if (password_verify($password, $user['password'])) {
            // Establecer la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión

            $response['success'] = true;
            $response['message'] = "Inicio de sesión exitoso.";

            // Redirigir según el rol
            switch ($user['role']) {
                case 'admin':
                    $response['redirect'] = "admin/admin_dashboard.php";
                    break;
                case 'manager':
                    $response['redirect'] = "manager/manager_dashboard.php";
                    break;
                case 'user':
                    $response['redirect'] = "user/user_dashboard.php";
                    break;
                default:
                    $response['redirect'] = "index.php"; // Redirigir a la página principal si no se reconoce el rol
                    break;
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Contraseña incorrecta.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "El usuario no existe.";
    }

    // Devolver la respuesta como JSON
    echo json_encode($response);
    exit; // Asegúrate de salir después de enviar la respuesta
}
?>
