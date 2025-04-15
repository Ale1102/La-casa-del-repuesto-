<?php
// Título de la página
$pageTitle = isset($producto['nombre']) ? $producto['nombre'] : 'Detalle de producto';
?>

<section class="product-detail-page">
    <div class="container">
        <!-- Navegación de migas de pan -->
        <div class="breadcrumbs">
            <a href="<?php echo BASE_URL; ?>">Inicio</a> &gt;
            <a href="<?php echo BASE_URL; ?>productos">Productos</a> &gt;
            <a href="<?php echo BASE_URL; ?>categoria/<?php echo $producto['categoria_id']; ?>"><?php echo $producto['categoria_nombre']; ?></a> &gt;
            <span><?php echo $producto['nombre']; ?></span>
        </div>
        
        <!-- Detalle del producto -->
        <div class="product-detail">
            <div class="product-images">
                <img src="<?php echo !empty($producto['imagen']) ? BASE_URL . 'public/img/productos/' . $producto['imagen'] : BASE_URL . 'public/img/placeholder.jpg'; ?>" alt="<?php echo $producto['nombre']; ?>">
            </div>
            
            <div class="product-info-detail">
                <h1><?php echo $producto['nombre']; ?></h1>
                
                <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                
                <div class="product-description">
                    <p><?php echo $producto['descripcion']; ?></p>
                </div>
                
                <div class="product-meta">
                    <p><strong>Categoría:</strong> <?php echo $producto['categoria_nombre']; ?></p>
                    <p><strong>Disponibilidad:</strong> <?php echo $producto['stock'] > 0 ? 'En stock (' . $producto['stock'] . ' unidades)' : 'Agotado'; ?></p>
                </div>
                
                <?php if($producto['stock'] > 0): ?>
                <div class="add-to-cart">
                    <div class="quantity">
                        <button type="button" class="decrease">-</button>
                        <input type="text" value="1" min="1" max="<?php echo $producto['stock']; ?>">
                        <button type="button" class="increase">+</button>
                    </div>
                    
                    <button class="btn add-to-cart-btn" 
                            data-product-id="<?php echo $producto['id']; ?>" 
                            data-product-name="<?php echo $producto['nombre']; ?>" 
                            data-product-price="<?php echo $producto['precio']; ?>" 
                            data-product-image="<?php echo !empty($producto['imagen']) ? BASE_URL . 'public/img/productos/' . $producto['imagen'] : BASE_URL . 'public/img/placeholder.jpg'; ?>">
                        <i class="fas fa-cart-plus"></i> Agregar al carrito
                    </button>
                </div>
                <?php else: ?>
                <div class="out-of-stock">
                    <p>Este producto está agotado.</p>
                </div>
                <?php endif; ?>
                
                <!-- Compartir en redes sociales -->
                <div class="social-share">
                    <p>Compartir:</p>
                    <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Productos relacionados -->
        <section class="related-products">
            <h2 class="section-title">Productos relacionados</h2>
            <div class="products-grid">
                <?php
                // Aquí normalmente se cargarían los productos relacionados desde la base de datos
                $productos_relacionados = [
                    ['id' => 2, 'nombre' => 'Pastillas de freno', 'descripcion' => 'Juego de pastillas de freno delanteras', 'precio' => 35.50, 'imagen' => 'pastillas-freno.jpg'],
                    ['id' => 3, 'nombre' => 'Amortiguador', 'descripcion' => 'Amortiguador trasero para sedán', 'precio' => 89.99, 'imagen' => 'amortiguador.jpg'],
                    ['id' => 6, 'nombre' => 'Bujías', 'descripcion' => 'Juego de 4 bujías de alto rendimiento', 'precio' => 28.50, 'imagen' => 'bujias.jpg']
                ];
                
                foreach ($productos_relacionados as $prod_rel):
                ?>
                <div class="product-card">
                    <img src="<?php echo BASE_URL; ?>public/img/productos/<?php echo $prod_rel['imagen']; ?>" alt="<?php echo $prod_rel['nombre']; ?>">
                    <div class="product-info">
                        <h3><?php echo $prod_rel['nombre']; ?></h3>
                        <p><?php echo $prod_rel['descripcion']; ?></p>
                        <div class="product-price">$<?php echo number_format($prod_rel['precio'], 2); ?></div>
                        <a href="<?php echo BASE_URL; ?>producto/<?php echo $prod_rel['id']; ?>" class="btn">Ver detalles</a>
                        <button class="btn btn-secondary add-to-cart-btn" 
                                data-product-id="<?php echo $prod_rel['id']; ?>" 
                                data-product-name="<?php echo $prod_rel['nombre']; ?>" 
                                data-product-price="<?php echo $prod_rel['precio']; ?>" 
                                data-product-image="<?php echo BASE_URL; ?>public/img/productos/<?php echo $prod_rel['imagen']; ?>">
                            <i class="fas fa-cart-plus"></i> Agregar al carrito
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>