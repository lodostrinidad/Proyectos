// Función debounce (definida en ámbito global)
function debounce(func, wait = 20, immediate = true) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

$(document).ready(function () {
    // Animación inicial de opacidad
    $(".readyfade").animate({ 'opacity': '1' }, 1000);

    // Cambiar el color de la barra de navegación al desplazarse
    var scroll_start = 0;
    var startchange = $('#startchange');

    if (startchange.length) {
        var offset = startchange.offset();

        if (screen.width >= 769) {
            $(document).scroll(debounce(function () {
                scroll_start = $(this).scrollTop();
                if (scroll_start > offset.top) {
                    $('#navbar1').css('background-color', '#364357');
                } else {
                    $('#navbar1').css('background-color', 'transparent');
                }
            }, 100));
        }
    }

    // Colapsar la barra de navegación al hacer clic en el enlace
    $(".navbar-nav li a").click(function () {
        $(".navbar-collapse").collapse('hide');
    });

    // Desplazamiento suave al hacer clic en enlaces internos
    $('a[href*="#"]:not([data-toggle="modal"])').on('click', function (event) {
        var targetID = $(this).attr('href');
        var target = $(targetID);

        if (target.length) {
            event.preventDefault(); // Solo evitar si el destino existe
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 800);
        } else {
            console.error("El objetivo no existe:", targetID);
            alert("No se pudo encontrar el objetivo: " + targetID);
        }
    });

    // Animación de elementos que aparecen al desplazarse (debounced scroll)
    $(window).on('scroll', debounce(function () {
        $('.hideme').each(function () {
            var top_of_object = $(this).position().top;
            var bottom_of_window = $(window).scrollTop() + $(window).height();
            if (bottom_of_window > top_of_object) {
                $(this).animate({ 'opacity': '1' }, 1200);
            }
        });
    }));

    // Manejar el clic en el botón para abrir el modal
    $('.btn-light').on('click', function () {
        var authModal = $("#authModal");
        if (authModal.length) {
            $('html, body').animate({
                scrollTop: authModal.offset().top
            }, 800);
        }
    });

    // Manejar el formulario de inicio de sesión
    $('#loginForm').on('submit', function (event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario
        var formData = $(this).serialize(); // Obtener los datos del formulario

        $.ajax({
            url: 'login_process.php', 
            method: 'POST',
            data: formData,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    if (data.role === 'admin') {
                        window.location.href = 'admin_dashboard.php'; // Redirige al dashboard de admin
                    } else if (data.role === 'usuario') {
                        window.location.href = 'user_dashboard.php'; // Redirige al dashboard de usuario
                    }
                } else {
                    // Mostrar mensaje de error
                    alert(data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error al procesar la solicitud:", textStatus, errorThrown);
                alert('Error al procesar la solicitud. Intenta nuevamente.');
            }
        });
    });

    // Manejar el formulario de registro
    $('#registerForm').on('submit', function (event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario
        var formData = $(this).serialize(); // Obtener los datos del formulario

        // Validaciones adicionales
        if (!validateEmail($('#emailReg').val())) {
            alert('Por favor, ingresa un correo electrónico válido.');
            return;
        }

        if ($('#password').val().length < 4) {
            alert('La contraseña debe tener al menos 4 caracteres.');
            return;
        }

        // Llamada AJAX
        $.ajax({
            url: 'register_process.php', // Cambia la URL si es necesario
            method: 'POST',
            data: formData,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    alert(data.message);
                    // Redirigir a otra página si es necesario
                    // window.location.href = 'login.php'; // Por ejemplo
                } else {
                    // Mostrar mensaje de error
                    alert(data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error al procesar la solicitud:", textStatus, errorThrown);
                alert('Error al procesar la solicitud. Intenta nuevamente.');
            }
        });
    });

    // Función para validar el formato del email
    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Ejemplo adicional de animación al hacer clic en un elemento específico
    $('#miElemento').click(function () {
        $(this).animate({ opacity: 0.5 }, 1000); // Ejemplo de animación
    });

    // Ejemplo de llamada AJAX para obtener datos (simulación)
    $('#loadDataBtn').click(function () {
        $.ajax({
            url: 'https://jsonplaceholder.typicode.com/posts', // URL de ejemplo
            method: 'GET',
            success: function (data) {
                var content = '';
                $.each(data, function (index, post) {
                    content += `<h3>${post.title}</h3><p>${post.body}</p>`;
                });
                $('#dataContainer').html(content).fadeIn(1000); // Añadir datos con efecto de aparición
            },
            error: function () {
                alert('Error al cargar los datos.');
            }
        });
    });
});

// Efecto de animación en las secciones al hacer scroll (mejorado con debounce)
document.addEventListener('scroll', debounce(function() {
    const sections = document.querySelectorAll('.section');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;

        if (scrollTop > sectionTop - window.innerHeight + sectionHeight / 4) {
            section.classList.add('visible');
        }
    });
}, 20));

let nav = document.querySelector(".navcontainer");
