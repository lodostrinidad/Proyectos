const express = require('express');
const mysql = require('./database'); // Importa la conexión a la base de datos
const ExcelJS = require('exceljs');
const fs = require('fs');
const path = require('path');
const runScraper = require('./scraper'); // Importa tu función de scraper

const app = express();
const PORT = 3000;

// Middleware para parsear JSON y servir archivos estáticos
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public'))); // Sirve archivos estáticos desde 'public'

// Ruta para iniciar el scraper con filtros
app.post('/scrape', async (req, res) => {
    try {
        const filters = req.body; // Obtenemos los filtros desde el frontend
        const groupedData = await runScraper(filters); // Llamamos al scraper con los filtros

        // Aplanar el objeto en un array
        const flattenedData = [];
        for (const location in groupedData) {
            groupedData[location].forEach(job => {
                flattenedData.push({
                    title: job.title,
                    link: job.link,
                    location: location,
                    company: job.company // Incluir nombre de la empresa
                });
            });
        }

        console.log("Datos enviados al frontend:", flattenedData);
        res.json(flattenedData); // Envía el array al cliente
    } catch (error) {
        console.error('Error en el scraping:', error);
        res.status(500).json({ message: 'Error en el scraping.' });
    }
});

// Ruta para exportar los datos a Excel
app.get('/export-excel', async (req, res) => {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Trabajos');

    // Definir las columnas
    worksheet.columns = [
        { header: 'Título', key: 'title', width: 30 },
        { header: 'Enlace', key: 'link', width: 50 },
        { header: 'Ubicación', key: 'location', width: 30 },
        { header: 'Empresa', key: 'company', width: 30 }
    ];

    // Obtener datos de la base de datos
    mysql.query('SELECT title, link, location, company FROM offers', (err, results) => {
        if (err) {
            console.error("Error al obtener datos de la base de datos:", err);
            return res.status(500).json({ message: "Error al obtener datos de la base de datos." });
        }

        // Agregar filas al worksheet
        results.forEach(job => {
            worksheet.addRow(job);
        });

        // Obtener filtros de la solicitud
        const { keyword, location, date } = req.query; // Acceder a los filtros

        // Formatear la fecha y la hora
        const now = new Date();
        const formattedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        const formattedTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

        // Generar un nombre de archivo único basado en los filtros y la fecha actual
        const fileName = `Trabajos_${keyword || 'cualquier_tipo'}_${location || 'cualquier_ubicacion'}_${date || 'cualquier_fecha'}/${formattedDate}_T_${formattedTime}.xlsx`;
        const filePath = `./${fileName}`;

        // Generar el archivo Excel
        workbook.xlsx.writeFile(filePath).then(() => {
            res.download(filePath, fileName, (err) => {
                if (err) {
                    console.error("Error al descargar el archivo:", err);
                }
                fs.unlinkSync(filePath); // Eliminar el archivo temporal después de la descarga
            });
        }).catch(err => {
            console.error("Error al escribir el archivo Excel:", err);
            res.status(500).json({ message: "Error al generar el archivo Excel." });
        });
    });
});

// Ruta para exportar datos a la base de datos
app.post('/export-database', (req, res) => {
    const offers = req.body; // Los datos que se envían desde el frontend

    // Insertar datos en la base de datos
    offers.forEach(offer => {
        const { title, link, location, company } = offer;
        mysql.query('INSERT INTO offers (title, link, location, company) VALUES (?, ?, ?, ?)', [title, link, location, company], (err, result) => {
            if (err) {
                console.error("Error al insertar datos en la base de datos:", err);
                return res.status(500).json({ message: "Error al exportar datos a la base de datos." });
            }
        });
    });

    res.status(200).json({ message: 'Datos exportados a la base de datos con éxito.' });
});

// Iniciar el servidor
app.listen(PORT, () => {
    console.log(`Servidor escuchando en http://localhost:${PORT}`);
});
