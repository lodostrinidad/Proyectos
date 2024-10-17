const express = require('express');
const app = express();
const path = require('path');

app.use(express.static(path.join(__dirname, 'public')));
app.use(express.json());

// Variables para almacenar datos del bot
let transactions = [];

// Ruta para iniciar el bot
app.post('/start-bot', (req, res) => {
    const { playerName, minBuyPrice, maxBuyPrice, sellPrice } = req.body;
    console.log(`Bot iniciado con filtros: Jugador: ${playerName}, Precio Mín: ${minBuyPrice}, Precio Máx: ${maxBuyPrice}, Precio Venta: ${sellPrice}`);
    // Aquí se integraría la lógica del bot de scraping
    res.json({ message: 'Bot iniciado' });
});

// Ruta para detener el bot y generar transacciones ficticias
app.post('/stop-bot', (req, res) => {
    // Simular transacciones realizadas por el bot
    transactions = [
        { player: 'Cristiano Ronaldo', buyPrice: 120000, sellPrice: 150000 },
        { player: 'Lionel Messi', buyPrice: 130000, sellPrice: 160000 },
        { player: 'Neymar Jr', buyPrice: 110000, sellPrice: 140000 },
    ];
    res.json({ message: 'Bot detenido', transactions });
});

// Iniciar el servidor
const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Servidor corriendo en http://localhost:${port}`);
});
