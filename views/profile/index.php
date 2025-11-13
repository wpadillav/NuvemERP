<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    
    <div class="container mt-5">
        <h2>👤 Mi Perfil</h2>
        <p class="text-muted">Información de tu cuenta y configuraciones personales</p>
        
        <?php if ($message): ?>
            <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                <?= htmlspecialchars($message['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
        <div class="row">
            <!-- Información del Usuario -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📋 Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>ID de Usuario:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-secondary">#<?= htmlspecialchars($userProfile['id']) ?></span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Nombre de Usuario:</strong></div>
                            <div class="col-sm-8">
                                <code><?= htmlspecialchars($userProfile['username']) ?></code>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Roles Asignados:</strong></div>
                            <div class="col-sm-8">
                                <?php if ($userProfile['roles']): ?>
                                    <?php foreach (explode(', ', $userProfile['roles']) as $role): ?>
                                        <span class="badge bg-info me-1"><?= htmlspecialchars($role) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">Sin roles asignados</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Cuenta Creada:</strong></div>
                            <div class="col-sm-8">
                                <?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($userProfile['created_at']))) ?>
                                <small class="text-muted">
                                    (<?= timeAgo($userProfile['created_at']) ?>)
                                </small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Último Acceso:</strong></div>
                            <div class="col-sm-8">
                                <?php if (isset($_SESSION['user']['last_login'])): ?>
                                    <?= date('d/m/Y H:i:s', $_SESSION['user']['last_login']) ?>
                                <?php else: ?>
                                    <span class="text-muted">Información no disponible</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Contraseña:</strong></div>
                            <div class="col-sm-8">
                                <code>••••••••••••••••••••</code>
                                <small class="text-muted d-block">Protegida con encriptación segura</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones Disponibles -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">⚙️ Acciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="/?controller=Profile&action=changePassword" class="btn btn-warning">
                                🔑 Cambiar Contraseña
                            </a>
                            
                            <a href="/?controller=Dashboard&action=index" class="btn btn-primary">
                                🏠 Ir al Dashboard
                            </a>
                            
                            <?php if ($userProfile['roles'] && (strpos($userProfile['roles'], 'admin') !== false || strpos($userProfile['roles'], 'root') !== false)): ?>
                            <a href="/?controller=UserAdmin&action=index" class="btn btn-info">
                                👥 Administrar Usuarios
                            </a>
                            <?php endif; ?>
                            
                            <hr>
                            
                            <a href="/?controller=Auth&action=logout" class="btn btn-outline-danger">
                                🚪 Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Seguridad -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">🔒 Seguridad</h6>
                    </div>
                    <div class="card-body">
                        <small>
                            <ul class="mb-0">
                                <li>Tu contraseña está protegida con PBKDF2 + SHA-256</li>
                                <li>La sesión expira automáticamente por seguridad</li>
                                <li>Todos los accesos son registrados</li>
                            </ul>
                        </small>
                    </div>
                </div>
                
                <!-- Permisos del Usuario -->
                <div class="card mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">🛡️ Permisos</h6>
                    </div>
                    <div class="card-body">
                        <small>
                            <strong>Acceso a:</strong><br>
                            ✅ Perfil personal<br>
                            ✅ Dashboard<br>
                            ✅ Herramientas básicas<br>
                            <?php if ($userProfile['roles'] && (strpos($userProfile['roles'], 'admin') !== false || strpos($userProfile['roles'], 'root') !== false)): ?>
                            ✅ Administración de usuarios<br>
                            ✅ Configuración del sistema<br>
                            <?php else: ?>
                            ❌ Administración de usuarios<br>
                            ❌ Configuración del sistema<br>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Función helper para mostrar tiempo relativo
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'hace menos de un minuto';
    if ($time < 3600) return 'hace ' . floor($time/60) . ' minutos';
    if ($time < 86400) return 'hace ' . floor($time/3600) . ' horas';
    if ($time < 2592000) return 'hace ' . floor($time/86400) . ' días';
    if ($time < 31536000) return 'hace ' . floor($time/2592000) . ' meses';
    return 'hace ' . floor($time/31536000) . ' años';
}
?>