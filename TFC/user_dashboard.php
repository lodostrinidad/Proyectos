<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';
$usuarioId = $_SESSION['usuario_id'];

// Funciones de subir archivos y crear carpetas
if (isset($_POST['upload'])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO archivos (nombre_archivo, ruta_archivo, usuario_id) VALUES ('$fileName', '$targetFilePath', '$usuarioId')";
        $conexion->query($sql);
    }
}

if (isset($_POST['create_folder'])) {
    $nombreCarpeta = $_POST['folder_name'];
    $sql = "INSERT INTO carpetas (nombre, usuario_id) VALUES ('$nombreCarpeta', '$usuarioId')";
    $conexion->query($sql);
}

$archivos = $conexion->query("SELECT * FROM archivos WHERE usuario_id = '$usuarioId'");
$carpetas = $conexion->query("SELECT * FROM carpetas WHERE usuario_id = '$usuarioId'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .sidebar {
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #007bff;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        h2 {
            margin-top: 20px;
        }

        h3 {
            margin-top: 30px;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-info {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="text-white text-center">Panel de Control</h2>
        <a href="user_dashboard.php">Mis Archivos</a>
        <a href="profile.php">Mi Perfil</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>

    <div class="content">
        <h2>Bienvenido al Dashboard</h2>
        
        <div class="row">
            <div class="col-md-6">
                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <h3>Subir Archivo</h3>
                    <div class="input-group">
                        <input type="file" name="file" class="form-control" required>
                        <div class="input-group-append">
                            <button type="submit" name="upload" class="btn btn-success">Subir</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <form method="POST" class="mb-4">
                    <h3>Crear Carpeta</h3>
                    <div class="input-group">
                        <input type="text" name="folder_name" class="form-control" placeholder="Nombre de la Carpeta" required>
                        <div class="input-group-append">
                            <button type="submit" name="create_folder" class="btn btn-primary">Crear Carpeta</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h3>Mis Archivos</h3>
        <ul class="list-group">
            <?php while ($archivo = $archivos->fetch_assoc()) { ?>
                <li class="list-group-item">
                    <span><?php echo $archivo['nombre_archivo']; ?></span>
                    <a href="<?php echo $archivo['ruta_archivo']; ?>" class="btn btn-info btn-sm" target="_blank">Ver</a>
                </li>
            <?php } ?>
        </ul>

        <h3>Mis Carpetas</h3>
        <ul class="list-group">
            <?php while ($carpeta = $carpetas->fetch_assoc()) { ?>
                <li class="list-group-item">
                    <span><?php echo $carpeta['nombre']; ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
