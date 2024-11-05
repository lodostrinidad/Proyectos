document.addEventListener('DOMContentLoaded', function () {
    const selectButton = document.getElementById('select-elements');
    const scrapeButton = document.getElementById('start-scraping');
    const exportButton = document.getElementById('export-results');
    const resultsDisplay = document.getElementById('results');
    const selectedSelectors = [];
    const resultsData = []; // Para almacenar los resultados

    chrome.storage.local.get('selectors', (data) => {
        if (data.selectors) {
            selectedSelectors.push(...data.selectors);
        }
    });

    // Iniciar la selección de elementos en la página
    selectButton.addEventListener('click', () => {
        alert("Por favor, selecciona elementos en la página. Haz clic en ellos para guardar los selectores.");
        chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
            selectedSelectors.length = 0;
            chrome.tabs.sendMessage(tabs[0].id, { type: 'start-selection' }, (response) => {
                if (chrome.runtime.lastError) {
                    alert("El script de contenido no está disponible. Asegúrate de que estás en la página correcta.");
                }
            });
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
            const structuredData = formatData(request.data);
            resultsData.push(...structuredData);
            displayResults();
            chrome.storage.local.set({ results: resultsData });
        }
    });

    // Función para estructurar los datos como array de objetos
    function formatData(scrapedArrays) {
        const maxRows = Math.max(...scrapedArrays.map(arr => arr.length));
        const formattedData = [];

        for (let i = 0; i < maxRows; i++) {
            const row = {};
            selectedSelectors.forEach((selector, index) => {
                row[selector.name] = scrapedArrays[index][i] || '';
            });
            formattedData.push(row);
        }
        return formattedData;
    }

    // Función para mostrar los resultados
    function displayResults() {
        resultsDisplay.innerHTML = '';
        const table = document.createElement('table');
        const headerRow = document.createElement('tr');

        // Crear encabezados de la tabla con los nombres de los selectores
        selectedSelectors.forEach(selector => {
            const headerCell = document.createElement('th');
            headerCell.textContent = selector.name;
            headerRow.appendChild(headerCell);
        });
        table.appendChild(headerRow);

        // Crear las filas de la tabla con los datos correspondientes
        resultsData.forEach(rowData => {
            const row = document.createElement('tr');
            selectedSelectors.forEach(selector => {
                const cell = document.createElement('td');
                cell.textContent = rowData[selector.name];
                row.appendChild(cell);
            });
            table.appendChild(row);
        });

        resultsDisplay.appendChild(table);
        resultsDisplay.innerHTML += `<p>Total de elementos encontrados: ${resultsData.length}</p>`;

        const viewInPopupButton = document.createElement('button');
        viewInPopupButton.textContent = 'Ver Resultados en Ventana Emergente';
        viewInPopupButton.addEventListener('click', openResultsInResultsHTML);
        resultsDisplay.appendChild(viewInPopupButton);
    }

    function openResultsInResultsHTML() {
        chrome.storage.local.set({ results: resultsData }, () => {
            chrome.tabs.create({ url: chrome.runtime.getURL("resultados.html") });
        });
    }

    // Exportación de resultados
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

    function exportToCsv() {
        const csvRows = [];
        const headers = selectedSelectors.map(sel => sel.name);
        csvRows.push(headers.join(','));

        const maxRows = Math.max(...resultsData.map(arr => arr.length));
        for (let i = 0; i < maxRows; i++) {
            const row = [];
            resultsData.forEach((resultArray) => {
                row.push(resultArray[i] || '');
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
