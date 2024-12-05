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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header Sticky con Login -->
    <header class="sticky-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="#" class="logo">ShadoW Dev <img src="uploads/logo1.png" alt="Logo ShadoW Dev" style="height: 80px; margin-right: 10px;"></a>
            <nav>
                <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#authModal">
                    <i class="fas fa-user me-2"></i>Iniciar Sesión
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section con Parallax Mejorado -->
    <section class="parallax-section section-1 d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white animate-fadeInUp">
                    <h1 class="display-4 fw-bold gradient-text mb-4">
                        ShadoW Dev File Manager
                    </h1>
                    <p class="lead mb-5">
                        Transforma la gestión de tus archivos con una solución inteligente, segura y eficiente.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="https://aLodos3.github.io" class="btn btn-portfolio btn-advanced shadow-lg">
                            <i class="fas fa-briefcase me-2"></i>Ver Portafolio
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-advanced">
                            <i class="fas fa-info-circle me-2"></i>Más Información
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/File%20Cabinet.png" 
                        alt="File Management" 
                        class="img-fluid animate-float" 
                        style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Estadísticas -->
    <section class="py-5 bg-secondary">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-users fa-2x mb-3 gradient-text"></i>
                        <div class="stats-number">10K+</div>
                        <p>Usuarios Activos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-3 gradient-text"></i>
                        <div class="stats-number">5M+</div>
                        <p>Archivos Subidos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-shield-alt fa-2x mb-3 gradient-text"></i>
                        <div class="stats-number">99.9%</div>
                        <p>Uptime</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-star fa-2x mb-3 gradient-text"></i>
                        <div class="stats-number">4.9</div>
                        <p>Calificación</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Características Destacadas -->
    <section id="features" class="container py-5">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-6 fw-bold gradient-text">Características Principales</h2>
                <p class="lead text-muted">Descubre por qué ShadoW Dev es la mejor solución para gestionar tus archivos</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card interactive-element shadow-lg">
                    <i class="fas fa-cloud-upload-alt fa-3x gradient-text mb-3"></i>
                    <h3 class="h4 mb-3">Subida Rápida</h3>
                    <p class="text-muted">Sube archivos de gran tamaño de manera instantánea y segura.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card interactive-element shadow-lg">
                    <i class="fas fa-lock fa-3x gradient-text mb-3"></i>
                    <h3 class="h4 mb-3">Seguridad Avanzada</h3>
                    <p class="text-muted">Protección de última generación para tus documentos más importantes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card interactive-element shadow-lg">
                    <i class="fas fa-share-alt fa-3x gradient-text mb-3"></i>
                    <h3 class="h4 mb-3">Compartición Fácil</h3>
                    <p class="text-muted">Comparte archivos con un solo clic, sin complicaciones.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Testimonios Mejorada -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-6 fw-bold gradient-text">Lo Que Dicen Nuestros Usuarios</h2>
                    <p class="lead text-muted">Experiencias reales de profesionales que han transformado su gestión de archivos</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card interactive-element shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="María García" class="mb-3">
                        <h4 class="h5 mb-3">María García</h4>
                        <p class="text-muted">"ShadoW Dev ha revolucionado mi forma de trabajar. Ahora gestiono mis archivos con total tranquilidad."</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card interactive-element shadow-lg">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Carlos Rodríguez" class="mb-3">
                        <h4 class="h5 mb-3">Carlos Rodríguez</h4>
                        <p class="text-muted">"La seguridad y velocidad de ShadoW Dev no tienen comparación. ¡Altamente recomendado!"</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card interactive-element shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Ana Martínez" class="mb-3">
                        <h4 class="h5 mb-3">Ana Martínez</h4>
                        <p class="text-muted">"Finalmente, un gestor de archivos que entiende las necesidades de los profesionales modernos."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Llamada a la Acción Final -->
    <section class="parallax-section section-3 text-white text-center">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4 gradient-text">Transforma Tu Gestión de Archivos Hoy</h2>
            <p class="lead mb-5">Únete a miles de profesionales que ya han mejorado su productividad con ShadoW Dev.</p>
            <div class="d-flex justify-content-center gap-3">
                <button class="btn btn-portfolio btn-advanced shadow-lg" data-bs-toggle="modal" data-bs-target="#authModal">
                    <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                </button>
                <a href="#features" class="btn btn-outline-light btn-advanced">
                    <i class="fas fa-info-circle me-2"></i>Más Detalles
                </a>
            </div>
        </div>
    </section>

    <!-- Modal de Login Mejorado -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title gradient-text" id="authModalLabel">
                        <i class="fas fa-lock me-2"></i>Iniciar Sesión
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="loginForm" class="login-form" action="login.php" method="POST">
                        <div class="form-group mb-4">
                            <label for="loginUsername" class="form-label text-light">
                                <i class="fas fa-user me-2"></i>Usuario
                            </label>
                            <input type="text" class="form-control" id="loginUsername" name="username" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="loginPassword" class="form-label text-light">
                                <i class="fas fa-key me-2"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="loginPassword" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label text-light" for="rememberMe">
                                Recordarme
                            </label>
                        </div>
                        <button type="submit" class="btn btn-login w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="gradient-text mb-3">Sobre Nosotros</h5>
                    <p class="text-muted">Transformando la gestión de archivos con tecnología de vanguardia.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="gradient-text mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-muted">Características</a></li>
                        <li><a href="#" class="text-muted">Soporte</a></li>
                        <li><a href="#" class="text-muted">Términos</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="gradient-text mb-3">Contacto</h5>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>info@shadowdev.com<br>
                        <i class="fas fa-phone me-2"></i>+1 234 567 890
                    </p>
                </div>
            </div>
            <hr class="my-4" style="border-color: var(--border-color)">
            <p class="text-muted">&copy; 2024 ShadoW Dev. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // Evita el envío del formulario por defecto

            $.ajax({
                type: 'POST',
                url: 'login.php',
                data: $(this).serialize(), // Serializa los datos del formulario
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Redirige al usuario a la página correspondiente
                        window.location.href = response.redirect;
                    } else {
                        // Muestra el mensaje de error
                        $('#loginMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#loginMessage').html('<div class="alert alert-danger">Error al intentar iniciar sesión. Por favor, inténtalo de nuevo más tarde.</div>');
                }
            });
        });
    });
    </script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>