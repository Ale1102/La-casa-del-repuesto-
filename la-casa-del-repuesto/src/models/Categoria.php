<?php
/**
 * Modelo para la gestión de categorías
 */
class Categoria {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "categorias";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $descripcion;
    public $categoria_padre_id;

    /**
     * Constructor con $db como conexión a la base de datos
     *
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Leer todas las categorías
     *
     * @return PDOStatement
     */
    public function read() {
        // Consulta para leer todas las categorías
        $query = "SELECT
                    c.id, c.nombre, c.descripcion, c.categoria_padre_id,
                    p.nombre as categoria_padre
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                        " . $this->table_name . " p ON c.categoria_padre_id = p.id
                ORDER BY
                    c.nombre ASC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }

    /**
     * Crear una categoría
     *
     * @return boolean
     */
    public function create() {
        // Consulta para insertar un registro
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    nombre=:nombre, descripcion=:descripcion, categoria_padre_id=:categoria_padre_id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->categoria_padre_id = $this->categoria_padre_id ? htmlspecialchars(strip_tags($this->categoria_padre_id)) : null;

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":categoria_padre_id", $this->categoria_padre_id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Leer una sola categoría
     *
     * @return boolean
     */
    public function readOne() {
        // Consulta para leer un solo registro
        $query = "SELECT
                    c.id, c.nombre, c.descripcion, c.categoria_padre_id,
                    p.nombre as categoria_padre
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                        " . $this->table_name . " p ON c.categoria_padre_id = p.id
                WHERE
                    c.id = ?
                LIMIT 0,1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el ID de la categoría
        $stmt->bindParam(1, $this->id);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el registro
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra la categoría, asignar valores a las propiedades del objeto
        if($row) {
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->categoria_padre_id = $row['categoria_padre_id'];
            return true;
        }

        return false;
    }

    /**
     * Actualizar una categoría
     *
     * @return boolean
     */
    public function update() {
        // Consulta para actualizar un registro
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nombre = :nombre,
                    descripcion = :descripcion,
                    categoria_padre_id = :categoria_padre_id
                WHERE
                    id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->categoria_padre_id = $this->categoria_padre_id ? htmlspecialchars(strip_tags($this->categoria_padre_id)) : null;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":categoria_padre_id", $this->categoria_padre_id);
        $stmt->bindParam(":id", $this->id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Eliminar una categoría
     *
     * @return boolean
     */
    public function delete() {
        // Consulta para eliminar un registro
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular el ID de la categoría a eliminar
        $stmt->bindParam(1, $this->id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Obtener categorías principales (sin padre)
     *
     * @return PDOStatement
     */
    public function readMainCategories() {
        // Consulta para leer categorías principales
        $query = "SELECT
                    id, nombre, descripcion
                FROM
                    " . $this->table_name . "
                WHERE
                    categoria_padre_id IS NULL
                ORDER BY
                    nombre ASC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtener subcategorías de una categoría
     *
     * @param int $parent_id ID de la categoría padre
     * @return PDOStatement
     */
    public function readSubcategories($parent_id) {
        // Consulta para leer subcategorías
        $query = "SELECT
                    id, nombre, descripcion
                FROM
                    " . $this->table_name . "
                WHERE
                    categoria_padre_id = ?
                ORDER BY
                    nombre ASC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el ID de la categoría padre
        $stmt->bindParam(1, $parent_id);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }
}
?>