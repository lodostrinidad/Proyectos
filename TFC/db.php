<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";  // Si tienes una contraseña en tu servidor MySQL, añádela aquí
$base_de_datos = "tfg";

$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
