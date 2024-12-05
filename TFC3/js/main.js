// Manejar el envío del formulario de inicio de sesión
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío normal del formulario

    var formData = new FormData(this); // Recoger los datos del formulario

    // Enviar datos usando Fetch API
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar mensaje en el modal
        document.getElementById('loginMessage').innerText = data.message;

        // Si el inicio de sesión es exitoso, redirigir
        if (data.success) {
            setTimeout(() => {
                window.location.href = data.redirect; // Redirige al dashboard correspondiente
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loginMessage').innerText = "Error en la solicitud.";
    });
});
