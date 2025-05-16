<?php
// Ruta: views/auth/logout.php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir constantes del sistema
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/mesa_ayuda');
define('BASE_URL', '/mesa_ayuda');

// Comprobamos que existe una sesión activa
if (isset($_SESSION['usuario_id'])) {
    // Guardar el rol actual para la redirección
    $es_admin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
    
    // Limpiar todas las variables de sesión
    $_SESSION = array();

    // Si se utiliza un cookie de sesión, eliminarlo
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();
    
    // Redirigir según el rol que tenía el usuario
    if ($es_admin) {
        header("Location: " . BASE_URL . "/index.php?logout=ok&admin=true");
    } else {
        header("Location: " . BASE_URL . "/index.php?logout=ok");
    }
} else {
    // Si no hay sesión activa, solo redirigir al índice
    header("Location: " . BASE_URL . "/index.php");
}

exit();
?>