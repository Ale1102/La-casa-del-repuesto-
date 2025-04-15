<?php
/**
 * Controlador para la gestión de productos
 */
class ProductoController {
    private $db;
    private $producto;

    /**
     * Constructor
     */
    public function __construct() {
        // Incluir la conexión a la base de datos
        require_once 'src/config/database.php';
        require_once 'src/models/Producto.php';

        // Instanciar la base de datos y el objeto producto
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
    }

    /**
     * Obtener todos los productos
     * 
     * @param array $params Parámetros de filtrado y ordenación
     * @return array Lista de productos
     */
    public function getAll($params = []) {
        // Consultar productos
        $stmt = $this->producto->read($params);
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de productos
            $productos_arr = array();
            $productos_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $producto_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "precio" => $precio,
                    "stock" => $stock,
                    "imagen" => $imagen,
                    "categoria_id" => $categoria_id,
                    "categoria_nombre" => $categoria_nombre
                );

                array_push($productos_arr["records"], $producto_item);
            }

            return $productos_arr;
        }

        return array("message" => "No se encontraron productos.");
    }

    /**
     * Obtener un producto por ID
     * 
     * @param int $id ID del producto
     * @return array Datos del producto
     */
    public function getOne($id) {
        // Establecer ID del producto a leer
        $this->producto->id = $id;

        // Leer los detalles del producto
        if($this->producto->readOne()) {
            // Crear array
            $producto_arr = array(
                "id" =>  $this->producto->id,
                "nombre" => $this->producto->nombre,
                "descripcion" => $this->producto->descripcion,
                "precio" => $this->producto->precio,
                "stock" => $this->producto->stock,
                "imagen" => $this->producto->imagen,
                "categoria_id" => $this->producto->categoria_id,
                "categoria_nombre" => $this->producto->categoria_nombre
            );

            return $producto_arr;
        }

        return array("message" => "El producto no existe.");
    }

    /**
     * Crear un producto
     * 
     * @param array $data Datos del producto
     * @return array Resultado de la operación
     */
    public function create($data) {
        // Asegurarse de que no esté vacío
        if(
            empty($data['nombre']) ||
            empty($data['precio']) ||
            empty($data['categoria_id'])
        ) {
            return array("message" => "No se puede crear el producto. Datos incompletos.");
        }

        // Establecer valores del producto
        $this->producto->nombre = $data['nombre'];
        $this->producto->descripcion = $data['descripcion'] ?? "";
        $this->producto->precio = $data['precio'];
        $this->producto->stock = $data['stock'] ?? 0;
        $this->producto->categoria_id = $data['categoria_id'];
        
        // Manejar la imagen si se proporciona
        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $this->producto->imagen = $this->uploadImage($_FILES['imagen']);
        } else {
            $this->producto->imagen = $data['imagen'] ?? "";
        }

        // Crear el producto
        if($this->producto->create()) {
            return array("message" => "Producto creado con éxito.");
        }

        return array("message" => "No se pudo crear el producto.");
    }

    /**
     * Actualizar un producto
     * 
     * @param int $id ID del producto
     * @param array $data Datos del producto
     * @return array Resultado de la operación
     */
    public function update($id, $data) {
        // Establecer ID del producto a actualizar
        $this->producto->id = $id;

        // Verificar si el producto existe
        if(!$this->producto->readOne()) {
            return array("message" => "El producto no existe.");
        }

        // Actualizar valores del producto
        if(isset($data['nombre'])) $this->producto->nombre = $data['nombre'];
        if(isset($data['descripcion'])) $this->producto->descripcion = $data['descripcion'];
        if(isset($data['precio'])) $this->producto->precio = $data['precio'];
        if(isset($data['stock'])) $this->producto->stock = $data['stock'];
        if(isset($data['categoria_id'])) $this->producto->categoria_id = $data['categoria_id'];
        
        // Manejar la imagen si se proporciona
        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Eliminar imagen anterior si existe
            if(!empty($this->producto->imagen)) {
                $this->deleteImage($this->producto->imagen);
            }
            
            $this->producto->imagen = $this->uploadImage($_FILES['imagen']);
        } else if(isset($data['imagen'])) {
            $this->producto->imagen = $data['imagen'];
        }

        // Actualizar el producto
        if($this->producto->update()) {
            return array("message" => "Producto actualizado con éxito.");
        }

        return array("message" => "No se pudo actualizar el producto.");
    }

    /**
     * Eliminar un producto
     * 
     * @param int $id ID del producto
     * @return array Resultado de la operación
     */
    public function delete($id) {
        // Establecer ID del producto a eliminar
        $this->producto->id = $id;

        // Verificar si el producto existe
        if(!$this->producto->readOne()) {
            return array("message" => "El producto no existe.");
        }

        // Eliminar imagen si existe
        if(!empty($this->producto->imagen)) {
            $this->deleteImage($this->producto->imagen);
        }

        // Eliminar el producto
        if($this->producto->delete()) {
            return array("message" => "Producto eliminado con éxito.");
        }

        return array("message" => "No se pudo eliminar el producto.");
    }

    /**
     * Buscar productos
     * 
     * @param string $keyword Palabra clave para buscar
     * @return array Lista de productos
     */
    public function search($keyword) {
        // Consultar productos
        $stmt = $this->producto->search($keyword);
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de productos
            $productos_arr = array();
            $productos_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $producto_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "precio" => $precio,
                    "stock" => $stock,
                    "imagen" => $imagen,
                    "categoria_id" => $categoria_id,
                    "categoria_nombre" => $categoria_nombre
                );

                array_push($productos_arr["records"], $producto_item);
            }

            return $productos_arr;
        }

        return array("message" => "No se encontraron productos.");
    }

    /**
     * Obtener productos por categoría
     * 
     * @param int $categoria_id ID de la categoría
     * @return array Lista de productos
     */
    public function getByCategoria($categoria_id) {
        // Consultar productos
        $stmt = $this->producto->readByCategoria($categoria_id);
        $num = $stmt->rowCount();

        // Verificar si hay más de 0 registros encontrados
        if($num > 0) {
            // Array de productos
            $productos_arr = array();
            $productos_arr["records"] = array();

            // Obtener el contenido de la tabla
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $producto_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "precio" => $precio,
                    "stock" => $stock,
                    "imagen" => $imagen,
                    "categoria_id" => $categoria_id,
                    "categoria_nombre" => $categoria_nombre
                );

                array_push($productos_arr["records"], $producto_item);
            }

            return $productos_arr;
        }

        return array("message" => "No se encontraron productos en esta categoría.");
    }

    /**
     * Subir una imagen
     * 
     * @param array $file Archivo de imagen
     * @return string Nombre del archivo
     */
    private function uploadImage($file) {
        // Generar un nombre único para el archivo
        $filename = uniqid() . '_' . basename($file['name']);
        $target_path = UPLOADS_PATH . $filename;
        
        // Verificar si el directorio existe, si no, crearlo
        if (!file_exists(UPLOADS_PATH)) {
            mkdir(UPLOADS_PATH, 0777, true);
        }
        
        // Mover el archivo al directorio de destino
        if(move_uploaded_file($file['tmp_name'], $target_path)) {
            return $filename;
        }
        
        return "";
    }

    /**
     * Eliminar una imagen
     * 
     * @param string $filename Nombre del archivo
     * @return boolean
     */
    private function deleteImage($filename) {
        $file_path = UPLOADS_PATH . $filename;
        
        if(file_exists($file_path)) {
            return unlink($file_path);
        }
        
        return false;
    }
}
?>