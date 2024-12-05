<?php
include 'db_connect.php';

header('Content-Type: application/json');

try {
    // Obtener el total de usuarios
    $stmt = $conn->query("SELECT COUNT(*) as total_users FROM users");
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Obtener distribución de usuarios por rol
    $stmt = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener otras métricas (ejemplo, aquí puedes ajustar según tus necesidades)
    $stmt = $conn->query("SELECT COUNT(*) as total_files FROM files");
    $totalFiles = $stmt->fetch(PDO::FETCH_ASSOC)['total_files'];

    $stmt = $conn->query("SELECT COUNT(*) as pending_requests FROM support_requests WHERE status = 'pending'");
    $pendingRequests = $stmt->fetch(PDO::FETCH_ASSOC)['pending_requests'];

    echo json_encode([
        'total_users' => $totalUsers,
        'roles' => $roles,
        'total_files' => $totalFiles,
        'pending_requests' => $pendingRequests
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
