<?php
/**
 * Funciones de autenticación
 * Ruta: includes/auth.php
 */

/**
 * Verifica si el usuario debe estar autenticado para acceder a una página
 * Redirige al login si no lo está
 */
function verificar_sesion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: " . BASE_URL . "/views/auth/login.php");
        exit;
    }
}

/**
 * Verifica si el usuario tiene un rol específico
 * @param string|array $roles Rol o array de roles permitidos
 * @return bool Verdadero si el usuario tiene el rol
 */
function verificar_rol($roles) {
    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }
    
    if (is_array($roles)) {
        return in_array($_SESSION['usuario_rol'], $roles);
    }
    
    return $_SESSION['usuario_rol'] === $roles;
}

/**
 * Verifica si el usuario es administrador
 * @return bool Verdadero si el usuario es administrador
 */
function es_admin() {
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
}

/**
 * Verifica si el usuario es técnico
 * @return bool Verdadero si el usuario es técnico
 */
function es_tecnico() {
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'tecnico';
}

/**
 * Verifica si el usuario es supervisor
 * @return bool Verdadero si el usuario es supervisor
 */
function es_supervisor() {
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'supervisor';
}

/**
 * Verifica si el usuario es usuario regular
 * @return bool Verdadero si el usuario es usuario regular
 */
function es_usuario_regular() {
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'usuario';
}

/**
 * Restringe el acceso a una página según el rol
 * @param string|array $roles Rol o array de roles permitidos
 */
function restringir_acceso($roles) {
    if (!verificar_rol($roles)) {
        if (isset($_SESSION['usuario_id'])) {
            // Si el usuario está autenticado pero no tiene el rol correcto
            header("Location: " . BASE_URL . "/views/usuario/dashboard.php");
        } else {
            // Si el usuario no está autenticado
            header("Location: " . BASE_URL . "/views/auth/login.php");
        }
        exit;
    }
}

/**
 * Genera un hash seguro para una contraseña
 * @param string $password Contraseña a hashear
 * @return string Hash generado
 */
function generar_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica si una contraseña coincide con un hash
 * @param string $password Contraseña a verificar
 * @param string $hash Hash contra el que verificar
 * @return bool Verdadero si la contraseña coincide
 */
function verificar_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Genera un token para recuperación de contraseña
 * @return string Token generado
 */
function generar_token_recuperacion() {
    return bin2hex(random_bytes(32));
}

/**
 * Registra un intento de inicio de sesión fallido
 * @param string $usuario Usuario que intentó iniciar sesión
 */
function registrar_intento_fallido($usuario) {
    // Aquí implementarías la lógica para registrar intentos fallidos
    // Por ejemplo, incrementar un contador en la base de datos
    
    // Este es un ejemplo básico usando sesión
    if (!isset($_SESSION['intentos_fallidos'])) {
        $_SESSION['intentos_fallidos'] = [];
    }
    
    if (!isset($_SESSION['intentos_fallidos'][$usuario])) {
        $_SESSION['intentos_fallidos'][$usuario] = 0;
    }
    
    $_SESSION['intentos_fallidos'][$usuario]++;
}

/**
 * Verifica si un usuario ha excedido el número de intentos fallidos
 * @param string $usuario Usuario a verificar
 * @param int $max_intentos Número máximo de intentos permitidos
 * @return bool Verdadero si ha excedido los intentos
 */
function ha_excedido_intentos($usuario, $max_intentos = 5) {
    if (!isset($_SESSION['intentos_fallidos']) || !isset($_SESSION['intentos_fallidos'][$usuario])) {
        return false;
    }
    
    return $_SESSION['intentos_fallidos'][$usuario] >= $max_intentos;
}

/**
 * Reinicia el contador de intentos fallidos para un usuario
 * @param string $usuario Usuario para el que reiniciar el contador
 */
function reiniciar_intentos($usuario) {
    if (isset($_SESSION['intentos_fallidos']) && isset($_SESSION['intentos_fallidos'][$usuario])) {
        $_SESSION['intentos_fallidos'][$usuario] = 0;
    }
}

/**
 * Registra actividad de un usuario
 * @param int $usuario_id ID del usuario
 * @param string $accion Acción realizada
 * @param string $detalles Detalles de la acción
 */
function registrar_actividad($usuario_id, $accion, $detalles = '') {
    // Aquí implementarías la lógica para registrar actividad
    // Por ejemplo, insertar un registro en la base de datos
    
    // Este es un ejemplo básico usando un log
    $mensaje = date(DATE_FORMAT) . " - Usuario ID: $usuario_id - Acción: $accion";
    if (!empty($detalles)) {
        $mensaje .= " - Detalles: $detalles";
    }
    $mensaje .= " - IP: " . getClientIP() . "\n";
    
    error_log($mensaje, 3, ROOT_PATH . '/logs/activity.log');
}
