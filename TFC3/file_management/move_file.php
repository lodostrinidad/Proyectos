<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['submit'])) {
    $file_id = $_POST['file_id'];
    $folder_id = $_POST['folder_id'];

    // Actualiza la carpeta del archivo en la base de datos
    $stmt = $conn->prepare("UPDATE files SET folder_id = :folder_id WHERE id = :file_id");
    $stmt->bindParam(':folder_id', $folder_id);
    $stmt->bindParam(':file_id', $file_id);
    $stmt->execute();

    header("Location: manage_files.php");
    exit;
}

// Aquí deberías obtener las carpetas disponibles para el usuario
$stmt = $conn->prepare("SELECT * FROM folders"); // Asegúrate de tener una tabla de carpetas
$stmt->execute();
$folders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mover Archivo</title>
</head>
<body>
    <h1>Mover Archivo</h1>
    <form action="move_file.php" method="post">
        <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <label for="folder_id">Selecciona la nueva carpeta:</label>
        <select name="folder_id" id="folder_id" required>
            <?php foreach ($folders as $folder): ?>
                <option value="<?php echo $folder['id']; ?>"><?php echo htmlspecialchars($folder['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="submit">Mover Archivo</button>
    </form>

    <br><a href="manage_files.php">Cancelar</a>
</body>
</html>
