<?php
/**
 * Modelo para la gestión de usuarios
 */
class Usuario {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $direccion;
    public $fecha_registro;
    public $rol;

    /**
     * Constructor con $db como conexión a la base de datos
     *
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registrar un nuevo usuario
     *
     * @return boolean
     */
    public function registrar() {
        // Consulta para insertar un registro
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    nombre=:nombre, apellido=:apellido, email=:email, 
                    password=:password, telefono=:telefono, direccion=:direccion, 
                    rol=:rol";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->rol = htmlspecialchars(strip_tags($this->rol));

        // Encriptar la contraseña
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rol", $this->rol);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Verificar si el email ya existe
     *
     * @return boolean
     */
    public function emailExiste() {
        // Consulta para verificar si el email existe
        $query = "SELECT id, nombre, apellido, password, rol
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Vincular el valor
        $stmt->bindParam(1, $this->email);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el número de filas
        $num = $stmt->rowCount();

        // Si el email existe, asignar valores a las propiedades del objeto
        if($num > 0) {
            // Obtener el registro
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Asignar valores a las propiedades del objeto
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->password = $row['password'];
            $this->rol = $row['rol'];

            return true;
        }

        return false;
    }

    /**
     * Iniciar sesión de usuario
     *
     * @return boolean
     */
    public function login() {
        // Verificar si el email existe
        if($this->emailExiste()) {
            // Verificar si la contraseña es correcta
            if(password_verify($this->password, $this->password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Leer un solo usuario
     *
     * @return boolean
     */
    public function readOne() {
        // Consulta para leer un solo registro
        $query = "SELECT
                    id, nombre, apellido, email, telefono, direccion, fecha_registro, rol
                FROM
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT 0,1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el ID del usuario
        $stmt->bindParam(1, $this->id);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el registro
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el usuario, asignar valores a las propiedades del objeto
        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->direccion = $row['direccion'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->rol = $row['rol'];
            return true;
        }

        return false;
    }

    /**
     * Actualizar un usuario
     *
     * @return boolean
     */
    public function update() {
        // Consulta para actualizar un registro
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nombre = :nombre,
                    apellido = :apellido,
                    telefono = :telefono,
                    direccion = :direccion
                WHERE
                    id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":id", $this->id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Actualizar la contraseña de un usuario
     *
     * @return boolean
     */
    public function updatePassword() {
        // Consulta para actualizar la contraseña
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    password = :password
                WHERE
                    id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Encriptar la contraseña
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular los valores
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":id", $this->id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>