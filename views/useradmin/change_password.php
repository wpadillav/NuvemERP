<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    <div class="container mt-5">
        <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
        <p>Cambiar contraseña de usuario.</p>
    </div>
    
    <div class="container mt-4">
        <h2>🔑 Cambiar Contraseña</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">👤 Usuario: <?= htmlspecialchars($user['username']) ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña *</label>
                                <input type="password" class="form-control" name="new_password" 
                                       id="new_password" minlength="6" required>
                                <div class="invalid-feedback">
                                    La contraseña debe tener al menos 6 caracteres.
                                </div>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña *</label>
                                <input type="password" class="form-control" name="confirm_password" 
                                       id="confirm_password" minlength="6" required>
                                <div class="invalid-feedback">
                                    Las contraseñas deben coincidir.
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="submit" class="btn btn-warning">🔐 Cambiar Contraseña</button>
                                <a href="/?controller=UserAdmin&action=index" class="btn btn-secondary">❌ Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📊 Información del Usuario</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>ID:</strong></div>
                            <div class="col-sm-8"><?= htmlspecialchars($user['id']) ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Usuario:</strong></div>
                            <div class="col-sm-8"><?= htmlspecialchars($user['username']) ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Roles:</strong></div>
                            <div class="col-sm-8">
                                <?php if ($user['roles']): ?>
                                    <?php foreach (explode(', ', $user['roles']) as $role): ?>
                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($role) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">Sin roles</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Creado:</strong></div>
                            <div class="col-sm-8"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($user['created_at']))) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6>🔒 Seguridad</h6>
                    <ul class="mb-0">
                        <li>La contraseña será encriptada de forma segura</li>
                        <li>El usuario deberá usar la nueva contraseña en su próximo login</li>
                        <li>Se recomienda informar al usuario sobre el cambio</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de contraseñas coincidentes
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Mostrar/ocultar contraseña
        function togglePassword(inputId, buttonId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(buttonId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁️';
            }
        }
    </script>
</body>
</html>