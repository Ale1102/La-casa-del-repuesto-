<?php
/**
 * Sistema de registro de errores y eventos
 */

/**
 * Registra un mensaje en el archivo de log
 * 
 * @param string $message Mensaje a registrar
 * @param string $level Nivel del mensaje (info, warning, error)
 * @return boolean
 */
function logMessage($message, $level = 'info') {
    $logDir = __DIR__ . '/../../logs';
    
    // Crear directorio de logs si no existe
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $logFile = $logDir . '/' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    
    // Formatear mensaje
    $formattedMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    // Escribir en el archivo de log
    return file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

/**
 * Registra un mensaje de información
 * 
 * @param string $message Mensaje a registrar
 * @return boolean
 */
function logInfo($message) {
    return logMessage($message, 'info');
}

/**
 * Registra un mensaje de advertencia
 * 
 * @param string $message Mensaje a registrar
 * @return boolean
 */
function logWarning($message) {
    return logMessage($message, 'warning');
}

/**
 * Registra un mensaje de error
 * 
 * @param string $message Mensaje a registrar
 * @return boolean
 */
function logError($message) {
    return logMessage($message, 'error');
}

/**
 * Registra una excepción
 * 
 * @param Exception $exception Excepción a registrar
 * @return boolean
 */
function logException($exception) {
    $message = get_class($exception) . ': ' . $exception->getMessage() . 
               ' in ' . $exception->getFile() . ' on line ' . $exception->getLine() . 
               PHP_EOL . $exception->getTraceAsString();
    
    return logError($message);
}

/**
 * Manejador de errores personalizado
 * 
 * @param int $errno Número de error
 * @param string $errstr Mensaje de error
 * @param string $errfile Archivo donde ocurrió el error
 * @param int $errline Línea donde ocurrió el error
 * @return boolean
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $message = "Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}";
    logError($message);
    
    // En desarrollo, mostrar el error
    if (env('APP_ENV', 'production') === 'development') {
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "<strong>Error:</strong> {$errstr} in {$errfile} on line {$errline}";
        echo "</div>";
    }
    
    // No ejecutar el manejador de errores interno de PHP
    return true;
}

/**
 * Manejador de excepciones no capturadas
 * 
 * @param Exception $exception Excepción no capturada
 */
function customExceptionHandler($exception) {
    logException($exception);
    
    // Mostrar siempre los detalles del error (temporalmente)
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>Uncaught Exception:</strong> " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    echo "</div>";
    
    exit(1);
}
// Establecer manejadores personalizados
set_error_handler('customErrorHandler');
set_exception_handler('customExceptionHandler');
?>