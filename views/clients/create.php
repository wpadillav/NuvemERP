<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../components/nav.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user-plus text-primary"></i> Crear Cliente</h2>
                    <a href="/?action=clients" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <!-- Alertas -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Formulario -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user text-primary"></i> Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="clients">
                            <input type="hidden" name="subaction" value="create">

                            <!-- Información básica -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Nombre Completo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           required maxlength="255"
                                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                           placeholder="Nombre completo del cliente">
                                    <div class="invalid-feedback">
                                        El nombre es requerido
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        Email
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Email de contacto (debe ser único)"></i>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           maxlength="255"
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                           placeholder="email@ejemplo.com">
                                    <div class="invalid-feedback">
                                        Ingrese un email válido
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           maxlength="50"
                                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                           placeholder="+57 300 123 4567">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                            Activo
                                        </option>
                                        <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                            Inactivo
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Documento de identificación -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="document_type" class="form-label">Tipo de Documento</label>
                                    <select class="form-select" id="document_type" name="document_type">
                                        <?php foreach ($documentTypes as $key => $label): ?>
                                            <option value="<?= $key ?>" <?= ($_POST['document_type'] ?? 'cc') === $key ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="document_number" class="form-label">
                                        Número de Documento
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Número de documento (debe ser único)"></i>
                                    </label>
                                    <input type="text" class="form-control" id="document_number" name="document_number" 
                                           maxlength="50"
                                           value="<?= htmlspecialchars($_POST['document_number'] ?? '') ?>"
                                           placeholder="Número del documento">
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <textarea class="form-control" id="address" name="address" 
                                          rows="2" placeholder="Dirección completa"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           maxlength="100"
                                           value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                                           placeholder="Ciudad de residencia">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">País</label>
                                    <input type="text" class="form-control" id="country" name="country" 
                                           maxlength="100"
                                           value="<?= htmlspecialchars($_POST['country'] ?? 'Colombia') ?>"
                                           placeholder="País de residencia">
                                </div>
                            </div>

                            <!-- Notas -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    Notas Adicionales
                                    <i class="fas fa-info-circle text-muted ms-1" 
                                       title="Información adicional sobre el cliente"></i>
                                </label>
                                <textarea class="form-control" id="notes" name="notes" 
                                          rows="3" placeholder="Notas, comentarios o información adicional..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                            </div>

                            <!-- Vista previa de la información -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-eye text-info"></i> Vista Previa
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="preview-basic">
                                                <strong id="preview-name">-</strong><br>
                                                <small class="text-muted" id="preview-email">-</small><br>
                                                <small class="text-muted" id="preview-phone">-</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="preview-location">
                                                <small class="text-muted" id="preview-address">-</small><br>
                                                <small class="text-muted" id="preview-city">-</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between">
                                <a href="/?action=clients" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Crear Cliente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Actualizar vista previa en tiempo real
        function updatePreview() {
            const name = document.getElementById('name').value || 'Sin nombre';
            const email = document.getElementById('email').value || 'Sin email';
            const phone = document.getElementById('phone').value || 'Sin teléfono';
            const address = document.getElementById('address').value || 'Sin dirección';
            const city = document.getElementById('city').value || 'Sin ciudad';
            const country = document.getElementById('country').value || 'Sin país';

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-email').textContent = email;
            document.getElementById('preview-phone').textContent = phone;
            document.getElementById('preview-address').textContent = address;
            document.getElementById('preview-city').textContent = city + ', ' + country;
        }

        // Event listeners para actualizar la vista previa
        const previewFields = ['name', 'email', 'phone', 'address', 'city', 'country'];
        previewFields.forEach(field => {
            document.getElementById(field).addEventListener('input', updatePreview);
        });

        // Actualizar al cargar
        updatePreview();

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            let hasErrors = false;

            // Validar nombre requerido
            if (!name) {
                document.getElementById('name').classList.add('is-invalid');
                hasErrors = true;
            }

            // Validar formato de email si se proporciona
            if (email && !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                document.getElementById('email').classList.add('is-invalid');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
            }
        });

        // Limpiar validación al escribir
        document.getElementById('name').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        // Formatear teléfono automáticamente
        document.getElementById('phone').addEventListener('input', function() {
            let phone = this.value.replace(/\D/g, '');
            if (phone.length > 10) {
                phone = phone.substring(0, 10);
            }
            if (phone.length >= 3) {
                phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
            }
            this.value = phone;
        });
    </script>
</body>
</html>