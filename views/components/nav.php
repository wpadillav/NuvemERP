<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Barra de navegación principal -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/?controller=Dashboard&action=index">
            🔒 Sistema Seguro
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/?controller=Dashboard&action=index">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                
                <!-- Módulos ERP -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="erpDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-industry"></i> ERP
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="/?action=products">
                                <i class="fas fa-box text-primary"></i> Productos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/?action=clients">
                                <i class="fas fa-users text-success"></i> Clientes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/?action=quotes">
                                <i class="fas fa-file-invoice text-warning"></i> Cotizaciones
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-muted" href="#">
                                <i class="fas fa-chart-bar"></i> Reportes <small>(próximamente)</small>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <?php 
                // Verificar si el usuario tiene acceso de administrador
                $userModel = new UserModel();
                $userProfile = $userModel->getUserProfileByUsername($_SESSION['user']['username']);
                $isAdmin = $userProfile && $userProfile['roles'] && 
                          (strpos($userProfile['roles'], 'admin') !== false || strpos($userProfile['roles'], 'root') !== false);
                ?>
                
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/?controller=UserAdmin&action=index">
                        <i class="fas fa-users-cog"></i> Administrar Usuarios
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="/?controller=Tools&action=index">
                        <i class="fas fa-tools"></i> Herramientas
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user']['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/?controller=Profile&action=index">
                                <i class="fas fa-user"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/?controller=Profile&action=changePassword">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/?controller=Auth&action=logout">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>