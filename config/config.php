<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/init.php';

global $conn;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    $db = new Database();
    $conn = $db->connect();

    if (!$conn) {
        Logger::error('No se pudo conectar a la base de datos.');
        die('Error: No se pudo conectar a la base de datos.');
    }
}
