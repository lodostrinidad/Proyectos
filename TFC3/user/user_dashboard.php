<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); // Redirigir si no es usuario
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user_dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <h2>Gestión de Archivos</h2>
    
    <form action="upload_file.php" method="post" enctype="multipart/form-data">
        <label for="file">Selecciona un archivo:</label>
        <input type="file" name="file" id="file" required>
        <label for="folder_id">Selecciona la carpeta:</label>
        <select name="folder_id">
            <option value="">Raíz</option>
            <!-- Aquí se listarían las carpetas disponibles del usuario -->
        </select>
        <button type="submit">Subir Archivo</button>
    </form>

    <a href="../file_management/manage_files.php" class="button">Gestionar Archivos</a>

    <br><a href="../logout.php">Cerrar sesión</a>
</body>
</html>
