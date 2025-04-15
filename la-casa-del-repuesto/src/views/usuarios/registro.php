<section class="auth-page">
    <div class="container">
        <h1 class="section-title">Registro</h1>
        
        <div class="auth-container">
            <form method="POST" action="<?php echo BASE_URL; ?>registro" class="form-container">
                <?php echo csrfField(); ?>
                
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
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required data-error-message="La contraseña debe tener al menos 8 caracteres, una letra y un número">
                    <small>La contraseña debe tener al menos 8 caracteres, una letra y un número.</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terminos" name="terminos" required>
                        <label for="terminos">He leído y acepto los <a href="#">términos y condiciones</a></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Registrarse</button>
                </div>
                
                <div class="auth-links">
                    <p>¿Ya tienes una cuenta? <a href="<?php echo BASE_URL; ?>login">Iniciar Sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</section>