<?php
// Incluir archivos de configuración
require_once 'src/config/config.php';
require_once 'src/config/database.php';

// Incluir controladores
require_once 'src/controllers/ProductoController.php';

// Determinar la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/la-casa-del-repuesto/';
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');

// Inicializar controladores
$productoController = new ProductoController();

// Enrutamiento básico
switch ($path) {
    case '':
    case 'index.php':
    case 'home':
        include 'src/views/layouts/header.php';
        include 'src/views/home.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'productos':
        $productos = $productoController->getAll();
        include 'src/views/layouts/header.php';
        include 'src/views/productos/index.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case (preg_match('/^producto\/(\d+)$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $producto = $productoController->getOne($id);
        include 'src/views/layouts/header.php';
        include 'src/views/productos/detalle.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'carrito':
        include 'src/views/layouts/header.php';
        include 'src/views/carrito/index.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'checkout':
        include 'src/views/layouts/header.php';
        include 'src/views/carrito/checkout.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'login':
        include 'src/views/layouts/header.php';
        include 'src/views/usuarios/login.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'registro':
        include 'src/views/layouts/header.php';
        include 'src/views/usuarios/registro.php';
        include 'src/views/layouts/footer.php';
        break;
    
    case 'admin':
        // Verificar si el usuario es administrador
        if (isset($_SESSION['user']) && $_SESSION['user']['rol'] == 1) {
            include 'src/views/layouts/header.php';
            include 'src/views/admin/dashboard.php';
            include 'src/views/layouts/footer.php';
        } else {
            header('Location: ' . BASE_URL . 'login');
        }
        break;
    
    default:
        // Página 404
        include 'src/views/layouts/header.php';
        include 'src/views/404.php';
        include 'src/views/layouts/footer.php';
        break;
}
?>
