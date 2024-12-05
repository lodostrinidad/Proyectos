<?php
// Conexión a la base de datos
require_once '../includes/db_connect.php';

// Obtener el nombre de la tabla de la solicitud GET
$table = $_GET['table'] ?? '';

if ($table) {
    try {
        // Consultar la estructura de la tabla
        $query = $conn->query("DESCRIBE " . $table);
        
        if ($query->rowCount() > 0) {
            echo '<table id="table-details">';
            echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
            
            // Listar la estructura de la tabla
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['Field']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Type']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Null']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Key']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Default']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Extra']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No se pudo describir la tabla.</p>';
        }
    } catch (PDOException $e) {
        echo 'Error al describir la tabla: ' . $e->getMessage();
    }
} else {
    echo 'No se proporcionó un nombre de tabla.';
}
?>
