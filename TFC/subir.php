<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_archivo = basename($_FILES['archivo']['name']);
    $ruta_archivo = 'uploads/' . $nombre_archivo;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
        $usuario_id = $_SESSION['usuario']['id'];
        $consulta = $conexion->prepare("INSERT INTO archivos (nombre_archivo, ruta_archivo, usuario_id) VALUES (?, ?, ?)");
        $consulta->bind_param("ssi", $nombre_archivo, $ruta_archivo, $usuario_id);
        $consulta->execute();
        header('Location: index.php');
    } else {
        echo "Error al subir archivo";
    }
}
?>
