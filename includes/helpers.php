<?php
/**
 * Funciones auxiliares
 * Ruta: includes/helpers.php
 */

/**
 * Obtiene el valor de una variable de sesión
 * @param string $key Clave de la variable
 * @param mixed $default Valor por defecto
 * @return mixed Valor de la variable o valor por defecto
 */
function session($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

/**
 * Verifica si una cadena contiene otra
 * @param string $haystack Cadena donde buscar
 * @param string $needle Cadena a buscar
 * @return bool Verdadero si la cadena contiene la otra
 */
function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

/**
 * Genera un número aleatorio entre dos valores
 * @param int $min Valor mínimo
 * @param int $max Valor máximo
 * @return int Número aleatorio generado
 */
function randomNumber($min, $max) {
    return mt_rand($min, $max);
}

/**
 * Genera una cadena aleatoria
 * @param int $length Longitud de la cadena
 * @return string Cadena aleatoria generada
 */
function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

/**
 * Calcula el porcentaje de un valor sobre un total
 * @param float $value Valor parcial
 * @param float $total Valor total
 * @param int $decimals Número de decimales
 * @return float Porcentaje calculado
 */
function percentage($value, $total, $decimals = 0) {
    if ($total == 0) return 0;
    return round(($value / $total) * 100, $decimals);
}

/**
 * Obtiene el nombre del mes
 * @param int $month Número del mes (1-12)
 * @return string Nombre del mes
 */
function getMonthName($month) {
    $months = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];
    
    return $months[$month] ?? '';
}

/**
 * Obtiene el nombre del día de la semana
 * @param int $day Número del día (0-6, siendo 0 domingo)
 * @return string Nombre del día
 */
function getDayName($day) {
    $days = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado'
    ];
    
    return $days[$day] ?? '';
}

/**
 * Genera paginación HTML
 * @param int $current_page Página actual
 * @param int $total_pages Total de páginas
 * @param string $url_pattern Patrón de URL con {page} como marcador
 * @return string Código HTML de la paginación
 */
function pagination($current_page, $total_pages, $url_pattern) {
    if ($total_pages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Navegación de páginas"><ul class="pagination justify-content-center">';
    
    // Botón anterior
    if ($current_page > 1) {
        $prev_url = str_replace('{page}', $current_page - 1, $url_pattern);
        $html .= '<li class="page-item"><a class="page-link" href="' . $prev_url . '" aria-label="Anterior">
            <span aria-hidden="true">&laquo;</span>
        </a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Anterior">
            <span aria-hidden="true">&laquo;</span>
        </a></li>';
    }
    
    // Páginas
    $start = max(1, $current_page - 2);
    $end = min($total_pages, $current_page + 2);
    
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', 1, $url_pattern) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $active = $i == $current_page ? ' active' : '';
        $html .= '<li class="page-item' . $active . '">
            <a class="page-link" href="' . str_replace('{page}', $i, $url_pattern) . '">' . $i . '</a>
        </li>';
    }
    
    if ($end < $total_pages) {
        if ($end < $total_pages - 1) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>';
        }
        $html .= '<li class="page-item">
            <a class="page-link" href="' . str_replace('{page}', $total_pages, $url_pattern) . '">' . $total_pages . '</a>
        </li>';
    }
    
    // Botón siguiente
    if ($current_page < $total_pages) {
        $next_url = str_replace('{page}', $current_page + 1, $url_pattern);
        $html .= '<li class="page-item"><a class="page-link" href="' . $next_url . '" aria-label="Siguiente">
            <span aria-hidden="true">&raquo;</span>
        </a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Siguiente">
            <span aria-hidden="true">&raquo;</span>
        </a></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Formatea un número a moneda
 * @param float $number Número a formatear
 * @param string $currency Símbolo de moneda
 * @return string Número formateado como moneda
 */
function formatCurrency($number, $currency = '$') {
    return $currency . ' ' . number_format($number, 0, ',', '.');
}

/**
 * Obtiene la extensión de un archivo
 * @param string $filename Nombre del archivo
 * @return string Extensión del archivo
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Verifica si una extensión de archivo está permitida
 * @param string $extension Extensión a verificar
 * @return bool Verdadero si la extensión está permitida
 */
function isAllowedExtension($extension) {
    $allowed = $GLOBALS['config']['allowed_extensions'] ?? ['jpg', 'jpeg', 'png', 'pdf'];
    return in_array(strtolower($extension), $allowed);
}

/**
 * Genera un mensaje de confirmación para borrado
 * @param string $message Mensaje a mostrar
 * @param string $title Título de la confirmación
 * @return string Código JavaScript para confirmación
 */
function deleteConfirm($message = '¿Está seguro de eliminar este elemento?', $title = 'Confirmar eliminación') {
    return 'onclick="return confirm(\'' . $message . '\')"';
}

/**
 * Obtiene la IP real del cliente
 * @return string Dirección IP
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}
