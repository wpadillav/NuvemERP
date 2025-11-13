<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    <div class="container mt-5">
        <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
        <p>Editar información del usuario.</p>
    </div>
    
    <div class="container mt-4">
        <h2>✏️ Editar Usuario</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario *</label>
                        <input type="text" class="form-control" name="username" id="username" 
                               value="<?= htmlspecialchars($_POST['username'] ?? $user['username']) ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingrese un nombre de usuario válido.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Roles Actuales</label>
                        <div class="form-control-plaintext">
                            <?php if ($user['roles']): ?>
                                <?php foreach (explode(', ', $user['roles']) as $role): ?>
                                    <span class="badge bg-secondary me-1"><?= htmlspecialchars($role) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Sin roles asignados</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Asignar Roles *</label>
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                    <?php foreach ($roles as $role): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" 
                                   value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>"
                                   <?= in_array($role['id'], $_POST['roles'] ?? $userRoleIds) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                <strong><?= htmlspecialchars($role['name']) ?></strong>
                                <?php if ($role['description']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($role['description']) ?></small>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="form-text text-muted">Seleccione al menos un rol</small>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">💾 Actualizar Usuario</button>
                <a href="/?controller=UserAdmin&action=changePassword&id=<?= $user['id'] ?>" 
                   class="btn btn-warning">🔑 Cambiar Contraseña</a>
                <a href="/?controller=UserAdmin&action=index" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">📊 Información del Usuario</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3"><strong>ID:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($user['id']) ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><strong>Usuario:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($user['username']) ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><strong>Creado:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($user['created_at']))) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>