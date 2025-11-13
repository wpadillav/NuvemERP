<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
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
                    <h2><i class="fas fa-user-edit text-primary"></i> Editar Cliente</h2>
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

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Formulario -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-user text-primary"></i> Información del Cliente</h5>
                        <div class="text-muted small">
                            <i class="fas fa-clock"></i> Creado: <?= date('d/m/Y H:i', strtotime($client['created_at'])) ?>
                            <?php if ($client['updated_at']): ?>
                                | Actualizado: <?= date('d/m/Y H:i', strtotime($client['updated_at'])) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="clients">
                            <input type="hidden" name="subaction" value="edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($client['id']) ?>">

                            <!-- Información básica -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Nombre Completo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           required maxlength="255"
                                           value="<?= htmlspecialchars($_POST['name'] ?? $client['name']) ?>"
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
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               maxlength="255"
                                               value="<?= htmlspecialchars($_POST['email'] ?? $client['email']) ?>"
                                               placeholder="email@ejemplo.com">
                                        <?php if ($client['email']): ?>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="window.open('mailto:<?= htmlspecialchars($client['email']) ?>')"
                                                    title="Enviar email">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ingrese un email válido
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               maxlength="50"
                                               value="<?= htmlspecialchars($_POST['phone'] ?? $client['phone']) ?>"
                                               placeholder="+57 300 123 4567">
                                        <?php if ($client['phone']): ?>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="window.open('tel:<?= htmlspecialchars($client['phone']) ?>')"
                                                    title="Llamar">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= ($_POST['status'] ?? $client['status']) === 'active' ? 'selected' : '' ?>>
                                            <i class="fas fa-check-circle text-success"></i> Activo
                                        </option>
                                        <option value="inactive" <?= ($_POST['status'] ?? $client['status']) === 'inactive' ? 'selected' : '' ?>>
                                            <i class="fas fa-pause-circle text-warning"></i> Inactivo
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
                                            <option value="<?= $key ?>" <?= ($_POST['document_type'] ?? $client['document_type']) === $key ? 'selected' : '' ?>>
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
                                           value="<?= htmlspecialchars($_POST['document_number'] ?? $client['document_number']) ?>"
                                           placeholder="Número del documento">
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <textarea class="form-control" id="address" name="address" 
                                          rows="2" placeholder="Dirección completa"><?= htmlspecialchars($_POST['address'] ?? $client['address']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           maxlength="100"
                                           value="<?= htmlspecialchars($_POST['city'] ?? $client['city']) ?>"
                                           placeholder="Ciudad de residencia">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">País</label>
                                    <input type="text" class="form-control" id="country" name="country" 
                                           maxlength="100"
                                           value="<?= htmlspecialchars($_POST['country'] ?? $client['country']) ?>"
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
                                          rows="3" placeholder="Notas, comentarios o información adicional..."><?= htmlspecialchars($_POST['notes'] ?? $client['notes']) ?></textarea>
                            </div>

                            <!-- Historial de cambios -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-history text-info"></i> Historial de Cambios
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <strong>Cliente #<?= $client['id'] ?></strong><br>
                                                Creado: <?= date('d/m/Y H:i', strtotime($client['created_at'])) ?>
                                                <?php if ($client['updated_at']): ?>
                                                    <br>Última actualización: <?= date('d/m/Y H:i', strtotime($client['updated_at'])) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <?php if ($client['status'] === 'active'): ?>
                                                    <span class="badge bg-success me-2">
                                                        <i class="fas fa-check-circle"></i> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-pause-circle"></i> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="/?action=clients" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="button" class="btn btn-outline-danger ms-2" 
                                            onclick="confirmDelete(<?= $client['id'] ?>)">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-info me-2" onclick="resetForm()">
                                        <i class="fas fa-undo"></i> Restablecer
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar este cliente?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Nota:</strong> Esta acción no se puede deshacer. El cliente será marcado como eliminado pero se conservarán los datos para referencia histórica.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="clients">
                        <input type="hidden" name="subaction" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Cliente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Datos originales para restablecer
        const originalData = {
            name: '<?= htmlspecialchars($client['name']) ?>',
            email: '<?= htmlspecialchars($client['email']) ?>',
            phone: '<?= htmlspecialchars($client['phone']) ?>',
            status: '<?= htmlspecialchars($client['status']) ?>',
            document_type: '<?= htmlspecialchars($client['document_type']) ?>',
            document_number: '<?= htmlspecialchars($client['document_number']) ?>',
            address: '<?= htmlspecialchars($client['address']) ?>',
            city: '<?= htmlspecialchars($client['city']) ?>',
            country: '<?= htmlspecialchars($client['country']) ?>',
            notes: '<?= htmlspecialchars($client['notes']) ?>'
        };

        // Función para restablecer el formulario
        function resetForm() {
            Object.keys(originalData).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    element.value = originalData[key];
                    element.classList.remove('is-invalid');
                }
            });
        }

        // Función para confirmar eliminación
        function confirmDelete(id) {
            document.getElementById('deleteId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            if (e.target.querySelector('input[name="subaction"]').value !== 'edit') {
                return; // Solo validar el formulario de edición
            }

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

        // Detectar cambios en el formulario
        let hasUnsavedChanges = false;
        
        const formElements = document.querySelectorAll('input, select, textarea');
        formElements.forEach(element => {
            element.addEventListener('input', function() {
                hasUnsavedChanges = true;
            });
        });

        // Advertir antes de salir con cambios sin guardar
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // No advertir al enviar el formulario
        document.querySelector('form').addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });
    </script>
</body>
</html>