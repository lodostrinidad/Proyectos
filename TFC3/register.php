<?php
session_start();
require 'includes/db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Verifica si el usuario ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $response['success'] = false;
        $response['message'] = "El nombre de usuario o el correo ya están en uso.";
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Inserta el nuevo usuario como 'usuario'
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, 'user')");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Usuario registrado exitosamente.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error al registrar el usuario.";
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = "Error en la base de datos: " . $e->getMessage();
        }
    }

    // Devolver la respuesta como JSON
    echo json_encode($response);
    exit; // Asegúrate de salir después de enviar la respuesta
}
