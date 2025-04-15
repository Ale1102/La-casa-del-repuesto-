<section class="auth-page">
    <div class="container">
        <h1 class="section-title">Iniciar Sesión</h1>
        
        <div class="auth-container">
            <form method="POST" action="<?php echo BASE_URL; ?>login" class="form-container">
                <?php echo csrfField(); ?>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Iniciar Sesión</button>
                </div>
                
                <div class="auth-links">
                    <p>¿No tienes una cuenta? <a href="<?php echo BASE_URL; ?>registro">Regístrate</a></p>
                </div>
            </form>
        </div>
    </div>
</section>