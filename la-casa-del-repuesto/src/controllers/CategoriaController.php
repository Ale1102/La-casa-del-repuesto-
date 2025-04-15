<?php
/**
 * Controlador para la gestión de categorías
 */
class CategoriaController {
    private $db;
    private $categoria;

    /**
     * Constructor
     */
    public function __construct() {
        // Incluir la conexión a la base de datos
        require_once '../config/database.php';
        require_once '../models/Categoria.php';

        // Instanciar la base de datos y el objeto categoría
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoria = new Categoria($this->db);
    }

    /**
     * Obtener todas las categorías
     * 
     * @return array Lista de categorías
     */
    public function getAll() {
        // Consultar categorías
        $stmt = $this->categoria->read();
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de categorías
            $categorias_arr = array();
            $categorias_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $categoria_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "categoria_padre_id" => $categoria_padre_id,
                    "categoria_padre" => $categoria_padre
                );

                array_push($categorias_arr["records"], $categoria_item);
            }

            return $categorias_arr;
        }

        return array("message" => "No se encontraron categorías.");
    }

    /**
     * Obtener una categoría por ID
     * 
     * @param int $id ID de la categoría
     * @return array Datos de la categoría
     */
    public function getOne($id) {
        // Establecer ID de la categoría a leer
        $this->categoria->id = $id;

        // Leer los detalles de la categoría
        if($this->categoria->readOne()) {
            // Crear array
            $categoria_arr = array(
                "id" =>  $this->categoria->id,
                "nombre" => $this->categoria->nombre,
                "descripcion" => $this->categoria->descripcion,
                "categoria_padre_id" => $this->categoria->categoria_padre_id
            );

            return $categoria_arr;
        }

        return array("message" => "La categoría no existe.");
    }

    /**
     * Crear una categoría
     * 
     * @param array $data Datos de la categoría
     * @return array Resultado de la operación
     */
    public function create($data) {
        // Asegurarse de que no esté vacío
        if(empty($data['nombre'])) {
            return array("message" => "No se puede crear la categoría. Datos incompletos.");
        }

        // Establecer valores de la categoría
        $this->categoria->nombre = $data['nombre'];
        $this->categoria->descripcion = $data['descripcion'] ?? "";
        $this->categoria->categoria_padre_id = $data['categoria_padre_id'] ?? null;

        // Crear la categoría
        if($this->categoria->create()) {
            return array("message" => "Categoría creada con éxito.");
        }

        return array("message" => "No se pudo crear la categoría.");
    }

    /**
     * Actualizar una categoría
     * 
     * @param int $id ID de la categoría
     * @param array $data Datos de la categoría
     * @return array Resultado de la operación
     */
    public function update($id, $data) {
        // Establecer ID de la categoría a actualizar
        $this->categoria->id = $id;

        // Verificar si la categoría existe
        if(!$this->categoria->readOne()) {
            return array("message" => "La categoría no existe.");
        }

        // Actualizar valores de la categoría
        if(isset($data['nombre'])) $this->categoria->nombre = $data['nombre'];
        if(isset($data['descripcion'])) $this->categoria->descripcion = $data['descripcion'];
        if(isset($data['categoria_padre_id'])) $this->categoria->categoria_padre_id = $data['categoria_padre_id'];

        // Actualizar la categoría
        if($this->categoria->update()) {
            return array("message" => "Categoría actualizada con éxito.");
        }

        return array("message" => "No se pudo actualizar la categoría.");
    }

    /**
     * Eliminar una categoría
     * 
     * @param int $id ID de la categoría
     * @return array Resultado de la operación
     */
    public function delete($id) {
        // Establecer ID de la categoría a eliminar
        $this->categoria->id = $id;

        // Verificar si la categoría existe
        if(!$this->categoria->readOne()) {
            return array("message" => "La categoría no existe.");
        }

        // Eliminar la categoría
        if($this->categoria->delete()) {
            return array("message" => "Categoría eliminada con éxito.");
        }

        return array("message" => "No se pudo eliminar la categoría.");
    }

    /**
     * Obtener categorías principales
     * 
     * @return array Lista de categorías principales
     */
    public function getMainCategories() {
        // Consultar categorías principales
        $stmt = $this->categoria->readMainCategories();
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de categorías
            $categorias_arr = array();
            $categorias_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $categoria_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion
                );

                array_push($categorias_arr["records"], $categoria_item);
            }

            return $categorias_arr;
        }

        return array("message" => "No se encontraron categorías principales.");
    }

    /**
     * Obtener subcategorías de una categoría
     * 
     * @param int $parent_id ID de la categoría padre
     * @return array Lista de subcategorías
     */
    public function getSubcategories($parent_id) {
        // Consultar subcategorías
        $stmt = $this->categoria->readSubcategories($parent_id);
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de categorías
            $categorias_arr = array();
            $categorias_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $categoria_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion
                );

                array_push($categorias_arr["records"], $categoria_item);
            }

            return $categorias_arr;
        }

        return array("message" => "No se encontraron subcategorías.");
    }
}
?>

### src/utils/helpers.php

```php
<?php
/**
 * Funciones de ayuda para la aplicación
 */

/**
 * Formatea un precio en formato de moneda
 * 
 * @param float $price Precio a formatear
 * @return string Precio formateado
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Genera una URL amigable a partir de un texto
 * 
 * @param string $text Texto a convertir
 * @return string URL amigable
 */
function slugify($text) {
    // Reemplazar caracteres no alfanuméricos por guiones
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Transliterar
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Eliminar caracteres no deseados
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Eliminar guiones duplicados
    $text = preg_replace('~-+~', '-', $text);

    // Eliminar guiones al principio y al final
    $text = trim($text, '-');

    // Convertir a minúsculas
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * Redirecciona a una URL
 * 
 * @param string $url URL a redireccionar
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
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
 * Muestra un mensaje de alerta
 * 
 * @param string $message Mensaje a mostrar
 * @param string $type Tipo de alerta (success, error, warning, info)
 */
function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Obtiene y elimina un mensaje de alerta
 * 
 * @return array|null Mensaje de alerta o null si no hay
 */
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

/**
 * Sanitiza una entrada
 * 
 * @param string $input Entrada a sanitizar
 * @return string Entrada sanitizada
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
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
        return false;
    }
    return true;
}
?>