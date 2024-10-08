$(document).ready(function () {
    // Animación inicial de opacidad
    $(".readyfade").animate({ 'opacity': '1' }, 1000);

    // Cambiar el color de la barra de navegación al desplazarse
    var scroll_start = 0;
    var startchange = $('#startchange');

    if (startchange.length) {
        var offset = startchange.offset();

        if (screen.width >= 769) {
            $(document).scroll(function () {
                scroll_start = $(this).scrollTop();
                if (scroll_start > offset.top) {
                    $('#navbar1').css('background-color', '#364357');
                } else {
                    $('#navbar1').css('background-color', 'transparent');
                }
            });
        }
    }

    // Colapsa la barra de navegación al hacer clic en el enlace
    $(".navbar-nav li a").click(function () {
        $(".navbar-collapse").collapse('hide');
    });

    // Desplazamiento suave al hacer clic en enlaces internos
    $('a[href*="#"]:not([data-toggle="modal"])').on('click', function (event) {
        event.preventDefault(); // Previene el comportamiento predeterminado del enlace
        var targetID = $(this).attr('href'); // Obtener el ID del atributo href
        var target = $(targetID); // Seleccionar el elemento correspondiente

        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 800); // 800 ms para la animación
        } else {
            console.error("El objetivo no existe:", targetID);
            alert("No se pudo encontrar el objetivo: " + targetID);
        }
    });

    // Animación de elementos que aparecen al desplazarse
    $(window).scroll(function () {
        $('.hideme').each(function () {
            var top_of_object = $(this).position().top;
            var bottom_of_window = $(window).scrollTop() + $(window).height();
            // Si el objeto es completamente visible en la ventana, desvanecerlo
            if (bottom_of_window > top_of_object) {
                $(this).animate({ 'opacity': '1' }, 1200);
            }
        });
    });

    // Animación de desplazamiento al hacer clic en el botón de registro/login
    $('.btn-light').on('click', function () {
        var authModal = $("#authModal");
        if (authModal.length) {
            // Desplazamiento suave hacia el modal
            $('html, body').animate({
                scrollTop: authModal.offset().top
            }, 800);
        }
    });

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
                // Procesar los datos recibidos
                var content = '';
                $.each(data, function (index, post) {
                    content += `<h3>${post.title}</h3><p>${post.body}</p>`;
                });
                $('#dataContainer').html(content); // Añadir datos al contenedor
                $('#dataContainer').fadeIn(1000); // Efecto de aparición
            },
            error: function () {
                alert('Error al cargar los datos.');
            }
        });
    });
});
