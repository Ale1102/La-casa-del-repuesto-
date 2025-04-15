<?php
// Título de la página
$pageTitle = 'Productos';
?>

<section class="products-page">
    <div class="container">
        <h1 class="section-title">Catálogo de Productos</h1>
        
        <!-- Filtros -->
        <div class="filters">
            <form action="<?php echo BASE_URL; ?>productos" method="GET" class="filter-form">
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" id="categoria" class="form-control">
                        <option value="">Todas las categorías</option>
                        <option value="1">Motor</option>
                        <option value="2">Frenos</option>
                        <option value="3">Suspensión</option>
                        <option value="4">Eléctricos</option>
                        <option value="5">Carrocería</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="orden">Ordenar por:</label>
                    <select name="orden" id="orden" class="form-control">
                        <option value="nombre_asc">Nombre (A-Z)</option>
                        <option value="nombre_desc">Nombre (Z-A)</option>
                        <option value="precio_asc">Precio (menor a mayor)</option>
                        <option value="precio_desc">Precio (mayor a menor)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Aplicar filtros</button>
            </form>
        </div>
        
        <!-- Lista de productos -->
        <div class="products-grid">
            <?php
            // Verificar si hay productos
            if (isset($productos['records']) && !empty($productos['records'])):
                foreach ($productos['records'] as $producto):
            ?>
            <div class="product-card">
                <img src="<?php echo !empty($producto['imagen']) ? BASE_URL . 'public/img/productos/' . $producto['imagen'] : BASE_URL . 'public/img/placeholder.jpg'; ?>" alt="<?php echo $producto['nombre']; ?>">
                <div class="product-info">
                    <h3><?php echo $producto['nombre']; ?></h3>
                    <p><?php echo $producto['descripcion']; ?></p>
                    <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                    <a href="<?php echo BASE_URL; ?>producto/<?php echo $producto['id']; ?>" class="btn">Ver detalles</a>
                    <button class="btn btn-secondary add-to-cart-btn" 
                            data-product-id="<?php echo $producto['id']; ?>" 
                            data-product-name="<?php echo $producto['nombre']; ?>" 
                            data-product-price="<?php echo $producto['precio']; ?>" 
                            data-product-image="<?php echo !empty($producto['imagen']) ? BASE_URL . 'public/img/productos/' . $producto['imagen'] : BASE_URL . 'public/img/placeholder.jpg'; ?>">
                        <i class="fas fa-cart-plus"></i> Agregar al carrito
                    </button>
                </div>
            </div>
            <?php
                endforeach;
            else:
            ?>
            <div class="no-products">
                <p>No se encontraron productos.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Paginación -->
        <div class="pagination">
            <a href="#" class="pagination-link disabled">&laquo; Anterior</a>
            <a href="#" class="pagination-link active">1</a>
            <a href="#" class="pagination-link">2</a>
            <a href="#" class="pagination-link">3</a>
            <a href="#" class="pagination-link">Siguiente &raquo;</a>
        </div>
    </div>
</section>