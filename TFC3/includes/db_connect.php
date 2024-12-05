<?php
// db_connect.php

$host = 'localhost'; // Cambia si es necesario
$db_name = 'gestor_archivos3'; // Cambia si es necesario
$username = 'root'; // Cambia si es necesario
$password = ''; // Cambia si es necesario

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    // Configuración de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
