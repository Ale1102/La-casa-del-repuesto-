<?php
/**
 * Sistema de autenticación
 */

/**
 * Inicia sesión de usuario
 * 
 * @param array $user Datos del usuario
 * @return void
 */
function login($user) {
    // Regenerar ID de sesión para prevenir ataques de fijación de sesión
    session_regenerate_id(true);
    
    // Guardar datos del usuario en la sesión
    $_SESSION['user'] = $user;
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['last_activity'] = time();
    
    // Registrar inicio de sesión
    logInfo("Usuario {$user['id']} ({$user['email']}) ha iniciado sesión");
}

/**
 * Cierra sesión de usuario
 * 
 * @return void
 */
function logout() {
    // Registrar cierre de sesión si hay un usuario
    if (isset($_SESSION['user'])) {
        logInfo("Usuario {$_SESSION['user']['id']} ({$_SESSION['user']['email']}) ha cerrado sesión");
    }
    
    // Destruir la sesión
    session_unset();
    session_destroy();
}

/**
 * Verifica si el usuario ha iniciado sesión
 * 
 * @return boolean
 */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

/**
 * Verifica si el usuario es administrador
 * 
 * @return boolean
 */
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['rol'] == 1;
}

/**
 * Verifica si la sesión ha expirado
 * 
 * @param int $timeout Tiempo de expiración en segundos (por defecto 30 minutos)
 * @return boolean
 */
function isSessionExpired($timeout = 1800) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        return true;
    }
    
    return false;
}

/**
 * Verifica si la IP del usuario ha cambiado
 * 
 * @return boolean
 */
function hasIPChanged() {
    return isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'];
}

/**
 * Actualiza la actividad del usuario
 * 
 * @return void
 */
function updateActivity() {
    $_SESSION['last_activity'] = time();
}

/**
 * Requiere que el usuario haya iniciado sesión
 * 
 * @param string $redirect URL a redireccionar si no ha iniciado sesión
 * @return void
 */
function requireLogin($redirect = 'login') {
    if (!isLoggedIn()) {
        // Guardar la URL actual para redireccionar después del login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Redireccionar al login
        header('Location: ' . BASE_URL . $redirect);
        exit;
    }
    
    // Verificar si la sesión ha expirado o la IP ha cambiado
    if (isSessionExpired() || hasIPChanged()) {
        // Cerrar sesión
        logout();
        
        // Guardar la URL actual para redireccionar después del login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Redireccionar al login con mensaje
        $_SESSION['alert'] = [
            'message' => 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.',
            'type' => 'warning'
        ];
        
        header('Location: ' . BASE_URL . $redirect);
        exit;
    }
    
    // Actualizar actividad
    updateActivity();
}

/**
 * Requiere que el usuario sea administrador
 * 
 * @param string $redirect URL a redireccionar si no es administrador
 * @return void
 */
function requireAdmin($redirect = '') {
    // Primero verificar si ha iniciado sesión
    requireLogin('login');
    
    // Verificar si es administrador
    if (!isAdmin()) {
        // Redireccionar
        if (empty($redirect)) {
            $redirect = 'index.php';
        }
        
        $_SESSION['alert'] = [
            'message' => 'No tiene permisos para acceder a esta sección.',
            'type' => 'error'
        ];
        
        header('Location: ' . BASE_URL . $redirect);
        exit;
    }
}

/**
 * Genera un token CSRF
 * 
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verifica un token CSRF
 * 
 * @param string $token Token a verificar
 * @return boolean
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        // Registrar posible ataque CSRF
        logWarning("Posible ataque CSRF detectado. Token esperado: {$_SESSION['csrf_token']}, Token recibido: {$token}");
        return false;
    }
    
    return true;
}

/**
 * Genera un campo oculto con el token CSRF
 * 
 * @return string HTML del campo oculto
 */
function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
?>