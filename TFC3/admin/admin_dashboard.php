<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Custom CSS -->
    <link href="../css/admin_dashboard.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header class="d-flex justify-content-between align-items-center p-3 bg-dark text-white">
    <div class="d-flex align-items-center">
        <img src="../uploads/logo1.png" alt="Logo" class="me-2" style="height: 50px;"/>
        <span class="header-title fs-4">ShadoW Dev</span>
    </div>
    <button id="toggleButton" class="btn btn-secondary d-md-none">
        ☰
    </button>
    <button class="btn btn-secondary" id="logoutButton">
        <i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión
    </button>
</header>

    <div class="d-flex">
        <!-- Sidebar fijo -->
        <div class="sidebar bg-dark" id="sidebar">
        <nav class="nav flex-column">
            <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i><span> Dashboard</span></a>
            <a class="nav-link" id="usersLink" href="#"><i class="fas fa-users"></i><span> Usuarios</span></a>
            <a class="nav-link" id="individualChatsLink" href="#"><i class="fas fa-comments"></i><span> Chats Individuales</span></a>
            <a class="nav-link" id="tablesLink" href="#"><i class="fas fa-table"></i><span> Tablas</span></a>
        </nav>
    </div>

    <div class="content p-4" id="content">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total de Usuarios</h5>
                        <h3 id="totalUsers" class="text-primary">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total de Archivos</h5>
                        <h3 id="totalFiles" class="text-primary">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Solicitudes Pendientes</h5>
                        <h3 id="pendingRequests" class="text-primary">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Tareas Completadas</h5>
                        <h3 class="text-primary">50%</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Distribución de Usuarios por Rol</div>
                    <div class="card-body">
                        <canvas id="roleDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Tendencia de Usuarios</div>
                    <div class="card-body">
                        <canvas id="userTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Mensajes</div>
                    <div class="card-body">
                        <form id="messageForm">
                            <div class="mb-3">
                                <label for="messageContent" class="form-label">Escribir mensaje</label>
                                <textarea class="form-control" id="messageContent" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                        <hr>
                        <h6>Mensajes Recientes</h6>
                        <ul id="messageList" class="list-group">
                            <!-- Mensajes recientes se mostrarán aquí -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/chart.js"></script>
<script src="../js/admin_dashboard.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleButton');
        const sidebar = document.querySelector('.sidebar');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });

    // Cargar Usuarios
    document.getElementById('usersLink').addEventListener('click', function(event) {
        event.preventDefault();
        loadContent('manage_users.php');
    });

    // Cargar Tablas
    document.getElementById('tablesLink').addEventListener('click', function(event) {
        event.preventDefault();
        loadContent('../includes/show_tables.php');
    });

    function loadContent(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar el contenido');
                }
                return response.text();
            })
            .then(html => {
                document.querySelector('.content').innerHTML = html;
                let scripts = document.querySelectorAll('.content script');
                scripts.forEach(script => {
                    let newScript = document.createElement('script');
                    if (script.src) {
                        newScript.src = script.src;
                    } else {
                        newScript.textContent = script.textContent;
                    }
                    document.body.appendChild(newScript);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleButton');
        const sidebar = document.getElementById('sidebar');

        if (toggleButton && sidebar) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
    });
</script>

</body>
</html>
