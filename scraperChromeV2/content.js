let selectedElements = [];
let isSelecting = false;

function enableSelection() {
    isSelecting = true;
    document.body.style.cursor = 'crosshair';
    document.addEventListener('click', clickHandler, true);
}

function disableSelection() {
    isSelecting = false;
    document.body.style.cursor = 'default';
    document.removeEventListener('click', clickHandler, true);
}

function clickHandler(event) {
    event.preventDefault();
    event.stopPropagation();

    if (!isSelecting) return;

    const selector = getUniqueSelector(event.target);
    if (!selectedElements.some(el => el.selector === selector)) {
        const name = prompt("Introduce un nombre para el elemento seleccionado:");
        if (name) {
            const elementData = { selector: selector, name: name };
            selectedElements.push(elementData);
            alert(`Selector guardado: ${name} - ${selector}`);
            chrome.storage.local.set({ selectors: selectedElements });
        }
    }
}

function escapeSelector(selector) {
    return selector.replace(/([!"#$%&'()*+,./:;<=>?@[\\\]^`{|}~])/g, '\\$1');
}

function getUniqueSelector(element) {
    const path = [];
    while (element) {
        let selector = element.nodeName.toLowerCase();
        if (element.id) {
            selector += `#${escapeSelector(element.id)}`;
            path.unshift(selector);
            break;
        } else {
            const classes = Array.from(element.classList).map(c => `.${escapeSelector(c)}`).join('');
            selector += classes;
            path.unshift(selector);
        }
        element = element.parentElement;
    }
    return path.join(' > ');
}

chrome.runtime.onMessage.addListener((request) => {
    console.log('Mensaje recibido:', request);
    if (request.type === 'start-selection') {
        selectedElements = [];
        enableSelection();
    } else if (request.type === 'start-scraping') {
        scrapeData(request.selectors);
    }
});

async function scrollToBottom() {
    return new Promise((resolve) => {
        const totalHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        const scrollStep = 100;
        let currentHeight = 0;

        const interval = setInterval(() => {
            window.scrollBy(0, scrollStep);
            currentHeight += scrollStep;

            if (currentHeight >= totalHeight) {
                clearInterval(interval);
                resolve();
            }
        }, 100);
    });
}

async function scrapeData(selectors) {
    await scrollToBottom();

    const columnData = selectors.map(selectorObj => {
        try {
            const elements = document.querySelectorAll(selectorObj.selector);
            return Array.from(elements).map(element => element.textContent.trim());
        } catch (error) {
            console.error(`Error al procesar el selector "${selectorObj.selector}": ${error.message}`);
            return [];
        }
    });

    chrome.runtime.sendMessage({ type: 'scraping-result', data: columnData });
}
