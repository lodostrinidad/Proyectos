let selectedElements = [];
let isSelecting = false;
let scrapedData = []; // Almacena los datos extraídos para diferentes columnas

// Iniciar la selección de elementos
function enableSelection() {
    isSelecting = true;
    document.body.style.cursor = 'crosshair';
    document.addEventListener('click', clickHandler, true);
}

// Desactivar la selección
function disableSelection() {
    isSelecting = false;
    document.body.style.cursor = 'default';
    document.removeEventListener('click', clickHandler, true);
}

// Manejador de clics para seleccionar elementos
function clickHandler(event) {
    event.preventDefault();
    event.stopPropagation();

    if (!isSelecting) return; // Asegurarnos de que estamos en modo de selección

    const selector = getUniqueSelector(event.target);
    if (!selectedElements.includes(selector)) {
        selectedElements.push(selector);
        alert(`Selector guardado: ${selector}`);

        // Guardar el selector en chrome.storage
        chrome.storage.local.set({ selectors: selectedElements });
    }
}

// Escapar caracteres especiales para un selector CSS válido
function escapeSelector(selector) {
    return selector.replace(/([!"#$%&'()*+,./:;<=>?@[\\\]^`{|}~])/g, '\\$1');
}

// Obtener un selector CSS único
function getUniqueSelector(element) {
    const path = [];
    while (element) {
        let selector = element.nodeName.toLowerCase();
        if (element.id) {
            selector += `#${escapeSelector(element.id)}`; // Escapar el id
            path.unshift(selector);
            break;
        } else {
            const classes = Array.from(element.classList).map(c => `.${escapeSelector(c)}`).join(''); // Escapar las clases
            selector += classes;
            path.unshift(selector);
        }
        element = element.parentElement;
    }
    return path.join(' > ');
}

// Escuchar mensajes para iniciar la selección o hacer scraping
chrome.runtime.onMessage.addListener((request) => {
    if (request.type === 'start-selection') {
        selectedElements = []; // Reiniciar la lista de elementos seleccionados
        enableSelection();
    } else if (request.type === 'start-scraping') {
        scrapeData(request.selectors);
    }
});

// Función para hacer scroll hasta abajo de la página
async function scrollToBottom() {
    return new Promise((resolve) => {
        const totalHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        const scrollStep = 100; // Cantidad de píxeles a desplazar cada vez
        let currentHeight = 0;

        const interval = setInterval(() => {
            window.scrollBy(0, scrollStep);
            currentHeight += scrollStep;

            if (currentHeight >= totalHeight) {
                clearInterval(interval);
                resolve();
            }
        }, 100); // Intervalo de tiempo en milisegundos entre cada desplazamiento
    });
}

// Función para hacer scraping de los datos
async function scrapeData(selectors) {
    await scrollToBottom(); // Hacer scroll hasta el final de la página

    // Extraer datos para cada selector
    const columnData = selectors.map(selector => {
        try {
            const elements = document.querySelectorAll(selector);
            return Array.from(elements).map(element => element.textContent.trim());
        } catch (error) {
            console.error(`Error al procesar el selector "${selector}": ${error.message}`);
            return []; // Retorna un array vacío si hay un error
        }
    });

    // Almacenar los datos en la matriz
    scrapedData.push(columnData);
    // Aquí puedes enviar los resultados de vuelta al popup
    chrome.runtime.sendMessage({ type: 'scraping-result', data: columnData });
}
