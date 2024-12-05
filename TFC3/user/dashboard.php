<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Verificar si el usuario es normal
if (!isUser()) {
    header('Location: ../index.php');
    exit();
}

$files = getFilesForUser($_SESSION['user_id']);

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Dashboard del Usuario</h2>

            <h3>Tus Archivos</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file) : ?>
                        <tr>
                            <td><?php echo $file['id']; ?></td>
                            <td><?php echo $file['name']; ?></td>
                            <td>
                                <a href="javascript:void(0);" onclick="deleteFile(<?php echo $file['id']; ?>);" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
