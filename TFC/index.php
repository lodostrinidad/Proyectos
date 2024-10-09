<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Proyecto TFC</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Enlaza tu archivo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <script src="assets/js/script.js" defer></script> <!-- Enlaza tu archivo JavaScript -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- jQuery Completo -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#home">Mi Proyecto TFC</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#about">Sobre Mí</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#authModal">Login / Registro</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <section class="parallax" id="home">
        <div class="parallax-content">
            <h2>Bienvenido a Mi Proyecto</h2>
            <p>Una breve descripción sobre mi proyecto TFC.</p>
            <a href="#services" class="btn">Descubre Más</a>
        </div>
    </section>

    <div class="spacer"></div> <!-- Espaciador -->

    <section id="parallax-section" class="parallax-bg">
    <div class="section" id="about">
        <h2>Sobre Mí</h2>
        <p>Información interesante sobre mí y mi proyecto.</p>
    </div>

    <div class="section" id="services">
        <h2>Servicios</h2>
        <p>Descripción de los servicios que ofreces.</p>
    </div>

    <div class="section" id="contact">
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



<div class="spacer"></div> <!-- Espaciador -->

<footer class="footer bg-dark text-white">
    <div class="container text-center">
        <p>&copy; 2024 Mi Proyecto TFC. Todos los derechos reservados.</p>
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com" target="_blank" class="text-white"><i class="fab fa-twitter"></i></a>
            <a href="https://instagram.com" target="_blank" class="text-white"><i class="fab fa-instagram"></i></a>
            <a href="https://linkedin.com" target="_blank" class="text-white"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</footer>

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
                    <!-- Nav pills -->
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#regis">Registro</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="login" class="container tab-pane active">
                            <form>
                                <div class="form-group">
                                    <label for="loginEmail">Email address</label>
                                    <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" required>
                                    <small class="form-text text-muted">No compartiremos tu email con nadie más.</small>
                                </div>
                                <div class="form-group">
                                    <label for="loginPassword">Contraseña</label>
                                    <input type="password" class="form-control" id="loginPassword" placeholder="Contraseña" required>
                                    <small class="form-text text-muted">Contraseña incorrecta.</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </form>
                        </div>
                        <div id="regis" class="container tab-pane fade">
                            <form>
                                <div class="form-group">
                                    <label for="inputName">Nombre Completo</label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Nombre Completo" required>
                                    <small class="form-text text-muted">No compartiremos tu nombre con nadie más.</small>
                                </div>
                                <div class="form-group">
                                    <label for="inputUsername">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="inputUsername" placeholder="Nombre de Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="registerEmail">Email address</label>
                                    <input type="email" class="form-control" id="registerEmail" placeholder="name@example.com" required>
                                    <small class="form-text text-muted">No compartiremos tu email con nadie más.</small>
                                </div>
                                <div class="form-group">
                                    <label for="registerPassword">Contraseña</label>
                                    <input type="password" class="form-control" id="registerPassword" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group">
                                    <label for="registerPasswordVer">Verificar Contraseña</label>
                                    <input type="password" class="form-control" id="registerPasswordVer" placeholder="Contraseña" required>
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
