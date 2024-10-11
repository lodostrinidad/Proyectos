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
                ? `${selectedKeyword || 'cualquier tipo'} en ${selectedLocation.charAt(0).toUpperCase() + selectedLocation.slice(1)} de los últimos ${selectedDate === 'ANY' ? 'cualquier fecha' : selectedDate}`
                : 'No se encontraron ofertas.';

            data.forEach(job => {
                const row = document.createElement('tr');
                const location = job.location ? job.location : 'Ubicación no disponible';
                const company = job.company ? job.company : 'Empresa no disponible'; // Usar la variable de empresa

                row.innerHTML = `
                    <td data-label="Título">${job.title || 'Título no disponible'}</td>
                    <td data-label="Ubicación">${location}</td>
                    <td data-label="Empresa">${company}</td> <!-- Añadir columna para la empresa -->
                    <td data-label="VER"><a href="${job.link}" target="_blank" class="apply-btn">VER</a></td>
                `;
                jobTableBody.appendChild(row);
            });

            const totalTitlesDiv = document.getElementById('total-titles');
            totalTitlesDiv.style.display = totalTitles > 0 ? 'block' : 'none';
        })
        .catch(error => {
            loadingPopup.style.display = 'none';
            console.error('Error:', error);
            alert('Ocurrió un error al ejecutar el scraper. Inténtalo de nuevo más tarde.');
        });
    });
});
