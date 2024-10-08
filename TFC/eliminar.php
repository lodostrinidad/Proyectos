<?php
session_start();
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar archivo
    $consulta = $conexion->prepare("DELETE FROM archivos WHERE id = ?");
    $consulta->bind_param("i", $id);
    $consulta->execute();

    header('Location: index.php');
}
?>