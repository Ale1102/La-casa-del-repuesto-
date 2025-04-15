<?php
/**
* Configuración general de la aplicación
*/

// Definir constantes de la aplicación
define('BASE_URL', 'http://localhost/la-casa-del-repuesto/');
define('SITE_NAME', 'La Casa del Repuesto');

// Configuración de rutas
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . '../public/');
define('UPLOADS_PATH', PUBLIC_PATH . 'img/productos/');

// Configuración de sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Zona horaria
date_default_timezone_set('America/El_Salvador');

// Configuración de errores (en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// En producción, cambiar a:
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(0);
?>