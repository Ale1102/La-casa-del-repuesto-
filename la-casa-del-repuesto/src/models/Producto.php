<?php
/**
 * Modelo para la entidad Producto
 */
class Producto {
    // Propiedades
    private $conn;
    private $table_name = "productos";
    
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $imagen;
    public $categoria_id;
    public $fecha_creacion;
    
    /**
     * Constructor
     * 
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtiene todos los productos
     * 
     * @param array $params Parámetros de filtrado
     * @return PDOStatement
     */
    public function getAll($params = []) {
        // Consulta base
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id";
        
        // Aplicar filtros si existen
        $conditions = [];
        $bindParams = [];
        
        if (isset($params['categoria_id']) && !empty($params['categoria_id'])) {
            $conditions[] = "p.categoria_id = :categoria_id";
            $bindParams[':categoria_id'] = $params['categoria_id'];
        }
        
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $conditions[] = "(p.nombre LIKE :keyword OR p.descripcion LIKE :keyword)";
            $bindParams[':keyword'] = "%" . $params['keyword'] . "%";
        }
        
        if (isset($params['precio_min']) && is_numeric($params['precio_min'])) {
            $conditions[] = "p.precio >= :precio_min";
            $bindParams[':precio_min'] = $params['precio_min'];
        }
        
        if (isset($params['precio_max']) && is_numeric($params['precio_max'])) {
            $conditions[] = "p.precio <= :precio_max";
            $bindParams[':precio_max'] = $params['precio_max'];
        }
        
        if (isset($params['en_stock']) && $params['en_stock'] == 1) {
            $conditions[] = "p.stock > 0";
        }
        
        // Agregar condiciones a la consulta
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        // Ordenamiento
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'p.nombre';
        $orderDir = isset($params['order_dir']) && strtoupper($params['order_dir']) === 'DESC' ? 'DESC' : 'ASC';
        $query .= " ORDER BY " . $orderBy . " " . $orderDir;
        
        // Paginación
        if (isset($params['limit']) && is_numeric($params['limit'])) {
            $limit = (int)$params['limit'];
            $offset = isset($params['offset']) && is_numeric($params['offset']) ? (int)$params['offset'] : 0;
            $query .= " LIMIT " . $offset . ", " . $limit;
        }
        
        // Preparar consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular parámetros
        foreach ($bindParams as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtiene un producto por su ID
     * 
     * @param int $id ID del producto
     * @return boolean
     */
    public function getOne($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = :id
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->imagen = $row['imagen'];
            $this->categoria_id = $row['categoria_id'];
            $this->fecha_creacion = $row['fecha_creacion'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Crea un nuevo producto
     * 
     * @return boolean
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (nombre, descripcion, precio, stock, imagen, categoria_id)
                  VALUES
                  (:nombre, :descripcion, :precio, :stock, :imagen, :categoria_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = (float)$this->precio;
        $this->stock = (int)$this->stock;
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria_id = (int)$this->categoria_id;
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        
        // Ejecutar consulta
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualiza un producto
     * 
     * @return boolean
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    stock = :stock,
                    imagen = :imagen,
                    categoria_id = :categoria_id
                  WHERE
                    id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id = (int)$this->id;
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = (float)$this->precio;
        $this->stock = (int)$this->stock;
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria_id = (int)$this->categoria_id;
        
        // Vincular parámetros
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        
        // Ejecutar consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Elimina un producto
     * 
     * @return boolean
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id = (int)$this->id;
        
        // Vincular parámetros
        $stmt->bindParam(':id', $this->id);
        
        // Ejecutar consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Busca productos por palabra clave
     * 
     * @param string $keyword Palabra clave a buscar
     * @return PDOStatement
     */
    public function search($keyword) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.nombre LIKE :keyword OR p.descripcion LIKE :keyword
                  ORDER BY p.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";
        
        // Vincular parámetros
        $stmt->bindParam(':keyword', $keyword);
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtiene productos por categoría
     * 
     * @param int $categoria_id ID de la categoría
     * @return PDOStatement
     */
    public function getByCategoria($categoria_id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.categoria_id = :categoria_id
                  ORDER BY p.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $categoria_id = (int)$categoria_id;
        
        // Vincular parámetros
        $stmt->bindParam(':categoria_id', $categoria_id);
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtiene productos relacionados
     * 
     * @param int $id ID del producto actual
     * @param int $limit Límite de productos a obtener
     * @return PDOStatement
     */
    public function getRelated($id, $limit = 4) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.categoria_id = (SELECT categoria_id FROM " . $this->table_name . " WHERE id = :id)
                  AND p.id != :id
                  ORDER BY RAND()
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $id = (int)$id;
        $limit = (int)$limit;
        
        // Vincular parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtiene los productos más vendidos
     * 
     * @param int $limit Límite de productos a obtener
     * @return PDOStatement
     */
    public function getMasVendidos($limit = 4) {
        // En una aplicación real, esto se basaría en datos de ventas
        // Aquí simplemente obtenemos algunos productos aleatorios
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  ORDER BY RAND()
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $limit = (int)$limit;
        
        // Vincular parámetros
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtiene los productos nuevos
     * 
     * @param int $limit Límite de productos a obtener
     * @return PDOStatement
     */
    public function getNuevos($limit = 4) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  ORDER BY p.fecha_creacion DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $limit = (int)$limit;
        
        // Vincular parámetros
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        // Ejecutar consulta
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Actualiza el stock de un producto
     * 
     * @param int $cantidad Cantidad a restar del stock
     * @return boolean
     */
    public function actualizarStock($cantidad) {
        $query = "UPDATE " . $this->table_name . "
                  SET stock = stock - :cantidad
                  WHERE id = :id AND stock >= :cantidad";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id = (int)$this->id;
        $cantidad = (int)$cantidad;
        
        // Vincular parámetros
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':cantidad', $cantidad);
        
        // Ejecutar consulta
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        
        return false;
    }
    
    /**
     * Verifica si hay suficiente stock
     * 
     * @param int $cantidad Cantidad a verificar
     * @return boolean
     */
    public function haySuficienteStock($cantidad) {
        $query = "SELECT stock FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id = (int)$this->id;
        
        // Vincular parámetros
        $stmt->bindParam(':id', $this->id);
        
        // Ejecutar consulta
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row && $row['stock'] >= $cantidad;
    }
    
    /**
     * Cuenta el total de productos
     * 
     * @param array $params Parámetros de filtrado
     * @return int
     */
    public function count($params = []) {
        // Consulta base
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " p";
        
        // Aplicar filtros si existen
        $conditions = [];
        $bindParams = [];
        
        if (isset($params['categoria_id']) && !empty($params['categoria_id'])) {
            $conditions[] = "p.categoria_id = :categoria_id";
            $bindParams[':categoria_id'] = $params['categoria_id'];
        }
        
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $conditions[] = "(p.nombre LIKE :keyword OR p.descripcion LIKE :keyword)";
            $bindParams[':keyword'] = "%" . $params['keyword'] . "%";
        }
        
        if (isset($params['precio_min']) && is_numeric($params['precio_min'])) {
            $conditions[] = "p.precio >= :precio_min";
            $bindParams[':precio_min'] = $params['precio_min'];
        }
        
        if (isset($params['precio_max']) && is_numeric($params['precio_max'])) {
            $conditions[] = "p.precio <= :precio_max";
            $bindParams[':precio_max'] = $params['precio_max'];
        }
        
        if (isset($params['en_stock']) && $params['en_stock'] == 1) {
            $conditions[] = "p.stock > 0";
        }
        
        // Agregar condiciones a la consulta
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        // Preparar consulta
        $stmt = $this->conn->prepare($query);
        
        // Vincular parámetros
        foreach ($bindParams as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        
        // Ejecutar consulta
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$row['total'];
    }
}
?>