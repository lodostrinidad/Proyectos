document.addEventListener('DOMContentLoaded', function () {
    const selectButton = document.getElementById('select-elements');
    const scrapeButton = document.getElementById('start-scraping');
    const exportButton = document.getElementById('export-results');
    const resultsDisplay = document.getElementById('results');
    const selectedSelectors = [];
    const resultsData = []; // Para almacenar los resultados

    // Cargar selectores de chrome.storage al abrir el popup
    chrome.storage.local.get('selectors', (data) => {
        if (data.selectors) {
            data.selectors.forEach(selector => {
                selectedSelectors.push(selector);
            });
        }
    });

    // Iniciar la selección de elementos en la página
    selectButton.addEventListener('click', () => {
        alert("Por favor, selecciona elementos en la página. Haz clic en ellos para guardar los selectores.");
        chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
            selectedSelectors.length = 0; // Reiniciar la lista de selectores
            chrome.tabs.sendMessage(tabs[0].id, { type: 'start-selection' });
        });
    });

    // Iniciar el scraping de los datos
    scrapeButton.addEventListener('click', () => {
        if (selectedSelectors.length === 0) {
            alert("No se han seleccionado selectores. Por favor, selecciona elementos primero.");
            return;
        }
        chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
            chrome.tabs.sendMessage(tabs[0].id, { type: 'start-scraping', selectors: selectedSelectors });
        });
    });

    // Escuchar los mensajes del contenido
    chrome.runtime.onMessage.addListener((request) => {
        if (request.type === 'scraping-result') {
            resultsData.push(request.data);
            displayResults();
            // Guardar resultados en chrome.storage
            chrome.storage.local.set({ results: resultsData });
        }
    });

    // Función para mostrar los resultados
    function displayResults() {
        resultsDisplay.innerHTML = ''; // Limpiar resultados previos

        // Resultados por separado
        const separatedResults = document.createElement('div');
        separatedResults.innerHTML = '<h3>Resultados Separados</h3>';
        resultsData.forEach((resultArray, index) => {
            const resultBlock = document.createElement('div');
            resultBlock.innerHTML = `<h4>Selector ${index + 1}</h4>`;
            
            // Listar cada resultado en una línea nueva
            const list = document.createElement('ul');
            resultArray.forEach(item => {
                const listItem = document.createElement('li');
                listItem.textContent = item;
                list.appendChild(listItem);
            });
            resultBlock.appendChild(list);
            separatedResults.appendChild(resultBlock);
        });
        resultsDisplay.appendChild(separatedResults);

        // Tabla combinada
        const table = document.createElement('table');
        const headerRow = document.createElement('tr');

        // Crear encabezados de la tabla
        selectedSelectors.forEach((selector, index) => {
            const headerCell = document.createElement('th');
            headerCell.textContent = `Selector ${index + 1}`;
            headerRow.appendChild(headerCell);
        });
        table.appendChild(headerRow);

        // Determinar el número de filas en la tabla
        const maxRows = Math.max(...resultsData.map(arr => arr.length));

        // Llenar la tabla con los datos
        for (let i = 0; i < maxRows; i++) {
            const row = document.createElement('tr');
            resultsData.forEach((resultArray) => {
                const cell = document.createElement('td');
                cell.textContent = resultArray[i] ? resultArray[i] : ''; // Mostrar elementos en la misma celda
                row.appendChild(cell);
            });
            table.appendChild(row);
        }

        resultsDisplay.appendChild(table);

        // Contar elementos
        const count = resultsData.reduce((acc, curr) => acc + curr.length, 0);
        resultsDisplay.innerHTML += `<p>Total de elementos encontrados: ${count}</p>`;

        // Botón para abrir resultados en resultados.html
        const viewInPopupButton = document.createElement('button');
        viewInPopupButton.textContent = 'Ver Resultados en Ventana Emergente';
        viewInPopupButton.addEventListener('click', openResultsInResultsHTML);
        resultsDisplay.appendChild(viewInPopupButton);
    }

    // Función para abrir resultados en resultados.html
    function openResultsInResultsHTML() {
        chrome.storage.local.set({ results: resultsData }, () => {
            chrome.tabs.create({ url: chrome.runtime.getURL("resultados.html") }); // Abre resultados.html
        });
    }

    // Funcionalidad para exportar resultados
    exportButton.addEventListener('click', () => {
        const format = prompt("Elige el formato de exportación: (json/csv)").toLowerCase();
        if (format === 'json') {
            exportToJson();
        } else if (format === 'csv') {
            exportToCsv();
        } else {
            alert("Formato no soportado.");
        }
    });

    // Función para exportar resultados a JSON
    function exportToJson() {
        const dataStr = JSON.stringify(resultsData);
        const blob = new Blob([dataStr], { type: "application/json" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = "resultados.json";
        a.click();
        URL.revokeObjectURL(url);
    }

    // Función para exportar resultados a CSV
    function exportToCsv() {
        const csvRows = [];
        // Encabezados vacíos
        const headers = selectedSelectors.map(() => '');
        csvRows.push(headers.join(','));

        // Determinar el número de filas en la tabla
        const maxRows = Math.max(...resultsData.map(arr => arr.length));
        for (let i = 0; i < maxRows; i++) {
            const row = [];
            resultsData.forEach((resultArray) => {
                row.push(resultArray[i] ? resultArray[i] : '');
            });
            csvRows.push(row.join(','));
        }

        const csvString = csvRows.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = "resultados.csv";
        a.click();
        URL.revokeObjectURL(url);
    }
});
