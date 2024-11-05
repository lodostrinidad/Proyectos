document.addEventListener('DOMContentLoaded', () => {
    const resultsDisplay = document.getElementById('results');
    const exportJsonButton = document.getElementById('export-json');
    const exportCsvButton = document.getElementById('export-csv');
    const editCellButton = document.getElementById('edit-cell');
    const deleteRowButton = document.getElementById('delete-row');
    const deleteColumnButton = document.getElementById('delete-column');
    const undoButton = document.getElementById('undo');
    const toggleThemeButton = document.getElementById('toggle-theme');
    const dropdownToggleButton = document.getElementById('dropdown-toggle');
    const dropdownMenu = document.getElementById('dropdown-menu');

    let resultsData = [];
    let history = [];
    let currentView = 'table'; // 'table', 'list', 'text'
    let actionSelected = null; // Almacena la acción seleccionada

    // Cargar resultados desde chrome.storage
    chrome.storage.local.get('results', (data) => {
        if (data.results) {
            resultsData = data.results;
            displayTable();
        }
    });

    function displayTable() {
        resultsDisplay.innerHTML = '';
        const table = document.createElement('table');
        const headerRow = document.createElement('tr');

        // Crear encabezados con checkboxes
        const headerCheckbox = document.createElement('th');
        headerCheckbox.innerHTML = '<input type="checkbox" id="select-all">';
        headerRow.appendChild(headerCheckbox);

        Object.keys(resultsData[0] || {}).forEach(header => {
            const headerCell = document.createElement('th');
            headerCell.textContent = header;
            headerRow.appendChild(headerCell);
        });
        table.appendChild(headerRow);

        // Crear filas con checkboxes
        resultsData.forEach((rowData, rowIndex) => {
            const row = document.createElement('tr');
            const rowCheckbox = document.createElement('td');
            rowCheckbox.innerHTML = `<input type="checkbox" class="row-checkbox" data-index="${rowIndex}">`;
            row.appendChild(rowCheckbox);

            Object.values(rowData).forEach((cellData, colIndex) => {
                const cell = document.createElement('td');
                cell.textContent = cellData;
                cell.classList.add('editable');
                cell.addEventListener('click', () => editCell(rowIndex, colIndex));
                row.appendChild(cell);
            });
            table.appendChild(row);
        });

        resultsDisplay.appendChild(table);

        // Evento para seleccionar todas las filas
        document.getElementById('select-all').addEventListener('change', (e) => {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }

    function displayList() {
        resultsDisplay.innerHTML = '';
        const list = document.createElement('ul');

        resultsData.forEach((rowData, rowIndex) => {
            const listItem = document.createElement('li');
            listItem.textContent = JSON.stringify(rowData);
            listItem.classList.add('editable');
            listItem.addEventListener('click', () => editListItem(rowIndex));
            list.appendChild(listItem);
        });

        resultsDisplay.appendChild(list);
    }

    function displayText() {
        resultsDisplay.innerHTML = resultsData.map(row => JSON.stringify(row)).join('\n');
    }

    function editCell(rowIndex, colIndex) {
        const cell = resultsDisplay.querySelector(`tr:nth-child(${rowIndex + 2}) td:nth-child(${colIndex + 2})`); // +2 para el checkbox
        const newValue = prompt("Introduce un nuevo valor:", cell.textContent);
        if (newValue !== null) {
            const oldValue = cell.textContent;
            history.push({ rowIndex, colIndex, oldValue }); // Guardar en historial
            resultsData[rowIndex][Object.keys(resultsData[0])[colIndex]] = newValue;
            cell.textContent = newValue;
            chrome.storage.local.set({ results: resultsData });
        }
    }

    function editListItem(rowIndex) {
        const newValue = prompt("Introduce un nuevo valor:", JSON.stringify(resultsData[rowIndex]));
        if (newValue !== null) {
            const oldValue = JSON.stringify(resultsData[rowIndex]);
            history.push({ rowIndex, oldValue }); // Guardar en historial
            resultsData[rowIndex] = JSON.parse(newValue);
            displayList();
            chrome.storage.local.set({ results: resultsData });
        }
    }

    function deleteRow() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        selectedCheckboxes.forEach(checkbox => {
            const rowIndex = checkbox.getAttribute('data-index');
            history.push({ action: 'deleteRow', rowIndex, rowData: { ...resultsData[rowIndex] } }); // Guardar en historial
            resultsData.splice(rowIndex, 1);
        });
        displayTable();
        chrome.storage.local.set({ results: resultsData });
    }

    function deleteColumn() {
        const colIndex = prompt("Introduce el índice de la columna a eliminar (comenzando desde 0):");
        if (colIndex !== null && colIndex >= 0) {
            const deletedColumnData = resultsData.map(row => row[Object.keys(row)[colIndex]]);
            history.push({ action: 'deleteColumn', colIndex, columnData: deletedColumnData }); // Guardar en historial
            resultsData.forEach(row => {
                delete row[Object.keys(row)[colIndex]];
            });
            displayTable();
            chrome.storage.local.set({ results: resultsData });
        }
    }

    function undo() {
        if (history.length === 0) {
            alert("No hay cambios para deshacer.");
            return;
        }

        const lastChange = history.pop();
        if (lastChange.action === 'deleteRow') {
            resultsData.splice(lastChange.rowIndex, 0, lastChange.rowData);
        } else if (lastChange.action === 'deleteColumn') {
            lastChange.columnData.forEach((value, rowIndex) => {
                resultsData[rowIndex][Object.keys(resultsData[0])[lastChange.colIndex]] = value;
            });
        } else {
            resultsData[lastChange.rowIndex][Object.keys(resultsData[0])[lastChange.colIndex]] = lastChange.oldValue;
        }

        displayTable();
        chrome.storage.local.set({ results: resultsData });
    }

    function toggleTheme() {
        document.body.classList.toggle('dark-theme');
        const buttons = document.querySelectorAll('div#dropdown-menu button');
        buttons.forEach(button => {
            button.classList.toggle('dark-theme-button');
        });
    }

    // Mostrar/ocultar el menú de opciones
    dropdownToggleButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden'); // Alterna la clase 'hidden' para mostrar/ocultar el menú
    });

    function switchView(view) {
        currentView = view;
        if (view === 'table') {
            displayTable();
        } else if (view === 'list') {
            displayList();
        } else if (view === 'text') {
            displayText();
        }
    }

    // Asignar eventos a los botones
    editCellButton.addEventListener('click', () => { actionSelected = 'edit'; highlightButton(editCellButton); });
    deleteRowButton.addEventListener('click', () => { actionSelected = 'deleteRow'; highlightButton(deleteRowButton); deleteRow(); });
    deleteColumnButton.addEventListener('click', () => { actionSelected = 'deleteColumn'; highlightButton(deleteColumnButton); deleteColumn(); });
    undoButton.addEventListener('click', () => { highlightButton(undoButton); undo(); });
    document.getElementById('toggle-theme').addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
    });
    
    document.getElementById('table-view').addEventListener('click', () => { highlightButton(document.getElementById('table-view')); switchView('table'); });
    document.getElementById('list-view').addEventListener('click', () => { highlightButton(document.getElementById('list-view')); switchView('list'); });
    document.getElementById('text-view').addEventListener('click', () => { highlightButton(document.getElementById('text-view')); switchView('text'); });

    // Función para exportar a JSON
    exportJsonButton.addEventListener('click', () => {
        const jsonBlob = new Blob([JSON.stringify(resultsData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(jsonBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'resultados.json';
        a.click();
        URL.revokeObjectURL(url);
    });

    // Función para exportar a CSV
    exportCsvButton.addEventListener('click', () => {
        const csvRows = [];
        const headers = Object.keys(resultsData[0]);
        csvRows.push(headers.join(','));

        for (const row of resultsData) {
            csvRows.push(Object.values(row).join(','));
        }

        const csvString = csvRows.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'resultados.csv';
        a.click();
        URL.revokeObjectURL(url);
    });

    function highlightButton(button) {
        const allButtons = [editCellButton, deleteRowButton, deleteColumnButton, undoButton, toggleThemeButton];
        allButtons.forEach(btn => btn.classList.remove('highlighted'));
        button.classList.add('highlighted');
    }
});
