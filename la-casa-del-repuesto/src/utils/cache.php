<?php
/**
 * Sistema de caché
 */

/**
 * Obtiene un valor de la caché
 * 
 * @param string $key Clave del valor
 * @return mixed|null Valor almacenado o null si no existe
 */
function getCache($key) {
    $cacheDir = __DIR__ . '/../../cache';
    $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
    
    // Verificar si el archivo existe y no ha expirado
    if (file_exists($cacheFile)) {
        $data = unserialize(file_get_contents($cacheFile));
        
        // Verificar si ha expirado
        if ($data['expires'] === 0 || $data['expires'] > time()) {
            return $data['value'];
        }
        
        // Si ha expirado, eliminar el archivo
        unlink($cacheFile);
    }
    
    return null;
}

/**
 * Almacena un valor en la caché
 * 
 * @param string $key Clave del valor
 * @param mixed $value Valor a almacenar
 * @param int $ttl Tiempo de vida en segundos (0 para no expirar)
 * @return boolean
 */
function setCache($key, $value, $ttl = 3600) {
    $cacheDir = __DIR__ . '/../../cache';
    
    // Crear directorio de caché si no existe
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    
    $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
    
    // Calcular tiempo de expiración
    $expires = $ttl > 0 ? time() + $ttl : 0;
    
    // Crear datos a almacenar
    $data = [
        'key' => $key,
        'value' => $value,
        'expires' => $expires
    ];
    
    // Guardar en archivo
    return file_put_contents($cacheFile, serialize($data)) !== false;
}

/**
 * Elimina un valor de la caché
 * 
 * @param string $key Clave del valor
 * @return boolean
 */
function deleteCache($key) {
    $cacheDir = __DIR__ . '/../../cache';
    $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
    
    if (file_exists($cacheFile)) {
        return unlink($cacheFile);
    }
    
    return true;
}

/**
 * Limpia toda la caché
 * 
 * @return boolean
 */
function clearCache() {
    $cacheDir = __DIR__ . '/../../cache';
    
    if (!file_exists($cacheDir)) {
        return true;
    }
    
    $files = glob($cacheDir . '/*.cache');
    
    foreach ($files as $file) {
        unlink($file);
    }
    
    return true;
}

/**
 * Obtiene un valor de la caché o lo genera si no existe
 * 
 * @param string $key Clave del valor
 * @param callable $callback Función para generar el valor
 * @param int $ttl Tiempo de vida en segundos
 * @return mixed
 */
function remember($key, $callback, $ttl = 3600) {
    $value = getCache($key);
    
    if ($value === null) {
        $value = $callback();
        setCache($key, $value, $ttl);
    }
    
    return $value;
}
?>