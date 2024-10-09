const express = require('express');
const mysql = require('mysql');
const ExcelJS = require('exceljs');
const fs = require('fs');
const path = require('path');
const runScraper = require('./scraper'); // Importa tu función de scraper

const app = express();
const PORT = 3000;

// Middleware para parsear JSON y servir archivos estáticos
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public'))); // Sirve archivos estáticos desde 'public'

// Conexión a la base de datos
const connection = mysql.createConnection({
    host: '127.0.0.1', // Cambiado a '127.0.0.1' en lugar de 'localhost' para evitar problemas con IPv6
    user: 'root', // Cambia por tu usuario de MySQL
    password: '', // Cambia por tu contraseña de MySQL si es necesario
    database: 'job_offers', // Cambia por el nombre de tu base de datos
    port: 3306  // Asegúrate de que MySQL está en este puerto
});

// Conectar a la base de datos
connection.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
        return;
    }
    console.log('Conectado a la base de datos MySQL');
});

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

// Ruta para exportar a Excel
app.get('/export-excel', async (req, res) => {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Trabajos');

    // Definir las columnas
    worksheet.columns = [
        { header: 'Título', key: 'title', width: 30 },
        { header: 'Enlace', key: 'link', width: 50 },
        { header: 'Ubicación', key: 'location', width: 30 }
    ];

    // Obtener datos de la base de datos
    connection.query('SELECT title, link, location FROM offers', (err, results) => {
        if (err) {
            console.error("Error al obtener datos de la base de datos:", err);
            return res.status(500).json({ message: "Error al obtener datos de la base de datos." });
        }

        // Agregar filas al worksheet
        results.forEach(job => {
            worksheet.addRow(job);
        });

        // Generar el archivo Excel
        const filePath = `./Trabajos_${Date.now()}.xlsx`;
        workbook.xlsx.writeFile(filePath).then(() => {
            res.download(filePath, 'Trabajos.xlsx', (err) => {
                if (err) {
                    console.error("Error al descargar el archivo:", err);
                }
                fs.unlinkSync(filePath);
            });
        }).catch(err => {
            console.error("Error al escribir el archivo Excel:", err);
            res.status(500).json({ message: "Error al generar el archivo Excel." });
        });
    });
});

// Ruta para exportar a la base de datos
app.post('/export-database', async (req, res) => {
    const offers = req.body; // Los datos que se envían desde el frontend

    // Insertar datos en la base de datos
    offers.forEach(offer => {
        const { title, link, location } = offer;
        connection.query('INSERT INTO offers (title, link, location) VALUES (?, ?, ?)', [title, link, location], (err, result) => {
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
