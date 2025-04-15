<?php
/**
 * Funciones de validación para la aplicación
 */

/**
 * Valida un correo electrónico
 * 
 * @param string $email Correo electrónico a validar
 * @return boolean
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida una contraseña
 * 
 * @param string $password Contraseña a validar
 * @return boolean
 */
function validatePassword($password) {
    // Mínimo 8 caracteres, al menos una letra y un número
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

/**
 * Valida un número de teléfono
 * 
 * @param string $phone Número de teléfono a validar
 * @return boolean
 */
function validatePhone($phone) {
    // Eliminar espacios, guiones y paréntesis
    $phone = preg_replace('/\s+|-|$$|$$/', '', $phone);
    
    // Validar que solo contenga números y tenga entre 8 y 15 dígitos
    return preg_match('/^\d{8,15}$/', $phone);
}

/**
 * Valida un precio
 * 
 * @param mixed $price Precio a validar
 * @return boolean
 */
function validatePrice($price) {
    return is_numeric($price) && $price >= 0;
}

/**
 * Valida una cantidad
 * 
 * @param mixed $quantity Cantidad a validar
 * @return boolean
 */
function validateQuantity($quantity) {
    return is_numeric($quantity) && $quantity > 0 && $quantity == (int)$quantity;
}

/**
 * Valida un formulario
 * 
 * @param array $data Datos del formulario
 * @param array $rules Reglas de validación
 * @return array Errores de validación
 */
function validateForm($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        // Verificar si el campo es requerido
        if (strpos($rule, 'required') !== false && (!isset($data[$field]) || trim($data[$field]) === '')) {
            $errors[$field] = 'Este campo es obligatorio';
            continue;
        }
        
        // Si el campo no es requerido y está vacío, continuar
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            continue;
        }
        
        // Validar según las reglas
        if (strpos($rule, 'email') !== false && !validateEmail($data[$field])) {
            $errors[$field] = 'Correo electrónico inválido';
        }
        
        if (strpos($rule, 'password') !== false && !validatePassword($data[$field])) {
            $errors[$field] = 'La contraseña debe tener al menos 8 caracteres, una letra y un número';
        }
        
        if (strpos($rule, 'phone') !== false && !validatePhone($data[$field])) {
            $errors[$field] = 'Número de teléfono inválido';
        }
        
        if (strpos($rule, 'price') !== false && !validatePrice($data[$field])) {
            $errors[$field] = 'Precio inválido';
        }
        
        if (strpos($rule, 'quantity') !== false && !validateQuantity($data[$field])) {
            $errors[$field] = 'Cantidad inválida';
        }
        
        // Validar longitud mínima
        if (preg_match('/min:(\d+)/', $rule, $matches)) {
            $min = (int)$matches[1];
            if (strlen($data[$field]) < $min) {
                $errors[$field] = 'Este campo debe tener al menos ' . $min . ' caracteres';
            }
        }
        
        // Validar longitud máxima
        if (preg_match('/max:(\d+)/', $rule, $matches)) {
            $max = (int)$matches[1];
            if (strlen($data[$field]) > $max) {
                $errors[$field] = 'Este campo no debe tener más de ' . $max . ' caracteres';
            }
        }
    }
    
    return $errors;
}

/**
 * Valida una fecha
 * 
 * @param string $date Fecha a validar (formato Y-m-d)
 * @return boolean
 */
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Valida un número de tarjeta de crédito
 * 
 * @param string $cardNumber Número de tarjeta a validar
 * @return boolean
 */
function validateCreditCard($cardNumber) {
    // Eliminar espacios y guiones
    $cardNumber = preg_replace('/\s+|-/', '', $cardNumber);
    
    // Verificar que solo contenga números y tenga entre 13 y 19 dígitos
    if (!preg_match('/^\d{13,19}$/', $cardNumber)) {
        return false;
    }
    
    // Algoritmo de Luhn para validar números de tarjeta
    $sum = 0;
    $length = strlen($cardNumber);
    $parity = $length % 2;
    
    for ($i = 0; $i < $length; $i++) {
        $digit = (int)$cardNumber[$i];
        
        if ($i % 2 == $parity) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        
        $sum += $digit;
    }
    
    return ($sum % 10) == 0;
}

/**
 * Valida un código CVV
 * 
 * @param string $cvv Código CVV a validar
 * @return boolean
 */
function validateCVV($cvv) {
    // El CVV debe ser un número de 3 o 4 dígitos
    return preg_match('/^\d{3,4}$/', $cvv);
}

/**
 * Valida una fecha de expiración de tarjeta
 * 
 * @param string $month Mes (1-12)
 * @param string $year Año (formato YY o YYYY)
 * @return boolean
 */
function validateExpiryDate($month, $year) {
    // Convertir a enteros
    $month = (int)$month;
    $year = (int)$year;
    
    // Validar rango del mes
    if ($month < 1 || $month > 12) {
        return false;
    }
    
    // Si el año tiene 2 dígitos, convertir a 4 dígitos
    if ($year < 100) {
        $year += 2000;
    }
    
    // Obtener la fecha actual
    $now = new DateTime();
    $currentYear = (int)$now->format('Y');
    $currentMonth = (int)$now->format('m');
    
    // Verificar que la fecha no haya expirado
    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
        return false;
    }
    
    return true;
}

/**
 * Valida un código postal
 * 
 * @param string $zipCode Código postal a validar
 * @return boolean
 */
function validateZipCode($zipCode) {
    // Para El Salvador, el código postal tiene 4 dígitos
    return preg_match('/^\d{4}$/', $zipCode);
}
?>