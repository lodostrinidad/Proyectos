<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: index.php"); // Redirigir si no es encargado
    exit;
}

require_once '../includes/db_connect.php';

// Función para obtener archivos del usuario
function getUserFiles($conn, $user_id) {
    // Obtener archivos
    $stmt_files = $conn->prepare("SELECT * FROM files WHERE user_id = :user_id AND is_deleted = 0");
    $stmt_files->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_files->execute();
    $files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

    // Obtener carpetas
    $stmt_folders = $conn->prepare("SELECT * FROM folders WHERE user_id = :user_id");
    $stmt_folders->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_folders->execute();
    $folders = $stmt_folders->fetchAll(PDO::FETCH_ASSOC);

    // Combinar archivos y carpetas
    $combined = array_merge($files, $folders);

    return $combined;
}

// Función para subir archivos
function uploadFile($conn, $user_id, $file) {
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_type = mime_content_type($file_tmp);
    $file_path = 'uploads/' . $file_name;

    // Crear directorio de uploads si no existe
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Mover el archivo a la carpeta de subidas
    move_uploaded_file($file_tmp, $file_path);

    // Insertar el archivo en la base de datos
    $stmt = $conn->prepare("INSERT INTO files (user_id, file_name, file_path, file_size, file_type_id) VALUES (:user_id, :file_name, :file_path, :file_size, (SELECT id FROM file_types WHERE mime_type = :file_type))");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
    $stmt->bindParam(':file_path', $file_path, PDO::PARAM_STR);
    $stmt->bindParam(':file_size', $file_size, PDO::PARAM_INT);
    $stmt->bindParam(':file_type', $file_type, PDO::PARAM_STR);
    $stmt->execute();

    // Devolver mensaje de éxito
    $_SESSION['upload_success'] = "Archivo '$file_name' subido exitosamente.";
}

// Función para crear carpetas
function createFolder($conn, $user_id, $folder_name) {
    $stmt = $conn->prepare("INSERT INTO folders (user_id, folder_name) VALUES (:user_id, :folder_name)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':folder_name', $folder_name, PDO::PARAM_STR);
    $stmt->execute();

    // Devolver mensaje de éxito
    $_SESSION['folder_success'] = "Carpeta '$folder_name' creada exitosamente.";
}

// Función para eliminar carpetas
function deleteFolder($conn, $folder_id) {
    $stmt = $conn->prepare("DELETE FROM folders WHERE id = :folder_id");
    $stmt->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
    $stmt->execute();

    // Devolver mensaje de éxito
    $_SESSION['delete_success'] = "Carpeta eliminada exitosamente.";
}

// Función para renombrar carpetas
function renameFolder($conn, $folder_id, $new_name) {
    $stmt = $conn->prepare("UPDATE folders SET folder_name = :new_name WHERE id = :folder_id");
    $stmt->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
    $stmt->bindParam(':new_name', $new_name, PDO::PARAM_STR);
    $stmt->execute();

    // Devolver mensaje de éxito
    $_SESSION['rename_success'] = "Carpeta renombrada exitosamente.";
}

// Función para crear archivos de texto
function createTextFile($conn, $user_id, $file_name, $content) {
    $file_path = 'uploads/' . $file_name;

    // Crear directorio de uploads si no existe
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Crear el archivo de texto
    file_put_contents($file_path, $content);

    // Insertar el archivo en la base de datos
    $stmt = $conn->prepare("INSERT INTO files (user_id, file_name, file_path, file_size, file_type_id) VALUES (:user_id, :file_name, :file_path, :file_size, (SELECT id FROM file_types WHERE mime_type = 'text/plain'))");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
    $stmt->bindParam(':file_path', $file_path, PDO::PARAM_STR);
    $stmt->bindParam(':file_size', filesize($file_path), PDO::PARAM_INT);
    $stmt->execute();

    // Devolver mensaje de éxito
    $_SESSION['create_file_success'] = "Archivo de texto '$file_name' creado exitosamente.";
}

// Función para buscar archivos
function searchFiles($conn, $user_id, $query) {
    $stmt = $conn->prepare("SELECT * FROM files WHERE user_id = :user_id AND file_name LIKE :query AND is_deleted = 0");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para sincronizar archivos (simulación)
function syncFiles($conn, $user_id) {
    // Simulación de sincronización
    echo "Sincronización completada.";
}

// Función para gestionar permisos
function managePermissions($conn, $user_id, $file_id, $shared_user_id, $permissions) {
    $stmt = $conn->prepare("INSERT INTO shares (file_id, user_id, shared_by, permissions) VALUES (:file_id, :user_id, :shared_by, :permissions)");
    $stmt->bindParam(':file_id', $file_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $shared_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':shared_by', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':permissions', $permissions, PDO::PARAM_STR);
    $stmt->execute();
}

// Función para generar reportes
function generateReport($conn, $user_id) {
    // Simulación de generación de reportes
    echo "Reporte generado.";
}

// Función para gestionar equipos
function manageTeams($conn, $user_id, $team_name) {
    // Simulación de gestión de equipos
    echo "Equipo gestionado.";
}

// Manejar la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    uploadFile($conn, $_SESSION['user_id'], $_FILES['file']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la creación de carpetas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder_name'])) {
    createFolder($conn, $_SESSION['user_id'], $_POST['folder_name']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la eliminación de carpetas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_folder_id'])) {
    deleteFolder($conn, $_POST['delete_folder_id']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar el renombrado de carpetas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rename_folder_id']) && isset($_POST['new_folder_name'])) {
    renameFolder($conn, $_POST['rename_folder_id'], $_POST['new_folder_name']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la creación de archivos de texto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_text_file'])) {
    createTextFile($conn, $_SESSION['user_id'], $_POST['file_name'], $_POST['content']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la búsqueda de archivos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $files = searchFiles($conn, $_SESSION['user_id'], $_GET['search']);
} else {
    $user_id = $_SESSION['user_id'];
    $files = getUserFiles($conn, $user_id);
}

// Manejar la sincronización de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sync'])) {
    syncFiles($conn, $_SESSION['user_id']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la gestión de permisos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manage_permissions'])) {
    managePermissions($conn, $_SESSION['user_id'], $_POST['file_id'], $_POST['shared_user_id'], $_POST['permissions']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la generación de reportes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    generateReport($conn, $_SESSION['user_id']);
    header("Location: manager_dashboard.php");
    exit;
}

// Manejar la gestión de equipos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manage_teams'])) {
    manageTeams($conn, $_SESSION['user_id'], $_POST['team_name']);
    header("Location: manager_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        .file-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            background-color: white;
            transition: background-color 0.3s, color 0.3s;
        }
        .file-card img {
            max-width: 60px;
            margin-bottom: 10px;
        }
        .dark-mode {
            background-color: #343a40;
            color: #ffffff;
        }
        .dark-mode .sidebar {
            background-color: #212529;
            color: #ffffff;
        }
        .dark-mode .sidebar a {
            color: #ffffff;
        }
        .dark-mode .sidebar a:hover {
            background-color: #343a40;
        }
        .dark-mode .main-content {
            background-color: #212529;
            color: #ffffff;
        }
        .dark-mode .file-card {
            background-color: #343a40;
            color: #ffffff;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .header .search-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 10px;
        }
        .header .search-bar input {
            border-radius: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            width: 35%;
            text-align: center;
        }
        .header .search-bar button {
            border-radius: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #007bff;
            color: white;
            margin-left: -40px;
            cursor: pointer;
        }
        .header .search-bar button:hover {
            background-color: #0056b3;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            width: 100%;
            flex-wrap: wrap;
        }
        .action-buttons button {
            border: none;
            background: none;
            color: #007bff;
            font-size: 24px;
            margin: 5px;
            cursor: pointer;
            transition: color 0.3s;
            position: relative;
        }
        .action-buttons button:hover {
            color: #0056b3;
        }
        .action-buttons button:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 12px;
            white-space: nowrap;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .file-icon, .folder-icon {
            font-size: 48px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .folder-icon {
            color: #ffc107;
        }

        .context-menu {
            position: fixed;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }

        .context-menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .context-menu ul li {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .context-menu ul li:hover {
            background-color: #f1f1f1;
        }

        .context-menu ul li i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h5 class="text-center">Administrador</h5>
                <p class="text-center">Rol: Administrador</p>
                <div class="progress mx-3 my-3">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%;">75%</div>
                </div>
                <a href="#" class="active"><i class="fas fa-folder"></i> Mis Archivos</a>
                <a href="#"><i class="fas fa-history"></i> Recientes</a>
                <a href="#"><i class="fas fa-star"></i> Favoritos</a>
                <a href="#"><i class="fas fa-share-alt"></i> Compartidos</a>
                <a href="#"><i class="fas fa-trash"></i> Papelera</a>
                <a href="#"><i class="fas fa-users"></i> Gestionar Usuarios</a>
                <a href="#"><i class="fas fa-cog"></i> Configuración del Sistema</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                <button class="btn btn-light mt-3" id="theme-toggle"><i class="fas fa-moon"></i> Tema Oscuro</button>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header">
                    <div class="search-bar">
                        <form action="manager_dashboard.php" method="get" class="w-100" style="text-align: center;">
                            <input type="text" placeholder="Buscar archivos..." name="search">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="action-buttons">
                        <button data-bs-toggle="modal" data-bs-target="#createFolderModal" data-tooltip="Crear Carpeta"><i class="fas fa-folder-plus"></i></button>
                        <button data-bs-toggle="modal" data-bs-target="#uploadFileModal" data-tooltip="Subir Archivo"><i class="fas fa-upload"></i></button>
                        <button data-bs-toggle="modal" data-bs-target="#createTextFileModal" data-tooltip="Crear Archivo de Texto"><i class="fas fa-file-alt"></i></button>
                        <form action="manager_dashboard.php" method="post" style="display:inline;">
                            <button type="submit" name="sync" data-tooltip="Sincronizar"><i class="fas fa-sync-alt"></i></button>
                        </form>
                        <button data-bs-toggle="modal" data-bs-target="#managePermissionsModal" data-tooltip="Gestionar Permisos"><i class="fas fa-lock"></i></button>
                        <form action="manager_dashboard.php" method="post" style="display:inline;">
                            <button type="submit" name="generate_report" data-tooltip="Reportes"><i class="fas fa-file-alt"></i></button>
                        </form>
                        <button data-bs-toggle="modal" data-bs-target="#manageTeamsModal" data-tooltip="Gestionar Equipos"><i class="fas fa-users-cog"></i></button>
                    </div>
                </div>

                <!-- Files Section -->
                <div class="row" id="files-container">
                    <?php foreach ($files as $item): ?>
                        <div class="col-md-4 file-item" draggable="true"
                            data-id="<?php echo htmlspecialchars($item['id'] ?? ''); ?>"
                            data-type="<?php echo isset($item['file_name']) ? 'file' : 'folder'; ?>">
                            <div class="file-card" oncontextmenu="showContextMenu(event)">
                                <?php if (isset($item['file_name'])): ?>
                                    <!-- Tarjeta de archivo -->
                                    <i class="fas fa-file file-icon"></i>
                                    <h6><?php echo htmlspecialchars($item['file_name']); ?></h6>
                                    <p>Modificado: <?php echo htmlspecialchars($item['last_modified'] ?? 'No disponible'); ?></p>
                                    <p>Tamaño: <?php echo htmlspecialchars($item['file_size'] ?? '0'); ?> bytes</p>
                                <?php else: ?>
                                    <!-- Tarjeta de carpeta -->
                                    <i class="fas fa-folder folder-icon"></i>
                                    <h6><?php echo htmlspecialchars($item['folder_name']); ?></h6>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Menú contextual -->
                <div id="context-menu" class="context-menu">
                    <ul>
                        <li onclick="renameItem()"><i class="fas fa-edit"></i> Renombrar</li>
                        <li onclick="deleteItem()"><i class="fas fa-trash"></i> Eliminar</li>
                        <li onclick="openFolder()"><i class="fas fa-folder-open"></i> Abrir</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para subir archivos -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Subir Archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manager_dashboard.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Seleccionar archivo</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-success">Subir Archivo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear carpetas -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFolderModalLabel">Crear Carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manager_dashboard.php" method="post">
                        <div class="form-group">
                            <label for="folder_name">Nombre de la carpeta</label>
                            <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Carpeta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear archivos de texto -->
    <div class="modal fade" id="createTextFileModal" tabindex="-1" aria-labelledby="createTextFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTextFileModalLabel">Crear Archivo de Texto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manager_dashboard.php" method="post">
                        <div class="form-group">
                            <label for="file_name">Nombre del archivo</label>
                            <input type="text" class="form-control" id="file_name" name="file_name" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Contenido</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="create_text_file">Crear Archivo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar permisos -->
    <div class="modal fade" id="managePermissionsModal" tabindex="-1" aria-labelledby="managePermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="managePermissionsModalLabel">Gestionar Permisos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manager_dashboard.php" method="post">
                        <div class="form-group">
                            <label for="file_id">ID del archivo</label>
                            <input type="text" class="form-control" id="file_id" name="file_id" required>
                        </div>
                        <div class="form-group">
                            <label for="shared_user_id">ID del usuario compartido</label>
                            <input type="text" class="form-control" id="shared_user_id" name="shared_user_id" required>
                        </div>
                        <div class="form-group">
                            <label for="permissions">Permisos</label>
                            <select class="form-control" id="permissions" name="permissions" required>
                                <option value="view">Ver</option>
                                <option value="edit">Editar</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger" name="manage_permissions">Gestionar Permisos</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar equipos -->
    <div class="modal fade" id="manageTeamsModal" tabindex="-1" aria-labelledby="manageTeamsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageTeamsModalLabel">Gestionar Equipos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manager_dashboard.php" method="post">
                        <div class="form-group">
                            <label for="team_name">Nombre del equipo</label>
                            <input type="text" class="form-control" id="team_name" name="team_name" required>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="manage_teams">Gestionar Equipo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_GET['search'])): ?>
            Swal.fire({
                icon: 'info',
                title: 'Resultados de búsqueda',
                text: 'Mostrando resultados para: <?php echo htmlspecialchars($_GET['search']); ?>',
            });
        <?php endif; ?>

        <?php if (isset($_SESSION['upload_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Archivo subido',
                text: '<?php echo htmlspecialchars($_SESSION['upload_success']); ?>',
            });
            <?php unset($_SESSION['upload_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['folder_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Carpeta creada',
                text: '<?php echo htmlspecialchars($_SESSION['folder_success']); ?>',
            });
            <?php unset($_SESSION['folder_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['delete_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Carpeta eliminada',
                text: '<?php echo htmlspecialchars($_SESSION['delete_success']); ?>',
            });
            <?php unset($_SESSION['delete_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['rename_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Carpeta renombrada',
                text: '<?php echo htmlspecialchars($_SESSION['rename_success']); ?>',
            });
            <?php unset($_SESSION['rename_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['create_file_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Archivo de texto creado',
                text: '<?php echo htmlspecialchars($_SESSION['create_file_success']); ?>',
            });
            <?php unset($_SESSION['create_file_success']); ?>
        <?php endif; ?>

        <?php if (isset($_POST['sync'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sincronización completada',
                text: 'Tus archivos han sido sincronizados correctamente.',
            });
        <?php endif; ?>

        <?php if (isset($_POST['generate_report'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Reporte generado',
                text: 'El reporte ha sido generado correctamente.',
            });
        <?php endif; ?>

        <?php if (isset($_POST['manage_permissions'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Permisos gestionados',
                text: 'Los permisos han sido gestionados correctamente.',
            });
        <?php endif; ?>

        <?php if (isset($_POST['manage_teams'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Equipo gestionado',
                text: 'El equipo ha sido gestionado correctamente.',
            });
        <?php endif; ?>

        // Tema claro/oscuro
        const themeToggleBtn = document.getElementById('theme-toggle');
        const body = document.body;

        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i> Tema Claro';
            } else {
                themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i> Tema Oscuro';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Drag and Drop functionality
        const filesContainer = document.getElementById('files-container');
        let draggedItem = null;

        filesContainer.addEventListener('dragstart', (e) => {
            draggedItem = e.target.closest('.file-item');
            e.dataTransfer.setData('text/plain', '');
        });

        filesContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        filesContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            const dropTarget = e.target.closest('.file-item');

            if (dropTarget && draggedItem !== dropTarget) {
                // Determine if dropping into a folder
                const targetType = dropTarget.dataset.type;
                const draggedType = draggedItem.dataset.type;

                if (targetType === 'folder' && draggedType === 'file') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Mover archivo',
                        text: `¿Deseas mover ${draggedItem.querySelector('h6').textContent} a ${dropTarget.querySelector('h6').textContent}?`,
                        showCancelButton: true,
                        confirmButtonText: 'Sí, mover',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lógica de movimiento de archivo
                            moveFile(draggedItem.dataset.id, dropTarget.dataset.id);
                        }
                    });
                }
            }
            draggedItem = null;
        });

        // Context Menu functionality
        let currentContextItem = null;
        const contextMenu = document.getElementById('context-menu');

        function showContextMenu(e) {
            e.preventDefault();
            currentContextItem = e.target.closest('.file-item');

            const menuWidth = contextMenu.offsetWidth;
            const menuHeight = contextMenu.offsetHeight;

            let posX = e.clientX;
            let posY = e.clientY;

            // Adjust position if menu would go off-screen
            if (posX + menuWidth > window.innerWidth) {
                posX -= menuWidth;
            }
            if (posY + menuHeight > window.innerHeight) {
                posY -= menuHeight;
            }

            contextMenu.style.display = 'block';
            contextMenu.style.top = `${posY}px`;
            contextMenu.style.left = `${posX}px`;
        }

        function hideContextMenu() {
            contextMenu.style.display = 'none';
        }

        // Hide context menu when clicking outside
        document.addEventListener('click', hideContextMenu);

        // Attach showContextMenu to file items
        const fileItems = document.querySelectorAll('.file-item');
        fileItems.forEach(item => {
            item.addEventListener('contextmenu', showContextMenu);
        });

        // Context menu actions
        window.renameItem = function() {
            if (!currentContextItem) return;

            const itemName = currentContextItem.querySelector('h6').textContent;
            const itemType = currentContextItem.dataset.type;

            Swal.fire({
                title: `Renombrar ${itemType}`,
                input: 'text',
                inputValue: itemName,
                showCancelButton: true,
                confirmButtonText: 'Renombrar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes introducir un nombre';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    renameItemOnServer(
                        currentContextItem.dataset.id,
                        result.value,
                        itemType
                    );
                }
            });
        }

        window.deleteItem = function() {
            if (!currentContextItem) return;

            const itemName = currentContextItem.querySelector('h6').textContent;
            const itemType = currentContextItem.dataset.type;

            Swal.fire({
                title: `¿Eliminar ${itemType}?`,
                text: `Estás seguro de eliminar ${itemName}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItemOnServer(
                        currentContextItem.dataset.id,
                        itemType
                    );
                }
            });
        }

        window.openFolder = function() {
            if (!currentContextItem || currentContextItem.dataset.type !== 'folder') return;

            const folderId = currentContextItem.dataset.id;
            const folderName = currentContextItem.querySelector('h6').textContent;

            // Realizar una solicitud AJAX para obtener el contenido de la carpeta
            fetch(`../file_management/get_folder_contents.php?folder_id=${folderId}`)
                .then(response => response.json())
                .then(data => {
                    // Limpiar el contenedor de archivos actual
                    const filesContainer = document.getElementById('files-container');
                    filesContainer.innerHTML = '';

                    // Mostrar la ruta de la carpeta actual
                    const header = document.querySelector('.header');
                    const breadcrumb = document.createElement('div');
                    breadcrumb.classList.add('breadcrumb');
                    breadcrumb.innerHTML = `
                        <a href="#" onclick="loadRootFolder()">Mis Archivos</a> /
                        <span>${folderName}</span>
                    `;
                    header.insertBefore(breadcrumb, header.firstChild);

                    // Renderizar archivos y subcarpetas
                    data.forEach(item => {
                        const itemElement = document.createElement('div');
                        itemElement.classList.add('col-md-4', 'file-item');
                        itemElement.setAttribute('draggable', 'true');
                        itemElement.dataset.id = item.id;
                        itemElement.dataset.type = item.file_name ? 'file' : 'folder';

                        const cardContent = item.file_name
                            ? `
                                <i class="fas fa-file file-icon"></i>
                                <h6>${item.file_name}</h6>
                                <p>Modificado: ${item.last_modified || 'No disponible'}</p>
                                <p>Tamaño: ${item.file_size || '0'} bytes</p>
                            `
                            : `
                                <i class="fas fa-folder folder-icon"></i>
                                <h6>${item.folder_name}</h6>
                            `;

                        itemElement.innerHTML = `
                            <div class="file-card" oncontextmenu="showContextMenu(event)">
                                ${cardContent}
                            </div>
                        `;

                        filesContainer.appendChild(itemElement);
                    });

                    // Volver a añadir los event listeners para drag and drop y context menu
                    addDragAndDropListeners();
                    addContextMenuListeners();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el contenido de la carpeta'
                    });
                    console.error('Error:', error);
                });
        }

        // Función para volver a la carpeta raíz
        function loadRootFolder() {
            fetch('../file_management/get_root_contents.php')
                .then(response => response.json())
                .then(data => {
                    // Limpiar el contenedor de archivos
                    const filesContainer = document.getElementById('files-container');
                    filesContainer.innerHTML = '';

                    // Eliminar breadcrumb
                    const breadcrumb = document.querySelector('.breadcrumb');
                    if (breadcrumb) breadcrumb.remove();

                    // Renderizar archivos y carpetas
                    data.forEach(item => {
                        const itemElement = document.createElement('div');
                        itemElement.classList.add('col-md-4', 'file-item');
                        itemElement.setAttribute('draggable', 'true');
                        itemElement.dataset.id = item.id;
                        itemElement.dataset.type = item.file_name ? 'file' : 'folder';

                        const cardContent = item.file_name
                            ? `
                                <i class="fas fa-file file-icon"></i>
                                <h6>${item.file_name}</h6>
                                <p>Modificado: ${item.last_modified || 'No disponible'}</p>
                                <p>Tamaño: ${item.file_size || '0'} bytes</p>
                            `
                            : `
                                <i class="fas fa-folder folder-icon"></i>
                                <h6>${item.folder_name}</h6>
                            `;

                        itemElement.innerHTML = `
                            <div class="file-card" oncontextmenu="showContextMenu(event)">
                                ${cardContent}
                            </div>
                        `;

                        filesContainer.appendChild(itemElement);
                    });

                    // Volver a añadir los event listeners para drag and drop y context menu
                    addDragAndDropListeners();
                    addContextMenuListeners();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el contenido de la carpeta raíz'
                    });
                    console.error('Error:', error);
                });
        }

        // Funciones para re-añadir listeners
        function addDragAndDropListeners() {
            const filesContainer = document.getElementById('files-container');
            let draggedItem = null;

            filesContainer.addEventListener('dragstart', (e) => {
                draggedItem = e.target.closest('.file-item');
                e.dataTransfer.setData('text/plain', '');
            });

            filesContainer.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            filesContainer.addEventListener('drop', (e) => {
                e.preventDefault();
                const dropTarget = e.target.closest('.file-item');

                if (dropTarget && draggedItem !== dropTarget) {
                    const targetType = dropTarget.dataset.type;
                    const draggedType = draggedItem.dataset.type;

                    if (targetType === 'folder' && draggedType === 'file') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Mover archivo',
                            text: `¿Deseas mover ${draggedItem.querySelector('h6').textContent} a ${dropTarget.querySelector('h6').textContent}?`,
                            showCancelButton: true,
                            confirmButtonText: 'Sí, mover',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                moveFile(draggedItem.dataset.id, dropTarget.dataset.id);
                            }
                        });
                    }
                }
                draggedItem = null;
            });
        }

        function addContextMenuListeners() {
            const fileItems = document.querySelectorAll('.file-item');
            fileItems.forEach(item => {
                item.addEventListener('contextmenu', showContextMenu);
            });
        }

        // Inicializar listeners al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            addDragAndDropListeners();
            addContextMenuListeners();
        });

        // Funciones para comunicación con el servidor (a implementar)
        function moveFile(fileId, folderId) {
            // Implementar llamada AJAX para mover archivo
            fetch('../file_management/move_file.php', {
                method: 'POST',
                body: JSON.stringify({ fileId, folderId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      Swal.fire('¡Movido!', 'El archivo ha sido movido.', 'success');
                      // Actualizar vista
                  } else {
                      Swal.fire('Error', data.message, 'error');
                  }
              });
        }

        function renameItemOnServer(id, newName, type) {
            // Implementar llamada AJAX para renombrar
            fetch('../file_management/rename_item.php', {
                method: 'POST',
                body: JSON.stringify({ id, newName, type }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      Swal.fire('¡Renombrado!', 'El elemento ha sido renombrado.', 'success');
                      // Actualizar vista
                      currentContextItem.querySelector('h6').textContent = newName;
                  } else {
                      Swal.fire('Error', data.message, 'error');
                  }
              });
        }

        function deleteItemOnServer(id, type) {
            // Implementar llamada AJAX para eliminar
            fetch('../file_management/delete_item.php', {
                method: 'POST',
                body: JSON.stringify({ id, type }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      Swal.fire('¡Eliminado!', 'El elemento ha sido eliminado.', 'success');
                      // Eliminar elemento de la vista
                      currentContextItem.remove();
                  } else {
                      Swal.fire('Error', data.message, 'error');
                  }
              });
        }
    });
</script>
</body>
</html>
