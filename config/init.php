<?php
// Archivo: config/init.php

// Evitar redefinición
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Autoload con soporte a PSR-4 simple (case-insensitive)
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/' . $class . '.php',
        ROOT_PATH . '/models/' . $class . '.php',
        ROOT_PATH . '/controllers/' . $class . '.php',
        ROOT_PATH . '/includes/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Intentar búsqueda en minúscula (compatibilidad)
    $classLower = strtolower($class);
    $fallbacks = [
        __DIR__ . '/' . $classLower . '.php',
        ROOT_PATH . '/models/' . $classLower . '.php',
        ROOT_PATH . '/controllers/' . $classLower . '.php',
        ROOT_PATH . '/includes/' . $classLower . '.php',
    ];

    foreach ($fallbacks as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    Logger::error("Clase '$class' no encontrada");
    die("Error: Clase '$class' no encontrada.");
});