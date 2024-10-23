let roleDistributionChart; // Variable global para mantener la instancia del gráfico

async function createRoleDistributionChart(roles, counts) {
    const ctx = document.getElementById('roleDistributionChart').getContext('2d');

    // Verificar si ya existe un gráfico y destruirlo
    if (roleDistributionChart) {
        roleDistributionChart.destroy();
    }

    // Crear el nuevo gráfico
    roleDistributionChart = new Chart(ctx, {
        type: 'bar', // Cambia a 'bar' para un gráfico de barras
        data: {
            labels: roles,
            datasets: [{
                label: 'Usuarios por Rol',
                data: counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
