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
        <h2>🔑 Cambiar Mi Contraseña</h2>
        <p class="text-muted">Actualiza tu contraseña para mantener tu cuenta segura</p>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h6>❌ Se encontraron los siguientes errores:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">🔐 Formulario de Cambio de Contraseña</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Contraseña Actual *</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="current_password" 
                                                   id="current_password" required>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('current_password', this)">👁️</button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor ingrese su contraseña actual.
                                        </div>
                                        <small class="form-text text-muted">
                                            Ingrese su contraseña actual para verificar su identidad
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Información del Usuario</label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($userProfile['username']) ?><br>
                                            <strong>Roles:</strong> 
                                            <?php if ($userProfile['roles']): ?>
                                                <?php foreach (explode(', ', $userProfile['roles']) as $role): ?>
                                                    <span class="badge bg-secondary me-1"><?= htmlspecialchars($role) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin roles</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Nueva Contraseña *</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password" 
                                                   id="new_password" minlength="6" required>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('new_password', this)">👁️</button>
                                        </div>
                                        <div class="invalid-feedback">
                                            La nueva contraseña debe tener al menos 6 caracteres.
                                        </div>
                                        <div class="progress mt-1" style="height: 3px;">
                                            <div class="progress-bar" id="strength-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small id="strength-text" class="form-text text-muted">
                                            Ingrese una contraseña de al menos 6 caracteres
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña *</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="confirm_password" 
                                                   id="confirm_password" minlength="6" required>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('confirm_password', this)">👁️</button>
                                        </div>
                                        <div class="invalid-feedback">
                                            Las contraseñas deben coincidir.
                                        </div>
                                        <small class="form-text text-muted">
                                            Confirme su nueva contraseña
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            🔐 Cambiar Contraseña
                                        </button>
                                        <a href="/?controller=Profile&action=index" class="btn btn-secondary">
                                            ❌ Cancelar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Consejos de Seguridad -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">💡 Consejos para una Contraseña Segura</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>✅ Recomendaciones:</h6>
                                <ul class="small">
                                    <li>Al menos 8 caracteres de longitud</li>
                                    <li>Combinar letras mayúsculas y minúsculas</li>
                                    <li>Incluir números y símbolos</li>
                                    <li>Evitar información personal</li>
                                    <li>No reutilizar contraseñas anteriores</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>❌ Evitar:</h6>
                                <ul class="small">
                                    <li>Contraseñas simples como "123456"</li>
                                    <li>Fechas de nacimiento o nombres</li>
                                    <li>Palabras del diccionario</li>
                                    <li>Secuencias de teclado</li>
                                    <li>Contraseñas muy cortas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                button.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                button.textContent = '👁️';
            }
        }
        
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
        
        // Medidor de fortaleza de contraseña
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            let strength = 0;
            let text = '';
            let color = '';
            
            if (password.length >= 6) strength += 20;
            if (password.length >= 8) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 10;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;
            
            if (strength < 40) {
                text = 'Débil';
                color = 'bg-danger';
            } else if (strength < 70) {
                text = 'Media';
                color = 'bg-warning';
            } else {
                text = 'Fuerte';
                color = 'bg-success';
            }
            
            strengthBar.style.width = strength + '%';
            strengthBar.className = 'progress-bar ' + color;
            strengthText.textContent = `Fortaleza: ${text} (${strength}%)`;
        });
        
        // Validación del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>