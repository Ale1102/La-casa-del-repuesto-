<?php
// Título de la página
$pageTitle = 'Finalizar Compra';
?>

<section class="checkout-page">
    <div class="container">
        <h1 class="section-title">Finalizar Compra</h1>
        
        <div class="checkout-container">
            <div class="checkout-form">
                <form id="checkout-form" method="POST" action="<?php echo BASE_URL; ?>procesar-pedido">
                    <h3>Información de Contacto</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" required>
                    </div>
                    
                    <h3>Dirección de Envío</h3>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" id="ciudad" name="ciudad" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal</label>
                            <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" required>
                        </div>
                    </div>
                    
                    <h3>Método de Pago</h3>
                    
                    <div class="form-group">
                        <label>Seleccione un método de pago</label>
                        
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" id="tarjeta" name="metodo_pago" value="tarjeta" checked>
                                <label for="tarjeta">Tarjeta de Crédito/Débito</label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" id="transferencia" name="metodo_pago" value="transferencia">
                                <label for="transferencia">Transferencia Bancaria</label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" id="efectivo" name="metodo_pago" value="efectivo">
                                <label for="efectivo">Pago en Efectivo al Recibir</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tarjeta-details" class="payment-details">
                        <div class="form-group">
                            <label for="numero_tarjeta">Número de Tarjeta</label>
                            <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="form-control">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha_expiracion">Fecha de Expiración</label>
                                <input type="text" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/AA" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notas">Notas adicionales</label>
                        <textarea id="notas" name="notas" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="terminos" name="terminos" required>
                            <label for="terminos">He leído y acepto los <a href="#">términos y condiciones</a></label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">Completar Pedido</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h3>Resumen del Pedido</h3>
                
                <div class="order-items">
                    <!-- Los items del pedido se cargarán dinámicamente con JavaScript -->
                </div>
                
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
            </div>
        </div>
    </div>
</section>

<script>
    // Script para mostrar/ocultar detalles de pago según el método seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const metodoPago = document.getElementsByName('metodo_pago');
        const tarjetaDetails = document.getElementById('tarjeta-details');
        
        function togglePaymentDetails() {
            if (document.getElementById('tarjeta').checked) {
                tarjetaDetails.style.display = 'block';
            } else {
                tarjetaDetails.style.display = 'none';
            }
        }
        
        // Inicializar
        togglePaymentDetails();
        
        // Agregar eventos
        for (let i = 0; i < metodoPago.length; i++) {
            metodoPago[i].addEventListener('change', togglePaymentDetails);
        }
    });
</script>