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
