<?php
/**
 * Configuración de la conexión a la base de datos
 * 
 * Este archivo contiene la configuración necesaria para conectarse
 * a la base de datos MySQL a través de PDO.
 */

class Database {
    // Parámetros de la base de datos
    private $host = "localhost";
    private $db_name = "la_casa_del_repuesto";
    private $username = "root";
    private $password = ""; // Por defecto, WampServer no tiene contraseña
    private $conn;

    /**
     * Obtiene la conexión a la base de datos
     * 
     * @return PDO|null La conexión a la base de datos o null si hay un error
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
