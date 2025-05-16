<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir BASE_URL si no está definida
if (!defined('BASE_URL')) {
    define('BASE_URL', '/mesa_ayuda');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mesa de Ayuda</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/font/bootstrap-icons.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">

  <!-- Bootstrap JS con Popper (defer para mejorar carga) -->
  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>
