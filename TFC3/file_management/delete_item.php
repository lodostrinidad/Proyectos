<?php
session_start();
require 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $item_type = $_POST['item_type']; // 'file' o 'folder'

    if ($item_type === 'file') {
        $stmt = $conn->prepare("UPDATE files SET is_deleted = 1 WHERE id = :item_id AND user_id = :user_id");
    } elseif ($item_type === 'folder') {
        $stmt = $conn->prepare("UPDATE folders SET is_deleted = 1 WHERE id = :item_id AND user_id = :user_id");
    }

    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo ucfirst($item_type) . " eliminado con éxito.";
    } else {
        echo "Error al eliminar el " . $item_type . ".";
    }
}
?>
