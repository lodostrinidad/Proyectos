const puppeteer = require('puppeteer');
const fs = require('fs');

const runScraper = async (filters) => {
    const browser = await puppeteer.launch({
        headless: false, // Para ver lo que sucede en el navegador
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-web-security',
            '--ignore-certificate-errors',
            '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36'
        ]
    });

    const page = await browser.newPage();

    // Cargar cookies si existen
    try {
        const cookies = JSON.parse(fs.readFileSync('cookies.json'));
        await page.setCookie(...cookies);
    } catch (error) {
        console.log("No se encontraron cookies, procederemos sin ellas.");
    }

    const maxPages = 1; // Número máximo de páginas a extraer
    const allData = []; // Arreglo para almacenar todos los datos

    // Construir la URL base
    let urlBase = `https://www.infojobs.net/jobsearch/search-results/list.xhtml?keyword=${filters.keyword || ''}&segmentId=&sortBy=PUBLICATION_DATE&onlyForeignCountry=false&countryIds=17&sinceDate=${filters.date || 'ANY'}`;

    // Agregar filtro de ubicación si se selecciona uno
    if (filters.location && filters.location !== 'all') {
        const locationMapping = {
            madrid: 33,
            barcelona: 9,
            sevilla: 43,
            valencia: 49,
            ceuta: 15,
            malaga: 34,
        };
        urlBase += `&provinceIds=${locationMapping[filters.location]}`;
    }

    // Recorrer las páginas
    for (let pageNum = 1; pageNum <= maxPages; pageNum++) {
        const url = `${urlBase}&page=${pageNum}`;
        
        console.log(`Navegando a: ${url}`);
        await page.goto(url, { waitUntil: 'domcontentloaded' });

        // Esperar un poco para asegurarse de que el contenido se cargue
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Hacer scroll para cargar más ofertas
        const maxScrolls = 10;
        for (let i = 0; i < maxScrolls; i++) {
            await page.evaluate(() => {
                window.scrollBy(0, window.innerHeight);
            });
            const waitTime = Math.random() * (2000 - 500) + 500;
            await new Promise(resolve => setTimeout(resolve, waitTime));
        }

        // Esperar a que los anuncios sean visibles
        await page.waitForSelector('h2.ij-OfferCardContent-description-title', { timeout: 20000 })
            .catch(() => console.log("No se encontraron elementos h2.ij-OfferCardContent-description-title"));

        // Extraer datos de la página actual
        const data = await page.evaluate(() => {
            const jobs = Array.from(document.querySelectorAll('h2.ij-OfferCardContent-description-title'));
            return jobs.map(job => {
                const titleElement = job.querySelector('a.ij-OfferCardContent-description-title-link');
                const link = titleElement ? titleElement.href : null;

                // Extraer ubicación de la URL
                const urlParts = link ? link.split('/') : [];
                const location = urlParts[3] ? urlParts[3].replace(/-/g, ' ') : 'Ubicación desconocida';

                return {
                    title: titleElement ? titleElement.innerText : null,
                    link: link,
                    location: location,
                };
            });
        });

        // Agregar los datos extraídos a allData
        allData.push(...data);
    }

    // Contar títulos
    const titleCount = allData.length;
    console.log(`Total de títulos extraídos: ${titleCount}`);

    // Agrupar por ubicación
    const groupedByLocation = allData.reduce((acc, job) => {
        const location = job.location || 'Ubicación desconocida';
        if (!acc[location]) {
            acc[location] = [];
        }
        acc[location].push({
            title: job.title,
            link: job.link,
        });
        return acc;
    }, {});

    console.log('Anuncios agrupados por ubicación:');
    for (const location in groupedByLocation) {
        const jobCount = groupedByLocation[location].length;
        console.log(`\nUbicación: ${location} (Total: ${jobCount})`);
        groupedByLocation[location].forEach(job => {
            console.log(`  Título: ${job.title}`);
            console.log(`  Link: ${job.link}`);
        });
    }

    // Guardar cookies
    const cookies = await page.cookies();
    fs.writeFileSync('cookies.json', JSON.stringify(cookies));

    await browser.close();

    return groupedByLocation; // Asegúrate de devolver los datos agrupados
};

module.exports = runScraper; // Exporta la función
