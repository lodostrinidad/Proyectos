<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";  // Cambia este valor si tienes otro usuario
$password = "";      // Cambia este valor si tienes una contraseña diferente
$dbname = "tfc";     // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
