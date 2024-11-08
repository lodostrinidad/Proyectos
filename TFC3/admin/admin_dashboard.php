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
<header>
    <div class="header-logo">
        <img src="../uploads/logo1.png" alt="Logo" />
        <span class="header-title">ShadoW Dev</span>
    </div>
    <button id="toggleButton" class="btn-user">☰</button> <!-- Icono de menú -->
    <div class="dropdown">
        <button class="btn btn-user" id="userButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>&nbsp;
            <?php
            session_start();
            $username = $_SESSION['username'] ?? 'Usuario';
            echo htmlspecialchars($username);
            ?>
        </button>
        <ul id="userDropdown" class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Perfil</a></li>
            <li><a class="dropdown-item" href="#">Configuraciones</a></li>
            <li><a class="dropdown-item" href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</header>

<div class="d-flex">
    <!-- Sidebar fijo -->
    <div class="sidebar" id="sidebar">
        <nav class="nav flex-column">
            <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i><span> Dashboard</span></a>
            <a class="nav-link" id="usersLink" href="#"><i class="fas fa-users"></i><span> Usuarios</span></a>
            <a class="nav-link" id="tablesLink" href="#"><i class="fas fa-table"></i><span> Tablas</span></a>
            <a class="nav-link" id="chartsLink" href="#"><i class="fas fa-chart-line"></i><span> Gráficos</span></a>
        </nav>
    </div>

    <div class="content" id="content">
        <!-- Contenido estático del dashboard que se muestra inicialmente -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Total de Usuarios</h5>
                        <h3 id="totalUsers">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Total de Archivos</h5>
                        <h3 id="totalFiles">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Solicitudes Pendientes</h5>
                        <h3 id="pendingRequests">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Tareas Completadas</h5>
                        <h3>50%</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Distribución de Usuarios por Rol</div>
                    <div class="card-body">
                        <canvas id="roleDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Tendencia de Usuarios</div>
                    <div class="card-body">
                        <canvas id="userTrendChart"></canvas>
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

</body>
</html>
