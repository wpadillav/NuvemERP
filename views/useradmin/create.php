<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    <div class="container mt-5">
        <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
        <p>Esta es una vista de Admnistración de Usuarios, donde puedes crear nuevos usuarios.</p>
    </div>
    <div class="container mt-4">
        <h2>➕ Crear Nuevo Usuario</h2>
        
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
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingrese un nombre de usuario válido.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Roles *</label>
                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                            <?php foreach ($roles as $role): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" 
                                           value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>"
                                           <?= in_array($role['id'], $_POST['roles'] ?? []) ? 'checked' : '' ?>>
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
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" name="password" id="password" 
                               minlength="6" required>
                        <div class="invalid-feedback">
                            La contraseña debe tener al menos 6 caracteres.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                        <input type="password" class="form-control" name="confirm_password" 
                               id="confirm_password" minlength="6" required>
                        <div class="invalid-feedback">
                            Las contraseñas deben coincidir.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-success">✅ Crear Usuario</button>
                <a href="/?controller=UserAdmin&action=index" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </div>
    
    <script>
        // Validación de contraseñas coincidentes
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
    <div class="container mt-5">
        <button type="button" class="btn btn-primary">Regresar</button>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>