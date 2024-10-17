document.addEventListener('DOMContentLoaded', () => {
    // Iniciar el scraper al hacer clic en el botón
    document.getElementById('start-scraper').addEventListener('click', () => {
        const loadingPopup = document.getElementById('loading-popup');
        loadingPopup.style.display = 'flex';

        // Obtener valores de los filtros
        const selectedLocation = document.getElementById('location').value;
        const selectedKeyword = document.getElementById('keyword').value;
        const selectedDate = document.getElementById('date').value;

        // Mapeo de ubicaciones a IDs de provincias
        const provinceMap = {
            madrid: 33,
            barcelona: 9,
            sevilla: 43,
            valencia: 49,
            ceuta: 15,
            malaga: 34,
        };

        // Mapeo para filtros de fecha
        const dateMap = {
            ANY: 'ANY',
            '24h': '_24_HOURS',
            '7d': '_7_DAYS',
            '15d': '_15_DAYS'
        };

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
            body: JSON.stringify(filters)
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            loadingPopup.style.display = 'none';
            const jobTableBody = document.getElementById('job-table-body');
            jobTableBody.innerHTML = '';

            const totalTitles = data.length;
            document.getElementById('total-count').innerText = totalTitles;

            const usedFilters = document.getElementById('used-filters');
            usedFilters.innerText = totalTitles > 0
                ? `${selectedKeyword || 'cualquier tipo'} en ${selectedLocation.charAt(0).toUpperCase() + selectedLocation.slice(1)} de los últimos ${selectedDate === 'ANY' ? 'cualquier fecha' : selectedDate}`
                : 'No se encontraron ofertas.';

            data.forEach(job => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td data-label="Título">${job.title || 'Título no disponible'}</td>
                    <td data-label="Ubicación">${job.location || 'Ubicación no disponible'}</td>
                    <td data-label="Empresa">${job.company || 'Empresa no disponible'}</td>
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

    // Exportar a Excel
    document.getElementById('export-excel').addEventListener('click', () => {
        const keyword = document.getElementById('keyword').value;
        const location = document.getElementById('location').value;
        const date = document.getElementById('date').value;

        window.location.href = `/export-excel?keyword=${keyword}&location=${location}&date=${date}`;
    });

    // Exportar a la base de datos
    document.getElementById('export-database').addEventListener('click', () => {
        const rows = document.querySelectorAll('#job-table-body tr');
        const offers = [];

        rows.forEach(row => {
            const title = row.querySelector('td[data-label="Título"]').innerText;
            const location = row.querySelector('td[data-label="Ubicación"]').innerText;
            const company = row.querySelector('td[data-label="Empresa"]').innerText;
            const link = row.querySelector('td[data-label="VER"] a').href;

            offers.push({ title, location, link, company });
        });

        fetch('/export-database', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(offers)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error exportando a la base de datos:', error);
            alert('Error al exportar a la base de datos.');
        });
    });
});
