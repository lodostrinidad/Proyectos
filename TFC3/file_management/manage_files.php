<?php
session_start();
require '../includes/db_connect.php';

// Verificar si el usuario tiene permisos
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Obtener archivos del usuario o de todos los usuarios si es admin
if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->prepare("SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id");
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id WHERE f.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
}

$stmt->execute();
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Archivos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/manage_files.css">
</head>
<body>
    <h1>Gestionar Archivos</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre del Archivo</th>
                <th>Propietario</th>
                <th>Tamaño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                    <td><?php echo htmlspecialchars($file['username']); ?></td>
                    <td><?php echo htmlspecialchars($file['file_size']); ?> bytes</td>
                    <td>
                        <a href="delete_file.php?id=<?php echo $file['id']; ?>">Eliminar</a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="move_file.php?id=<?php echo $file['id']; ?>">Mover</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><a href="<?php echo $_SESSION['role']; ?>_dashboard.php">Volver al Dashboard</a>
</body>
</html>
