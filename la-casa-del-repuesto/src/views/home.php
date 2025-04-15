<?php
// Título de la página
$pageTitle = 'Inicio';
?>

<!-- Banner principal -->
<section class="hero-banner">
    <div class="container">
        <h1>Repuestos de calidad para tu vehículo</h1>
        <p>Encuentra todo lo que necesitas para mantener tu auto en óptimas condiciones. Amplio catálogo de repuestos originales y alternativos.</p>
        <div>
            <a href="<?php echo BASE_URL; ?>productos" class="btn">Ver productos</a>
            <a href="<?php echo BASE_URL; ?>contacto" class="btn btn-secondary">Contáctanos</a>
        </div>
    </div>
</section>

<!-- Categorías destacadas -->
<section class="categories">
    <div class="container">
        <h2 class="section-title">Categorías destacadas</h2>
        <div class="categories-grid">
            <?php
            // Aquí normalmente se cargarían las categorías desde la base de datos
            $categorias = [
                ['id' => 1, 'nombre' => 'Motor', 'imagen' => 'motor.jpg'],
                ['id' => 2, 'nombre' => 'Frenos', 'imagen' => 'frenos.jpg'],
                ['id' => 3, 'nombre' => 'Suspensión', 'imagen' => 'suspension.jpg'],
                ['id' => 4, 'nombre' => 'Eléctricos', 'imagen' => 'electricos.jpg'],
                ['id' => 5, 'nombre' => 'Carrocería', 'imagen' => 'carroceria.jpg']
            ];
            
            foreach ($categorias as $categoria):
            ?>
            <a href="<?php echo BASE_URL; ?>categoria/<?php echo $categoria['id']; ?>" class="category-card">
                <img src="<?php echo BASE_URL; ?>public/img/categorias/<?php echo $categoria['imagen']; ?>" alt="<?php echo $categoria['nombre']; ?>">
                <h3><?php echo $categoria['nombre']; ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Productos destacados -->
<section class="products">
    <div class="container">
        <h2 class="section-title">Productos destacados</h2>
        <div class="products-grid">
            <?php
            // Aquí normalmente se cargarían los productos desde la base de datos
            $productos = [
                ['id' => 1, 'nombre' => 'Filtro de aceite', 'descripcion' => 'Filtro de aceite para motores de 4 cilindros', 'precio' => 12.99, 'imagen' => 'filtro-aceite.jpg'],
                ['id' => 2, 'nombre' => 'Pastillas de freno', 'descripcion' => 'Juego de pastillas de freno delanteras', 'precio' => 35.50, 'imagen' => 'pastillas-freno.jpg'],
                ['id' => 3, 'nombre' => 'Amortiguador', 'descripcion' => 'Amortiguador trasero para sedán', 'precio' => 89.99, 'imagen' => 'amortiguador.jpg'],
                ['id' => 4, 'nombre' => 'Batería 12V', 'descripcion' => 'Batería de 12 voltios para vehículos compactos', 'precio' => 120.00, 'imagen' => 'bateria.jpg'],
                ['id' => 5, 'nombre' => 'Espejo retrovisor', 'descripcion' => 'Espejo retrovisor lateral izquierdo', 'precio' => 45.75, 'imagen' => 'espejo.jpg'],
                ['id' => 6, 'nombre' => 'Bujías', 'descripcion' => 'Juego de 4 bujías de alto rendimiento', 'precio' => 28.50, 'imagen' => 'bujias.jpg']
            ];
            
            foreach ($productos as $producto):
            ?>
            <div class="product-card">
                <img src="<?php echo BASE_URL; ?>public/img/productos/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                <div class="product-info">
                    <h3><?php echo $producto['nombre']; ?></h3>
                    <p><?php echo $producto['descripcion']; ?></p>
                    <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                    <a href="<?php echo BASE_URL; ?>producto/<?php echo $producto['id']; ?>" class="btn">Ver detalles</a>
                    <button class="btn btn-secondary add-to-cart-btn" 
                            data-product-id="<?php echo $producto['id']; ?>" 
                            data-product-name="<?php echo $producto['nombre']; ?>" 
                            data-product-price="<?php echo $producto['precio']; ?>" 
                            data-product-image="<?php echo BASE_URL; ?>public/img/productos/<?php echo $producto['imagen']; ?>">
                        <i class="fas fa-cart-plus"></i> Agregar al carrito
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ventajas -->
<section class="features">
    <div class="container">
        <h2 class="section-title">¿Por qué elegirnos?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-truck"></i>
                <h3>Envío rápido</h3>
                <p>Entregamos tus repuestos en tiempo récord para que puedas reparar tu vehículo lo antes posible.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Garantía de calidad</h3>
                <p>Todos nuestros productos cuentan con garantía para asegurar tu satisfacción.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-tag"></i>
                <h3>Precios competitivos</h3>
                <p>Ofrecemos los mejores precios del mercado sin comprometer la calidad.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset"></i>
                <h3>Soporte técnico</h3>
                <p>Nuestro equipo de expertos está disponible para asesorarte en tus compras.</p>
            </div>
        </div>
    </div>
</section>