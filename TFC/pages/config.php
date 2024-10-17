<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    // Actualizar email y contraseña del usuario
    $query = "UPDATE usuarios SET email = ?, password = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $new_email, $new_password, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Configuración actualizada.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la configuración.']);
    }
}
?>

<div class="container">
    <h2>Configuración de Usuario</h2>
    <form method="POST" id="configForm">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Nueva Contraseña:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Actualizar</button>
    </form>
</div>

<script>
$('#configForm').on('submit', function (event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: 'config.php',
        method: 'POST',
        data: formData,
        success: function (response) {
            var data = JSON.parse(response);
            alert(data.message);
        },
        error: function () {
            alert('Error al actualizar los datos.');
        }
    });
});
</script>
