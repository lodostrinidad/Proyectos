document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('start-scraper').addEventListener('click', () => {
        const loadingPopup = document.getElementById('loading-popup');
        loadingPopup.style.display = 'flex';

        // Obtener valores de los filtros
        const selectedLocation = document.getElementById('location').value; // Ubicación seleccionada
        const selectedKeyword = document.getElementById('keyword').value; // Palabra clave
        const selectedDate = document.getElementById('date').value; // Fecha seleccionada

        // Mapeo de ubicaciones a IDs de provincias
        const provinceMap = {
            madrid: 33,
            barcelona: 9,
            sevilla: 43,
            valencia: 49,
            ceuta: 15,
            malaga: 34,
        };

        // Crear objeto de mapeo para los filtros de fecha
        const dateMap = {
            ANY: 'ANY',
            '24h': '_24_HOURS',
            '7d': '_7_DAYS',
            '15d': '_15_DAYS'
        };

        // Obtener el ID de provincia correspondiente
        const selectedProvinceId = provinceMap[selectedLocation] || 'all';

        // Crear objeto de filtros para enviar al servidor
        const filters = {
            location: selectedLocation,
            keyword: selectedKeyword || '',
            date: dateMap[selectedDate] || 'ANY'
        };

        fetch('/scrape', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(filters) // Enviar filtros al servidor
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            loadingPopup.style.display = 'none';

            const jobTableBody = document.getElementById('job-table-body');
            jobTableBody.innerHTML = '';

            const totalTitles = data.length;
            document.getElementById('total-count').innerText = totalTitles;

            // Actualizar información de filtros usados
            const usedFilters = document.getElementById('used-filters');
            usedFilters.innerText = totalTitles > 0 
                ? `ofertas de ${selectedKeyword || 'cualquier tipo'} en ${selectedLocation.charAt(0).toUpperCase() + selectedLocation.slice(1)} de los últimos ${selectedDate === 'ANY' ? 'cualquier fecha' : selectedDate}`
                : 'No se encontraron ofertas.';

            data.forEach(job => {
                const row = document.createElement('tr');
                const location = job.location ? job.location : 'Ubicación no disponible';
                row.innerHTML = `
                    <td data-label="Título">${job.title || 'Título no disponible'}</td>
                    <td data-label="Ubicación">${location}</td>
                    <td data-label="VER"><a href="${job.link}" target="_blank" class="apply-btn">VER</a></td>
                `;
                jobTableBody.appendChild(row);
            });

            const totalTitlesDiv = document.getElementById('total-titles');
            totalTitlesDiv.style.display = totalTitles > 0 ? 'block' : 'none';
        })
        .catch(error => {
            loadingPopup.style.display = 'none';
            console.error('Error al ejecutar el scraping:', error);
            alert('Hubo un error al ejecutar el scraping. Revisa la consola para más detalles.');
        });
    });

    // Asigna las funciones de exportación a los botones
    document.getElementById('export-excel-btn').addEventListener('click', exportToExcel);
    document.getElementById('export-database-btn').addEventListener('click', exportToDatabase);
});

// Funciones para exportar a Excel y a la base de datos
function exportToExcel() {
    window.location.href = '/export-excel';
}

function exportToDatabase() {
    const jobTableBody = document.getElementById('job-table-body');
    const offers = Array.from(jobTableBody.rows).map(row => ({
        title: row.cells[0].innerText,
        link: row.cells[2].querySelector('a').href,
        location: row.cells[1].innerText
    }));

    fetch('/export-database', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(offers)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al exportar a la base de datos');
        }
        return response.json();
    })
    .then(data => {
        alert('Datos exportados a la base de datos con éxito.');
    })
    .catch(error => {
        console.error('Error al exportar a la base de datos:', error);
        alert('Error al exportar a la base de datos.');
    });
}
