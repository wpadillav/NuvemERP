<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    <div class="container mt-5">
        <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
        <p>Confirmación de eliminación de usuario.</p>
    </div>
    
    <div class="container mt-4">
        <h2>🗑️ Eliminar Usuario</h2>
        
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-danger">
                    <h5>⚠️ Confirmación de Eliminación</h5>
                    <p class="mb-0">
                        ¿Está seguro que desea eliminar al usuario <strong><?= htmlspecialchars($user['username']) ?></strong>?
                    </p>
                </div>
                
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">👤 Información del Usuario a Eliminar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>ID:</strong></div>
                            <div class="col-sm-9"><?= htmlspecialchars($user['id']) ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Usuario:</strong></div>
                            <div class="col-sm-9"><?= htmlspecialchars($user['username']) ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Roles:</strong></div>
                            <div class="col-sm-9">
                                <?php if ($user['roles']): ?>
                                    <?php foreach (explode(', ', $user['roles']) as $role): ?>
                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($role) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">Sin roles asignados</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Creado:</strong></div>
                            <div class="col-sm-9"><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($user['created_at']))) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <h6>⚠️ Advertencias Importantes:</h6>
                    <ul class="mb-0">
                        <li><strong>Esta acción es irreversible</strong></li>
                        <li>Se eliminará el usuario y todas sus relaciones con roles</li>
                        <li>El usuario no podrá acceder al sistema</li>
                        <li>Se recomienda hacer un respaldo antes de eliminar</li>
                    </ul>
                </div>
                
                <form method="post" action="" class="mt-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('¿Está completamente seguro de eliminar este usuario? Esta acción NO se puede deshacer.')">
                            🗑️ Sí, Eliminar Usuario
                        </button>
                        <a href="/?controller=UserAdmin&action=index" class="btn btn-success">
                            ❌ No, Cancelar
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">💡 Alternativas</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Antes de eliminar, considera:</strong></p>
                        <ul>
                            <li>
                                <a href="/?controller=UserAdmin&action=edit&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary mb-2">
                                    ✏️ Editar Usuario
                                </a>
                                <br><small class="text-muted">Modificar roles o información</small>
                            </li>
                            <li>
                                <a href="/?controller=UserAdmin&action=changePassword&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-warning mb-2">
                                    🔑 Cambiar Contraseña
                                </a>
                                <br><small class="text-muted">Restablecer acceso</small>
                            </li>
                        </ul>
                        
                        <hr>
                        
                        <p><strong>En lugar de eliminar podrías:</strong></p>
                        <ul class="small">
                            <li>Remover todos los roles para desactivar</li>
                            <li>Cambiar la contraseña para bloquear acceso</li>
                            <li>Mantener el usuario para auditoría</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>