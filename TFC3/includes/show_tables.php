<?php
// Conexión a la base de datos
require_once '../includes/db_connect.php';

try {
    // Consulta para obtener todas las tablas de la base de datos
    $query = $conn->query("SHOW TABLES");

    // Comprobar si hay tablas en la base de datos
    if ($query->rowCount() > 0) {
        echo '<ul id="database-tables-list">';
        
        // Listar todas las tablas
        while ($row = $query->fetch(PDO::FETCH_NUM)) {
            echo '<li class="table-item" data-table-name="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[0]) . '</li>';
        }
        
        echo '</ul>';
        echo '<div id="table-details"></div>';  // Este div mostrará los detalles de la tabla seleccionada
    } else {
        echo '<p>No se encontraron tablas en la base de datos.</p>';
    }
} catch (PDOException $e) {
    echo 'Error al obtener las tablas: ' . $e->getMessage();
}
?>
