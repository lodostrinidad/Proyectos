<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Proyecto TFC</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Enlaza tu archivo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <script src="../assets/js/script.js" defer></script> <!-- Enlaza tu archivo JavaScript -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- jQuery Completo -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="navbar-brand">Adrian Lodos Trinidad</div>
                <a class="navbar-brand mx-auto" href="index.php" style="flex: 1; text-align: center;">
                    <img src="../assets/images/logo.png" alt="Logo" style="height: 50px;"> <!-- Logo -->
                </a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#authModal">Login / Registro</button>
            </div>
        </nav>
    </header>

    <section class="parallax" style="background-image: url('../assets/images/imagen3.jpg');">
        <div class="parallax-content">
            <h2>Bienvenido a Mi Proyecto</h2>
            <p>Una breve descripción sobre mi proyecto TFC.</p>
            <a href="#services" class="btn">Descubre Más</a>
        </div>
    </section>

    <div class="spacer"></div> <!-- Espaciador -->

    <div class="container">
        <section id="about" class="parallax section">
            <div class="section-content">
                <h2>Sobre Mí</h2>
                <p>Información interesante sobre mí y mi proyecto.</p>
            </div>
        </section>

        <section id="services" class="parallax section">
            <div class="section-content">
                <h2>Servicios</h2>
                <p>Descripción de los servicios que ofreces.</p>
            </div>
        </section>

        <section id="contact" class="parallax section">
            <div class="section-content">
                <h2>Contacto</h2>
                <form id="contact-form">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </section>
    </div>
    
    <div class="spacer"></div> <!-- Espaciador -->

    <!-- Modal para Registro/Login -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Registro/Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#register">Registro</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="login">
                            <form id="loginForm" method="POST"> <!-- Cambiado a id="loginForm" -->
                                <div class="form-group">
                                    <label for="username">Usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="register">
                            <form id="registerForm" method="POST"> <!-- Acción para el registro -->
                                <div class="form-group">
                                    <label for="usernameReg">Usuario</label>
                                    <input type="text" class="form-control" id="usernameReg" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="emailReg">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="emailReg" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="passwordReg">Contraseña</label>
                                    <input type="password" class="form-control" id="passwordReg" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php require_once '../includes/footer.php'; // Incluye el pie de página ?>