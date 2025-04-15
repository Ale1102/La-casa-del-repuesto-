<section class="account-page">
    <div class="container">
        <h1 class="section-title">Mi Cuenta</h1>
        
        <div class="account-container">
            <div class="account-sidebar">
                <div class="account-user">
                    <div class="account-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="account-info">
                        <h3><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></h3>
                        <p><?php echo $usuario['email']; ?></p>
                    </div>
                </div>
                
                <ul class="account-menu">
                    <li class="active"><a href="<?php echo BASE_URL; ?>mi-cuenta">Información Personal</a></li>
                    <li><a href="<?php echo BASE_URL; ?>editar-perfil">Editar Perfil</a></li>
                    <li><a href="<?php echo BASE_URL; ?>cambiar-password">Cambiar Contraseña</a></li>
                    <li><a href="<?php echo BASE_URL; ?>mis-pedidos">Mis Pedidos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>logout">Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <div class="account-content">
                <div class="account-section">
                    <h2>Información Personal</h2>
                    
                    <div class="account-details">
                        <div class="detail-row">
                            <span class="detail-label">Nombre:</span>
                            <span class="detail-value"><?php echo $usuario['nombre']; ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Apellido:</span>
                            <span class="detail-value"><?php echo $usuario['apellido']; ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Correo Electrónico:</span>
                            <span class="detail-value"><?php echo $usuario['email']; ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Teléfono:</span>
                            <span class="detail-value"><?php echo !empty($usuario['telefono']) ? $usuario['telefono'] : 'No especificado'; ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Dirección:</span>
                            <span class="detail-value"><?php echo !empty($usuario['direccion']) ? $usuario['direccion'] : 'No especificada'; ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Fecha de Registro:</span>
                            <span class="detail-value"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="account-actions">
                        <a href="<?php echo BASE_URL; ?>editar-perfil" class="btn">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>