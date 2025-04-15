<?php
/**
 * Controlador para la gestión de usuarios
 */
class UsuarioController {
    private $db;
    private $usuario;

    /**
     * Constructor
     */
    public function __construct() {
        // Incluir la conexión a la base de datos
    
        require_once '../models/Usuario.php';

        // Instanciar la base de datos y el objeto usuario
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    /**
     * Registrar un nuevo usuario
     * 
     * @param array $data Datos del usuario
     * @return array Resultado de la operación
     */
    public function registrar($data) {
        // Asegurarse de que no esté vacío
        if(
            empty($data['nombre']) ||
            empty($data['apellido']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            return array("message" => "No se puede registrar el usuario. Datos incompletos.");
        }

        // Verificar si el email ya existe
        $this->usuario->email = $data['email'];
        if($this->usuario->emailExiste()) {
            return array("message" => "El correo electrónico ya está registrado.");
        }

        // Establecer valores del usuario
        $this->usuario->nombre = $data['nombre'];
        $this->usuario->apellido = $data['apellido'];
        $this->usuario->email = $data['email'];
        $this->usuario->password = $data['password'];
        $this->usuario->telefono = $data['telefono'] ?? "";
        $this->usuario->direccion = $data['direccion'] ?? "";
        $this->usuario->rol = 0; // 0: Cliente, 1: Administrador

        // Registrar el usuario
        if($this->usuario->registrar()) {
            return array("message" => "Usuario registrado con éxito.");
        }

        return array("message" => "No se pudo registrar el usuario.");
    }

    /**
     * Iniciar sesión de usuario
     * 
     * @param array $data Datos de inicio de sesión
     * @return array Resultado de la operación
     */
    public function login($data) {
        // Asegurarse de que no esté vacío
        if(
            empty($data['email']) ||
            empty($data['password'])
        ) {
            return array("message" => "Por favor, ingrese su correo electrónico y contraseña.");
        }

        // Establecer valores del usuario
        $this->usuario->email = $data['email'];
        $this->usuario->password = $data['password'];

        // Verificar si el email existe
        if($this->usuario->emailExiste()) {
            // Verificar si la contraseña es correcta
            if(password_verify($data['password'], $this->usuario->password)) {
                // Crear sesión
                $_SESSION['user'] = array(
                    "id" => $this->usuario->id,
                    "nombre" => $this->usuario->nombre,
                    "apellido" => $this->usuario->apellido,
                    "email" => $this->usuario->email,
                    "rol" => $this->usuario->rol
                );

                return array(
                    "message" => "Inicio de sesión exitoso.",
                    "user" => $_SESSION['user']
                );
            } else {
                return array("message" => "Contraseña incorrecta.");
            }
        }

        return array("message" => "El correo electrónico no está registrado.");
    }

    /**
     * Cerrar sesión de usuario
     * 
     * @return array Resultado de la operación
     */
    public function logout() {
        // Destruir la sesión
        session_unset();
        session_destroy();

        return array("message" => "Sesión cerrada con éxito.");
    }

    /**
     * Obtener información del usuario actual
     * 
     * @return array Datos del usuario
     */
    public function getCurrentUser() {
        // Verificar si hay una sesión activa
        if(isset($_SESSION['user'])) {
            // Establecer ID del usuario
            $this->usuario->id = $_SESSION['user']['id'];

            // Leer los detalles del usuario
            if($this->usuario->readOne()) {
                // Crear array
                $usuario_arr = array(
                    "id" => $this->usuario->id,
                    "nombre" => $this->usuario->nombre,
                    "apellido" => $this->usuario->apellido,
                    "email" => $this->usuario->email,
                    "telefono" => $this->usuario->telefono,
                    "direccion" => $this->usuario->direccion,
                    "fecha_registro" => $this->usuario->fecha_registro,
                    "rol" => $this->usuario->rol
                );

                return $usuario_arr;
            }
        }

        return array("message" => "No hay una sesión activa.");
    }

    /**
     * Actualizar información del usuario
     * 
     * @param array $data Datos del usuario
     * @return array Resultado de la operación
     */
    public function update($data) {
        // Verificar si hay una sesión activa
        if(!isset($_SESSION['user'])) {
            return array("message" => "No hay una sesión activa.");
        }

        // Establecer ID del usuario
        $this->usuario->id = $_SESSION['user']['id'];

        // Leer los detalles del usuario
        if(!$this->usuario->readOne()) {
            return array("message" => "El usuario no existe.");
        }

        // Actualizar valores del usuario
        if(isset($data['nombre'])) $this->usuario->nombre = $data['nombre'];
        if(isset($data['apellido'])) $this->usuario->apellido = $data['apellido'];
        if(isset($data['telefono'])) $this->usuario->telefono = $data['telefono'];
        if(isset($data['direccion'])) $this->usuario->direccion = $data['direccion'];

        // Actualizar el usuario
        if($this->usuario->update()) {
            // Actualizar la sesión
            $_SESSION['user']['nombre'] = $this->usuario->nombre;
            $_SESSION['user']['apellido'] = $this->usuario->apellido;

            return array("message" => "Información actualizada con éxito.");
        }

        return array("message" => "No se pudo actualizar la información.");
    }

    /**
     * Cambiar contraseña del usuario
     * 
     * @param array $data Datos de la contraseña
     * @return array Resultado de la operación
     */
    public function changePassword($data) {
        // Verificar si hay una sesión activa
        if(!isset($_SESSION['user'])) {
            return array("message" => "No hay una sesión activa.");
        }

        // Asegurarse de que no esté vacío
        if(
            empty($data['current_password']) ||
            empty($data['new_password']) ||
            empty($data['confirm_password'])
        ) {
            return array("message" => "Por favor, complete todos los campos.");
        }

        // Verificar que las contraseñas coincidan
        if($data['new_password'] !== $data['confirm_password']) {
            return array("message" => "Las contraseñas no coinciden.");
        }

        // Establecer ID del usuario
        $this->usuario->id = $_SESSION['user']['id'];

        // Leer los detalles del usuario
        if(!$this->usuario->readOne()) {
            return array("message" => "El usuario no existe.");
        }

        // Verificar la contraseña actual
        $this->usuario->email = $_SESSION['user']['email'];
        if($this->usuario->emailExiste()) {
            if(!password_verify($data['current_password'], $this->usuario->password)) {
                return array("message" => "La contraseña actual es incorrecta.");
            }
        }

        // Establecer la nueva contraseña
        $this->usuario->password = $data['new_password'];

        // Actualizar la contraseña
        if($this->usuario->updatePassword()) {
            return array("message" => "Contraseña actualizada con éxito.");
        }

        return array("message" => "No se pudo actualizar la contraseña.");
    }
}
?>