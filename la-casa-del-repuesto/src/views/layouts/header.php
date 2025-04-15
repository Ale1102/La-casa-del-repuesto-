<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/responsive.css">
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Encabezado -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>">La Casa del Repuesto</a>
            </div>
            
            <div class="search-bar">
                <form action="<?php echo BASE_URL; ?>productos" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar repuestos...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            
            <div class="nav-links">
                <a href="<?php echo BASE_URL; ?>carrito">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrito</span>
                    <span class="cart-counter">0</span>
                </a>
                
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="<?php echo BASE_URL; ?>mi-cuenta">
                        <i class="fas fa-user"></i>
                        <span>Mi Cuenta</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Salir</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Ingresar</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>registro">
                        <i class="fas fa-user-plus"></i>
                        <span>Registrarse</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- Navegación principal -->
    <nav class="main-nav">
        <div class="container">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
                <li><a href="<?php echo BASE_URL; ?>productos">Productos</a></li>
                <li><a href="<?php echo BASE_URL; ?>categorias">Categorías</a></li>
                <li><a href="<?php echo BASE_URL; ?>ofertas">Ofertas</a></li>
                <li><a href="<?php echo BASE_URL; ?>contacto">Contacto</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Contenido principal -->
    <main>