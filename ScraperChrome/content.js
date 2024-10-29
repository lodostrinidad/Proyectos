async function scrollAndLoad() {
    const scrollStep = 300; // Desplazamiento de 300 píxeles hacia abajo en cada paso
    const delay = 500; // Tiempo de espera de 500 milisegundos entre desplazamientos

    while (true) {
        window.scrollBy(0, scrollStep);
        await new Promise(resolve => setTimeout(resolve, delay));

        const newHeight = document.body.scrollHeight;
        if (window.innerHeight + window.scrollY >= newHeight) {
            console.log('Se ha alcanzado el final de la página.');
            break;
        }
    }

    return extraerDatos();
}

async function extraerDatos() {
    console.log('Iniciando la extracción de datos...');
    const tarjetas = document.querySelectorAll('li.flex');
    console.log(`Se encontraron ${tarjetas.length} tarjetas.`);
    
    const resultados = [];
    tarjetas.forEach(tarjeta => {
        const nombreEmpresa = tarjeta.querySelector('.jt-text-14.line-clamp-1.text-left.font-semibold')?.innerText.trim() || 'No disponible';
        const tituloOferta = tarjeta.querySelector('.jt-text-20.col-span-2.line-clamp-2.text-left.font-semibold.text-black')?.innerText.trim() || 'No disponible';
        const ubicacion = tarjeta.querySelector('.jt-text-14.text-left')?.innerText.trim() || 'No disponible';
        const enlace = tarjeta.querySelector('a')?.href || 'No disponible';

        resultados.push({ nombreEmpresa, tituloOferta, ubicacion, enlace });
    });

    // Enviar resultados al popup
    chrome.runtime.sendMessage({ action: "mostrarResultados", data: resultados });
}

// Escuchar mensajes del popup.js
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "iniciarExtraccionDatos") {
        scrollAndLoad();
    }
});
