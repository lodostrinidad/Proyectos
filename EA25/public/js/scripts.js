// Variables para almacenar transacciones
const transactions = [];

// Función para agregar una transacción
function addTransaction(player, buyPrice, sellPrice) {
    const profit = sellPrice - buyPrice;
    transactions.push({ player, buyPrice, sellPrice, profit });
}

// Actualiza la tabla de transacciones en la página
function updateTransactionTable() {
    const tableBody = document.querySelector('#transactionTable tbody');
    tableBody.innerHTML = ''; // Limpia las filas actuales
    transactions.forEach(transaction => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${transaction.player}</td>
            <td>${transaction.buyPrice}</td>
            <td>${transaction.sellPrice}</td>
            <td>${transaction.profit}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Botón de iniciar bot
document.getElementById('startBot').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('filterForm'));
    const playerData = {
        playerName: formData.get('playerName'),
        minBuyPrice: formData.get('minBuyPrice'),
        maxBuyPrice: formData.get('maxBuyPrice'),
        sellPrice: formData.get('sellPrice'),
    };

    fetch('/start-bot', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(playerData)
    })
    .then(response => response.json())
    .then(data => {
        alert('Bot iniciado');
    });
});

// Botón de detener bot y generar historial
document.getElementById('stopBot').addEventListener('click', function () {
    fetch('/stop-bot', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            data.transactions.forEach(trx => {
                addTransaction(trx.player, trx.buyPrice, trx.sellPrice);
            });
            updateTransactionTable();
        });
});

// Botón de exportar en Excel
document.getElementById('exportExcel').addEventListener('click', function () {
    exportToExcel();
});

// Función para exportar a Excel
function exportToExcel() {
    let csvContent = "data:text/csv;charset=utf-8,Jugador,Precio Compra,Precio Venta,Ganancia\n";
    transactions.forEach(row => {
        csvContent += `${row.player},${row.buyPrice},${row.sellPrice},${row.profit}\n`;
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "transacciones_fifa_bot.csv");
    document.body.appendChild(link); // Necesario para Firefox
    link.click();
    document.body.removeChild(link);
}
