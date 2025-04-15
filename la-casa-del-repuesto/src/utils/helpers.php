<?php
/**
 * Funciones de ayuda para la aplicación
 */

/**
 * Formatea un precio para mostrar
 * 
 * @param float $price Precio a formatear
 * @param string $currency Símbolo de moneda
 * @return string
 */
function formatPrice($price, $currency = '$') {
    return $currency . number_format($price, 2, '.', ',');
}

/**
 * Formatea una fecha para mostrar
 * 
 * @param string $date Fecha en formato Y-m-d
 * @param string $format Formato de salida
 * @return string
 */
function formatDate($date, $format = 'd/m/Y') {
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

/**
 * Limpia un texto para prevenir XSS
 * 
 * @param string $text Texto a limpiar
 * @return string
 */
function sanitizeText($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera una URL amigable (slug)
 * 
 * @param string $text Texto a convertir
 * @return string
 */
function slugify($text) {
    // Reemplazar caracteres especiales
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
    // Transliterar
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
    // Eliminar caracteres no deseados
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    // Convertir a minúsculas
    $text = strtolower($text);
    
    // Eliminar guiones al inicio y final
    $text = trim($text, '-');
    
    // Eliminar guiones duplicados
    $text = preg_replace('~-+~', '-', $text);
    
    return $text;
}

/**
 * Trunca un texto a una longitud específica
 * 
 * @param string $text Texto a truncar
 * @param int $length Longitud máxima
 * @param string $append Texto a añadir al final
 * @return string
 */
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    
    return $text . $append;
}

/**
 * Genera un token aleatorio
 * 
 * @param int $length Longitud del token
 * @return string
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Redirecciona a una URL
 * 
 * @param string $url URL a redireccionar
 * @return void
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Obtiene la URL actual
 * 
 * @return string
 */
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    
    return $protocol . '://' . $host . $uri;
}

/**
 * Verifica si una cadena comienza con otra
 * 
 * @param string $haystack Cadena a verificar
 * @param string $needle Cadena a buscar
 * @return boolean
 */
function startsWith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

/**
 * Verifica si una cadena termina con otra
 * 
 * @param string $haystack Cadena a verificar
 * @param string $needle Cadena a buscar
 * @return boolean
 */
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

/**
 * Obtiene la extensión de un archivo
 * 
 * @param string $filename Nombre del archivo
 * @return string
 */
function getFileExtension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

/**
 * Genera un nombre de archivo único
 * 
 * @param string $filename Nombre del archivo original
 * @return string
 */
function generateUniqueFilename($filename) {
    $extension = getFileExtension($filename);
    return uniqid() . '.' . $extension;
}

/**
 * Verifica si un archivo es una imagen
 * 
 * @param string $filename Nombre del archivo
 * @return boolean
 */
function isImage($filename) {
    $extension = strtolower(getFileExtension($filename));
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    return in_array($extension, $imageExtensions);
}

/**
 * Muestra un mensaje de alerta
 * 
 * @param string $message Mensaje a mostrar
 * @param string $type Tipo de alerta (success, info, warning, error)
 * @return string
 */
function showAlert($message, $type = 'info') {
    $alertClass = '';
    
    switch ($type) {
        case 'success':
            $alertClass = 'alert-success';
            break;
        case 'warning':
            $alertClass = 'alert-warning';
            break;
        case 'error':
            $alertClass = 'alert-danger';
            break;
        default:
            $alertClass = 'alert-info';
            break;
    }
    
    return '<div class="alert ' . $alertClass . '">' . $message . '</div>';
}

/**
 * Muestra los mensajes de alerta almacenados en la sesión
 * 
 * @return string
 */
function showSessionAlerts() {
    if (isset($_SESSION['alert'])) {
        $alert = showAlert($_SESSION['alert']['message'], $_SESSION['alert']['type']);
        unset($_SESSION['alert']);
        return $alert;
    }
    
    return '';
}

/**
 * Verifica si una solicitud es AJAX
 * 
 * @return boolean
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Obtiene la IP del cliente
 * 
 * @return string
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Obtiene el agente de usuario del cliente
 * 
 * @return string
 */
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'];
}

/**
 * Verifica si es una solicitud POST
 * 
 * @return boolean
 */
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica si es una solicitud GET
 * 
 * @return boolean
 */
function isGetRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Obtiene un valor de $_GET con un valor por defecto
 * 
 * @param string $key Clave a buscar
 * @param mixed $default Valor por defecto
 * @return mixed
 */
function getQuery($key, $default = null) {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

/**
 * Obtiene un valor de $_POST con un valor por defecto
 * 
 * @param string $key Clave a buscar
 * @param mixed $default Valor por defecto
 * @return mixed
 */
function getPost($key, $default = null) {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

/**
 * Genera un breadcrumb
 * 
 * @param array $items Items del breadcrumb
 * @return string
 */
function generateBreadcrumb($items) {
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    
    foreach ($items as $key => $value) {
        if ($key === array_key_last($items)) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">' . $value . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . $key . '">' . $value . '</a></li>';
        }
    }
    
    $html .= '</ol></nav>';
    
    return $html;
}

/**
 * Genera un menú de paginación
 * 
 * @param int $currentPage Página actual
 * @param int $totalPages Total de páginas
 * @param string $urlPattern Patrón de URL
 * @return string
 */
function generatePagination($currentPage, $totalPages, $urlPattern = '?page=%d') {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Paginación"><ul class="pagination">';
    
    // Botón anterior
    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage - 1) . '">Anterior</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
    }
    
    // Páginas
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, 1) . '">1</a></li>';
        if ($startPage > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i === $currentPage) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $totalPages) . '">' . $totalPages . '</a></li>';
    }
    
    // Botón siguiente
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage + 1) . '">Siguiente</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}
?>