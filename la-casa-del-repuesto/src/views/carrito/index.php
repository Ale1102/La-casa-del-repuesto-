<?php
// Título de la página
$pageTitle = 'Carrito de Compras';
?>

<section class="cart-page">
    <div class="container">
        <h1 class="section-title">Carrito de Compras</h1>
        
        <div class="cart-container">
            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los items del carrito se cargarán dinámicamente con JavaScript -->
                        <tr>
                            <td colspan="5" class="text-center">Cargando carrito...</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="cart-actions">
                    <a href="<?php echo BASE_URL; ?>productos" class="btn btn-secondary">Continuar comprando</a>
                    <button class="btn clear-cart-btn">Vaciar carrito</button>
                </div>
            </div>
            
            <div class="cart-summary">
                <h3>Resumen del pedido</h3>
                
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span class="summary-subtotal">$0.00</span>
                </div>
                
                <div class="summary-row">
                    <span>Envío:</span>
                    <span>Gratis</span>
                </div>
                
                <div class="summary-row summary-total">
                    <span>Total:</span>
                    <span class="summary-total">$0.00</span>
                </div>
                
                <a href="<?php echo BASE_URL; ?>checkout" class="btn checkout-btn">Proceder al pago</a>
            </div>
        </div>
    </div>
</section>