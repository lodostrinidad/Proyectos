<?php
session_start();
require 'includes/db_connect.php'; 

// Mensajes de sesión
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Archivos</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <!-- Header Sticky con Login/Register -->
    <header class="sticky-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="#" class="logo">ShadoW Dev <img src="uploads/logo1.png" alt="Logo ShadoW Dev" style="height: 80px; margin-right: 10px;"></a>
            <nav>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">Login / Register</button>
            </nav>
        </div>
    </header>

    <!-- Sección 1 con Parallax y Botón de Portafolio -->
    <section class="parallax-section section-1">
        <div class="content">
            <h1>Bienvenido al Gestor de Archivos</h1>
            <p>Organiza, gestiona y comparte tus archivos con facilidad.</p>
            <a href="https://alodos3.github.io" class="btn btn-warning">Visita mi Portafolio</a>
        </div>
    </section>

    <!-- Sección 2 con Carrusel, Descripción y Características -->
    <section class="content-section">
        <div class="container d-flex align-items-center">
            <div id="carouselExampleControls" class="carousel slide w-50" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="uploads/imagen1.jpg" class="d-block w-100" alt="Ejemplo 1">
                    </div>
                    <div class="carousel-item">
                        <img src="uploads/imagen1.jpg" class="d-block w-100" alt="Ejemplo 2">
                    </div>
                    <div class="carousel-item">
                        <img src="uploads/imagen1.jpg" class="d-block w-100" alt="Ejemplo 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>

            <div class="description ms-5">
                <h2>Gestor de Archivos Inteligente</h2>
                <p>Esta aplicación te permitirá gestionar tus archivos de forma eficiente. Con características de subida, organización y compartición de archivos, es la herramienta perfecta para tu flujo de trabajo.</p>

                <!-- Lista de características -->
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-upload"></i>
                        <h4>Subida rápida</h4>
                        <p>Sube archivos de gran tamaño sin problemas.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-share-alt"></i>
                        <h4>Fácil compartición</h4>
                        <p>Comparte tus archivos de forma segura con un solo clic.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-lock"></i>
                        <h4>Seguridad avanzada</h4>
                        <p>Protege tus archivos con nuestras medidas de seguridad avanzadas.</p>
                    </div>
                </div>

                <!-- Tabla comparativa -->
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Características</th>
                            <th>Gratis</th>
                            <th>Premium</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Almacenamiento</td>
                            <td>5 GB</td>
                            <td>100 GB</td>
                        </tr>
                        <tr>
                            <td>Compartición</td>
                            <td>Hasta 5 usuarios</td>
                            <td>Ilimitado</td>
                        </tr>
                        <tr>
                            <td>Soporte</td>
                            <td>Email</td>
                            <td>Email y Teléfono</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Sección 3 con Parallax, Testimonios y Formulario -->
    <section class="parallax-section section-3">
        <div class="content">
            <h2>Transforma la manera en que manejas tus archivos</h2>
            <p>Haz que el trabajo sea más sencillo y eficiente con nuestras soluciones.</p>
            
            <!-- Testimonios -->
            <div class="testimonials">
                <div class="testimonial">
                    <img src="uploads/imagen1.jpg" alt="Usuario 1">
                    <p>"El gestor de archivos más eficiente que he usado. ¡Lo recomiendo!"</p>
                    <span>- Juan Pérez</span>
                </div>
                <div class="testimonial">
                    <img src="uploads/imagen1.jpg" alt="Usuario 2">
                    <p>"Facilita mi trabajo diario y mantiene mis archivos organizados."</p>
                    <span>- María López</span>
                </div>
            </div>

            <!-- Formulario de contacto -->
            <form class="contact-form mt-4">
                <h3>Contáctanos</h3>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Tu Nombre" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Tu Email" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="4" placeholder="Tu Mensaje" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </section>

    <!-- Modal con Pestañas para Login/Register -->
    <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Acceder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="authTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Registrarse</a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content" id="authTabContent">
                        <!-- Login -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                            <form id="loginForm" class="mt-3">
                                <div class="form-group">
                                    <label for="loginUsername">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="loginUsername" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="loginPassword">Contraseña</label>
                                    <input type="password" class="form-control" id="loginPassword" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                            </form>
                            <div id="loginMessage" class="mt-3"></div>
                        </div>

                        <!-- Registro -->
                        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <form id="registerForm" class="mt-3">
                                <div class="form-group">
                                    <label for="registerUsername">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="registerUsername" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="registerEmail">Email</label>
                                    <input type="email" class="form-control" id="registerEmail" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="registerPassword">Contraseña</label>
                                    <input type="password" class="form-control" id="registerPassword" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                            </form>
                            <div id="registerMessage" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center mt-5">
        <div class="container">
            <p>&copy; 2024 ShadoW Dev. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>