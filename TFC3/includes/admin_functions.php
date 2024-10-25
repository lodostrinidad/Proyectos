<?php
require 'db_connect.php';

function getAllUsers() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT id, username, email, role FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => 'Error al obtener usuarios: ' . $e->getMessage()];
    }
}

function createUser($username, $email, $role, $password) {
    global $conn;
    try {
        // Hashear la contraseña antes de guardarla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, role, password) VALUES (:username, :email, :role, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return ['success' => true];
    } catch (PDOException $e) {
        return ['error' => 'Error al crear usuario: ' . $e->getMessage()];
    }
}

function editUser($id, $username, $email, $role, $password = null) {
    global $conn;
    try {
        // Si se proporciona una nueva contraseña, se debe hashear
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, role = :role, password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        }
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return ['success' => true];
    } catch (PDOException $e) {
        return ['error' => 'Error al actualizar usuario: ' . $e->getMessage()];
    }
}

function deleteUser($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['success' => true];
    } catch (PDOException $e) {
        return ['error' => 'Error al eliminar usuario: ' . $e->getMessage()];
    }
}

function getUser($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => 'Error al obtener usuario: ' . $e->getMessage()];
    }
}
?>
