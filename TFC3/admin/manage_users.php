<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .header {
            background: linear-gradient(45deg, #333333, #666666);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-container {
            margin-top: 30px;
        }

        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .table-dark {
            background-color: #343a40;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn-warning {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-warning:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #e9ecef;
            border-top: 5px solid #dc3545;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="container">
        <h1 class="text-center">Gestión de Usuarios</h1>
    </div>
</div>

<div class="container">
    <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="userInterface.showModal()">
            Añadir Usuario
        </button>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody"></tbody>
        </table>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Añadir Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" required>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
class UserAPI {
    static async getUsers() {
        const response = await fetch('../includes/get_users.php');
        if (!response.ok) throw new Error('Error al obtener usuarios');
        return await response.json();
    }

    static async createUser(formData) {
        const response = await fetch('../includes/admin_functions.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) throw new Error('Error al crear usuario');
        return await response.json();
    }

    static async updateUser(formData) {
        const response = await fetch('../includes/admin_functions.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) throw new Error('Error al actualizar usuario');
        return await response.json();
    }

    static async deleteUser(id) {
        const formData = new FormData();
        formData.append('delete_user', true);
        formData.append('id', id);
        const response = await fetch('../includes/admin_functions.php', {
            method: 'POST',
            body: formData
        });
        if (!response.ok) throw new Error('Error al eliminar usuario');
        return await response.json();
    }
}

class UserInterface {
    constructor() {
        this.form = document.getElementById('userForm');
        this.userIdInput = document.getElementById('userId');
        this.usernameInput = document.getElementById('username');
        this.passwordInput = document.getElementById('password');
        this.emailInput = document.getElementById('email');
        this.roleInput = document.getElementById('role');
        this.submitBtn = document.getElementById('submitBtn');
        this.tableBody = document.getElementById('userTableBody');
        this.modal = new bootstrap.Modal(document.getElementById('userModal'));
        this.modalTitle = document.getElementById('userModalLabel');

        this.bindEvents();
        this.loadUsers();
    }

    bindEvents() {
        this.submitBtn.addEventListener('click', () => this.handleSubmit());
    }

    async loadUsers() {
        try {
            const users = await UserAPI.getUsers();
            this.renderUsers(users);
        } catch (error) {
            this.showError('Error al cargar usuarios');
        }
    }

    renderUsers(users) {
        this.tableBody.innerHTML = '';
        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${new Date(user.created_at).toLocaleString()}</td>
                <td>${user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</td>
                <td>${user.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td>
                <td>
                    <button class="btn btn-warning" onclick="userInterface.editUser(${user.id})">Editar</button>
                    <button class="btn btn-danger" onclick="userInterface.deleteUser(${user.id})">Eliminar</button>
                </td>
            `;
            this.tableBody.appendChild(row);
        });
    }

    async deleteUser(id) {
        try {
            await UserAPI.deleteUser(id);
            this.loadUsers();
            this.showSuccess('Usuario eliminado con éxito');
        } catch (error) {
            this.showError(error.message);
        }
    }

    editUser(id) {
        const userRow = [...this.tableBody.rows].find(row => row.cells[0].innerText == id);
        if (userRow) {
            this.usernameInput.value = userRow.cells[1].innerText;
            this.emailInput.value = userRow.cells[2].innerText;
            this.roleInput.value = userRow.cells[3].innerText;
            this.userIdInput.value = id;
            this.modalTitle.innerText = 'Editar Usuario';
        }
        this.modal.show();
    }

    showModal() {
        this.form.reset();
        this.userIdInput.value = '';
        this.modalTitle.innerText = 'Añadir Usuario';
        this.modal.show();
    }

    async handleSubmit() {
        const formData = new FormData();
        formData.append('username', this.usernameInput.value);
        formData.append('email', this.emailInput.value);
        formData.append('role', this.roleInput.value);
        
        if (this.passwordInput.value) {
            formData.append('password', this.passwordInput.value);
        }

        if (this.userIdInput.value) {
            formData.append('user_id', this.userIdInput.value); // Cambiado a user_id para que coincida con PHP
        }

        console.log("Form data being sent:", Object.fromEntries(formData)); // Para verificar qué datos se están enviando

        try {
            if (this.userIdInput.value) {
                const updatedUser = await UserAPI.updateUser(formData);
                this.showSuccess('Usuario actualizado con éxito');
            } else {
                const newUser = await UserAPI.createUser(formData);
                this.showSuccess('Usuario creado con éxito');
                this.loadUsers(); // Recargar la lista de usuarios
            }
        } catch (error) {
            console.error("Error response:", error); // Para verificar el error en la consola
            this.showError(error.message);
        } finally {
            this.modal.hide();
        }
    }

    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: message,
        });
    }

    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
        });
    }
}

const userInterface = new UserInterface();
</script>
</body>
</html>
