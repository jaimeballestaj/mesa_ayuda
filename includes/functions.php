<?php
/**
 * Funciones generales de la aplicación
 * Ruta: includes/functions.php
 */

/**
 * Valida que una cadena no esté vacía
 * @param string $str Cadena a validar
 * @return bool Verdadero si la cadena no está vacía
 */
function notEmpty($str) {
    return !empty(trim($str));
}

/**
 * Escapa cadenas para prevenir XSS
 * @param string $str Cadena a escapar
 * @return string Cadena escapada
 */
function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera una URL con el path base
 * @param string $path Ruta relativa
 * @return string URL completa
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Genera URL de asset con cache busting
 * @param string $path Ruta al asset
 * @return string URL completa del asset con parámetro de versión
 */
function asset($path) {
    $version = $GLOBALS['config']['app_version'] ?? '1.0';
    return url('assets/' . ltrim($path, '/')) . '?v=' . $version;
}

/**
 * Formatea una fecha al formato deseado
 * @param string $date Fecha a formatear
 * @param string $format Formato deseado (por defecto d/m/Y H:i)
 * @return string Fecha formateada
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

/**
 * Determina tiempo transcurrido desde una fecha dada
 * @param string $date Fecha desde la que calcular el tiempo
 * @return string Texto indicando el tiempo transcurrido
 */
function timeAgo($date) {
    if (empty($date)) return '';
    
    $timestamp = strtotime($date);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return 'Hace menos de un minuto';
    } elseif ($difference < 3600) {
        $minutes = round($difference / 60);
        return 'Hace ' . $minutes . ' ' . ($minutes == 1 ? 'minuto' : 'minutos');
    } elseif ($difference < 86400) {
        $hours = round($difference / 3600);
        return 'Hace ' . $hours . ' ' . ($hours == 1 ? 'hora' : 'horas');
    } elseif ($difference < 604800) {
        $days = round($difference / 86400);
        return 'Hace ' . $days . ' ' . ($days == 1 ? 'día' : 'días');
    } elseif ($difference < 2592000) {
        $weeks = round($difference / 604800);
        return 'Hace ' . $weeks . ' ' . ($weeks == 1 ? 'semana' : 'semanas');
    } else {
        return formatDate($date, 'd/m/Y');
    }
}

/**
 * Trunca una cadena a una longitud determinada
 * @param string $str Cadena a truncar
 * @param int $length Longitud máxima
 * @param string $suffix Sufijo a añadir (por defecto '...')
 * @return string Cadena truncada
 */
function truncate($str, $length, $suffix = '...') {
    if (strlen($str) <= $length) {
        return $str;
    }
    
    return substr($str, 0, $length) . $suffix;
}

/**
 * Muestra un mensaje de alerta
 * @param string $message Mensaje a mostrar
 * @param string $type Tipo de alerta (success, error, warning, info)
 * @return string Código HTML de la alerta
 */
function alert($message, $type = 'info') {
    $icon = 'info-circle';
    
    switch ($type) {
        case 'success':
            $icon = 'check-circle';
            break;
        case 'error':
            $icon = 'exclamation-triangle';
            $type = 'danger';
            break;
        case 'warning':
            $icon = 'exclamation-circle';
            break;
    }
    
    return '
    <div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
        <i class="bi bi-' . $icon . '-fill me-2"></i>
        ' . $message . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

/**
 * Genera un slug a partir de una cadena
 * @param string $str Cadena de entrada
 * @return string Slug generado
 */
function generateSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

/**
 * Genera un token CSRF
 * @return string Token generado
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verifica si un token CSRF es válido
 * @param string $token Token a verificar
 * @return bool Verdadero si el token es válido
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Filtra y sanitiza datos de entrada
 * @param mixed $data Datos a filtrar
 * @return mixed Datos filtrados
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitizeInput($value);
        }
    } else {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}