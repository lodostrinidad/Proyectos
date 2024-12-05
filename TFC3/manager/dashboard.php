<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Verificar si el usuario es encargado
if (!isManager()) {
    header('Location: ../index.php');
    exit();
}

$totalFiles = getTotalFiles();
$files = getFilesForManager($_SESSION['user_id']);

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Dashboard del Encargado</h2>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total de Archivos</h5>
                            <p class="card-text"><?php echo $totalFiles; ?></p>
                        </div>
                    </div>
                </div>
            </div>

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
