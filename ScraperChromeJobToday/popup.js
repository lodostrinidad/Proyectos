let totalOffers = 0;
let ofertas = []; // Almacenar las ofertas globalmente

document.getElementById('extractButton').addEventListener('click', async () => {
    console.log('Botón de extracción de datos presionado.');
    const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
    
    if (tab.id) {
        // Enviar un mensaje al script de contenido para iniciar la extracción
        chrome.tabs.sendMessage(tab.id, { action: "iniciarExtraccionDatos" });
        console.log('Mensaje para iniciar extracción de datos enviado.');
    } else {
        console.error('No se pudo obtener el ID de la pestaña activa.');
    }
});

document.getElementById('exportButton').addEventListener('click', () => {
    if (ofertas.length === 0) {
        alert('No hay datos para exportar.');
        return;
    }
    
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Nombre Empresa,Título Oferta,Ubicación,Enlace\n" 
        + ofertas.map(oferta => `${oferta.nombreEmpresa},${oferta.tituloOferta},${oferta.ubicacion},${oferta.enlace}`).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "ofertas_empleo.csv");
    document.body.appendChild(link); // Required for FF

    link.click();
    document.body.removeChild(link); // Limpiar el DOM
});

// Función para recibir y mostrar los resultados en el popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "mostrarResultados") {
        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = ''; // Limpiar resultados anteriores
        
        // Almacenar las ofertas globalmente
        ofertas = request.data;

        // Actualizar el conteo total de ofertas
        totalOffers = ofertas.length;
        document.getElementById('count').innerText = `Total de ofertas: ${totalOffers}`;

        if (totalOffers === 0) {
            resultsDiv.innerHTML = '<p>No se encontraron ofertas.</p>';
            document.getElementById('exportButton').disabled = true; // Deshabilitar el botón de exportar
        } else {
            // Crear la tabla
            const table = document.createElement('table');
            const headerRow = document.createElement('tr');
            headerRow.innerHTML = `<th>Nombre Empresa</th>
                                  <th>Título Oferta</th>
                                  <th>Ubicación</th>
                                  <th>Enlace</th>`;
            table.appendChild(headerRow);

            ofertas.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${item.nombreEmpresa}</td>
                                 <td>${item.tituloOferta}</td>
                                 <td>${item.ubicacion}</td>
                                 <td><a href="${item.enlace}" target="_blank">Ver oferta</a></td>`;
                table.appendChild(row);
            });
            resultsDiv.appendChild(table);
            document.getElementById('exportButton').disabled = false; // Habilitar el botón de exportar
        }
    }
});
