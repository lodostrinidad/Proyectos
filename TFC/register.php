<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form method="POST" action="register.php">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            <input type="email" name="correo" class="form-control" placeholder="Correo" required>
            <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <?php
    // Registro de usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'db.php';

        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Usar password_hash

        $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES ('$nombre', '$correo', '$contrasena')";
        if ($conexion->query($sql) === TRUE) {
            echo "<p>Registro exitoso. Puedes iniciar sesión ahora.</p>";
        } else {
            echo "<p>Error: " . $conexion->error . "</p>";
        }

        $conexion->close();
    }
    ?>
</body>
</html>
