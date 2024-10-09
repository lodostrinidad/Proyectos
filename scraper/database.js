const mysql = require('mysql');

// Crear la conexión a la base de datos
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root', // Cambiado a 'root'
    password: '', // Cambiado a '' si no hay contraseña
    database: 'job_offers' // Cambiado al nombre de tu base de datos
});

// Conectar a la base de datos
connection.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
        return;
    }
    console.log('Conectado a la base de datos MySQL');
});

// Exportar la conexión
module.exports = connection;
