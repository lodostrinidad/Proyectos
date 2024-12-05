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

<div class="container mt-5">
    <h1 class="text-center">Gestión de Usuarios</h1>
    
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar Nuevo Usuario</button>

    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser(<?php echo $user['id']; ?>)">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Manejar el envío del formulario de creación
    $('#createUserForm').on('submit', function(event) {
        event.preventDefault();
        const username = $('#createUsername').val();
        const email = $('#createEmail').val();
        const role = $('#createRole').val();
        const password = $('#createPassword').val();

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
                    }).then(() => location.reload());
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
                    }).then(() => location.reload());
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

function deleteUser(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás recuperar este usuario después de eliminarlo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
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
                            title: 'Eliminado',
                            text: 'Usuario eliminado correctamente',
                            icon: 'success',
                            timer: 1200, // Mensaje visible por 1.2 segundos
                            showCancelButton: false,
                            showConfirmButton: false
                        }).then(() => location.reload());
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
</script>
