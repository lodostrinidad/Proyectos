<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php">
            <input type="email" name="correo" class="form-control" placeholder="Correo" required>
            <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>

    <?php
    // Iniciar sesión
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'db.php';

        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($contrasena, $row['contrasena'])) {
                $_SESSION['usuario_id'] = $row['id'];
                header("Location: user_dashboard.php");
                exit();
            } else {
                echo "<p>Contraseña incorrecta.</p>";
            }
        } else {
            echo "<p>No se encontró el usuario.</p>";
        }

        $conexion->close();
    }
    ?>
</body>
</html>
