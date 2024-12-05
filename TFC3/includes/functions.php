<?php
require_once 'db_connect.php';

function getTotalUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getTotalFiles() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM files");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFilesForManager($managerId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM files WHERE manager_id = ?");
    $stmt->execute([$managerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFilesForUser($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Comprobar si el usuario es administrador
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Comprobar si el usuario es encargado
function isManager() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'manager';
}

// Comprobar si el usuario es normal
function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}
?>
