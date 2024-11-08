<?php
session_start();
require '../includes/db_connect.php';
require '../includes/admin_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar las solicitudes POST
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                $password = $_POST['password']; // Capturamos la contraseña
                $result = createUser($username, $email, $role, $password); // Modificamos la llamada a la función
                echo json_encode($result);
                exit;

            case 'edit':
                $id = $_POST['id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                $password = $_POST['password'] ?? null; // Capturamos la contraseña, si se proporciona
                $result = editUser($id, $username, $email, $role, $password); // Modificamos la llamada a la función
                echo json_encode($result);
                exit;

            case 'delete':
                $id = $_POST['id'];
                $result = deleteUser($id);
                echo json_encode($result);
                exit;

            case 'get_user':
                $id = $_POST['id'];
                $user = getUser($id);
                echo json_encode($user);
                exit;
        }
    }
}

$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Usuarios</h1>
        
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar Nuevo Usuario</button>

        <h2>Lista de Usuarios</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre de usuario</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editUser(<?php echo $user['id']; ?>)">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal para Agregar Usuario -->
        <div id="addUserModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createUserForm" style="color: black;">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario:</label>
                                <input type="text" id="username" class="form-control" required autocomplete="username"> <!-- Autocomplete agregado -->
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" id="email" class="form-control" required autocomplete="email"> <!-- Autocomplete agregado -->
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Rol:</label>
                                <select id="role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Encargado</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" id="password" class="form-control" required autocomplete="current-password"> <!-- Autocomplete agregado -->
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Usuario -->
        <div id="editUserModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" style="color: black;">
                            <input type="hidden" id="editUserId">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Nombre de usuario:</label>
                                <input type="text" id="editUsername" class="form-control" required autocomplete="username"> <!-- Autocomplete agregado -->
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Correo electrónico:</label>
                                <input type="email" id="editEmail" class="form-control" required autocomplete="email"> <!-- Autocomplete agregado -->
                            </div>
                            <div class="mb-3">
                                <label for="editRole" class="form-label">Rol:</label>
                                <select id="editRole" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Encargado</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editPassword" class="form-label">Contraseña:</label>
                                <input type="password" id="editPassword" class="form-control" autocomplete="current-password"> <!-- Autocomplete agregado -->
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            // Manejar el envío del formulario de creación
            $('#createUserForm').on('submit', function(event) {
                event.preventDefault();
                const username = $('#username').val();
                const email = $('#email').val();
                const role = $('#role').val();
                const password = $('#password').val(); // Capturamos la contraseña

                $.ajax({
                    url: 'manage_users.php',
                    type: 'POST',
                    data: { action: 'create', username, email, role, password },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: 'Usuario creado correctamente',
                                icon: 'success',
                                timer: 1200, // Mensaje visible por 1.2 segundos
                                showCancelButton: false,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Recargar la página después de mostrar el mensaje
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al crear usuario: ' + result.error,
                                icon: 'error',
                                timer: 1200, // Mensaje visible por 1.2 segundos
                                showCancelButton: false,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });
        });

        function editUser(id) {
            $.ajax({
                url: 'manage_users.php',
                type: 'POST',
                data: { action: 'get_user', id },
                success: function(response) {
                    const user = JSON.parse(response);
                    $('#editUserId').val(user.id);
                    $('#editUsername').val(user.username);
                    $('#editEmail').val(user.email);
                    $('#editRole').val(user.role);
                    $('#editUserModal').modal('show');
                }
            });
        }

        $('#editUserForm').on('submit', function(event) {
            event.preventDefault();
            const id = $('#editUserId').val();
            const username = $('#editUsername').val();
            const email = $('#editEmail').val();
            const role = $('#editRole').val();
            const password = $('#editPassword').val() || null; // Capturamos la contraseña si se proporciona

            $.ajax({
                url: 'manage_users.php',
                type: 'POST',
                data: { action: 'edit', id, username, email, role, password },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: 'Usuario actualizado correctamente',
                            icon: 'success',
                            timer: 1200, // Mensaje visible por 1.2 segundos
                            showCancelButton: false,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Recargar la página después de mostrar el mensaje
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al actualizar usuario: ' + result.error,
                            icon: 'error',
                            timer: 1200, // Mensaje visible por 1.2 segundos
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });

        function deleteUser(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás recuperar este usuario después de eliminarlo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'manage_users.php',
                        type: 'POST',
                        data: { action: 'delete', id },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                Swal.fire({
                                    title: 'Éxito',
                                    text: 'Usuario eliminado correctamente',
                                    icon: 'success',
                                    timer: 1200, // Mensaje visible por 1.2 segundos
                                    showCancelButton: false,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // Recargar la página después de mostrar el mensaje
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Error al eliminar usuario: ' + result.error,
                                    icon: 'error',
                                    timer: 1200, // Mensaje visible por 1.2 segundos
                                    showCancelButton: false,
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        }

        function closeEditModal() {
            $('#editUserModal').modal('hide');
        }
    </script>
</body>
</html>
