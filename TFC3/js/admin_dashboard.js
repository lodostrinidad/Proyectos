document.addEventListener("DOMContentLoaded", function () {
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no se ha cargado correctamente.');
        return;
    }

    // Función para cargar los datos de la base de datos
    async function loadDashboardData() {
        try {
            const response = await fetch('../includes/get_dashboard_data.php');
            const data = await response.json();
            
            // Mostrar total de usuarios
            document.getElementById('totalUsers').innerText = data.total_users;

            // Datos para el gráfico de distribución por rol
            const roles = data.roles.map(role => role.role);
            const counts = data.roles.map(role => role.count);
            
            // Crear gráfico
            createRoleDistributionChart(roles, counts);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    loadDashboardData();
});

document.addEventListener("DOMContentLoaded", function () {
    const messageForm = document.getElementById('messageForm');
    const messageList = document.getElementById('messageList');
    const messageContent = document.getElementById('messageContent');

    messageForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const messageText = messageContent.value.trim();
        if (messageText) {
            // Simulate sending the message
            const listItem = document.createElement('li');
            listItem.textContent = messageText;
            listItem.classList.add('list-group-item');
            messageList.prepend(listItem);
            messageContent.value = '';
            // Here you would typically send the message to the server
        }
    });

    const adminMessageForm = document.getElementById('adminMessageForm');
    const adminMessageContent = document.getElementById('adminMessageContent');
    const readStatusList = document.getElementById('readStatusList');

    adminMessageForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const messageText = adminMessageContent.value.trim();
        if (messageText) {
            // Simulate sending the message to the server
            fetch('/send_admin_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: messageText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    adminMessageContent.value = '';
                    // Update read status list
                    updateReadStatus();
                }
            });
        }
    });

    function updateReadStatus() {
        // Fetch read status from the server
        fetch('/get_read_status.php')
            .then(response => response.json())
            .then(data => {
                readStatusList.innerHTML = '';
                data.forEach(status => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${status.user}: ${status.read ? 'Leído' : 'No leído'}`;
                    listItem.classList.add('list-group-item');
                    readStatusList.appendChild(listItem);
                });
            });
    }

    updateReadStatus();

    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.querySelector('.sidebar');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Ensure dropdown functionality works across all tabs
    const dropdownToggleList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownToggleList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const target = this.getAttribute('href');
            if (!this.classList.contains('active')) { // Only load content if not active
                navLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
                if (target === 'admin_dashboard.php') {
                    window.location.href = target;
                } else if (target) {
                    loadContent(target);
                }
            }
        });
    });

    function loadContent(url) {
        const content = document.getElementById('content');
        fetch(url)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
                // Re-initialize Bootstrap components
                const newDropdownToggleList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                newDropdownToggleList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
                // Re-initialize modals
                const modals = [].slice.call(document.querySelectorAll('.modal'));
                modals.map(function (modalEl) {
                    return new bootstrap.Modal(modalEl);
                });
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const userButton = document.getElementById('userButton');

    if (userButton) {
        userButton.addEventListener('click', function() {
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('show');
            }
        });
    }

    const individualChatsLink = document.getElementById('individualChatsLink');
    const content = document.getElementById('content');

    individualChatsLink.addEventListener('click', function(event) {
        event.preventDefault();
        loadContent('individual_chats.php');
    });

    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.querySelector('.sidebar');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    // Display admin message popup for users
    fetch('/get_admin_messages.php')
        .then(response => response.json())
        .then(messages => {
            if (messages.length > 0) {
                Swal.fire({
                    title: 'Mensaje de Admin',
                    text: messages[0].message_content,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });
});

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('table-item')) {
        const tableName = event.target.getAttribute('data-table-name');

        // Hacer una solicitud para obtener los detalles de la tabla
        fetch('../includes/get_table_details.php?table=' + tableName)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los detalles de la tabla');
                }
                return response.text();
            })
            .then(html => {
                // Insertar los detalles de la tabla en el div table-details
                document.getElementById('table-details').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
