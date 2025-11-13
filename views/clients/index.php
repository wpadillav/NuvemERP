<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../components/nav.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Header con título y botón nuevo -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users text-primary"></i> Gestión de Clientes</h2>
                    <a href="/?action=clients&subaction=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Cliente
                    </a>
                </div>

                <!-- Alertas -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Clientes</h6>
                                        <h3 class="mb-0"><?= $stats['total'] ?? 0 ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Activos</h6>
                                        <h3 class="mb-0"><?= $stats['active'] ?? 0 ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Nuevos (30d)</h6>
                                        <h3 class="mb-0"><?= $stats['recent'] ?? 0 ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-user-plus fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Ciudades</h6>
                                        <h3 class="mb-0"><?= count($cities) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-map-marker-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="action" value="clients">
                            
                            <div class="col-md-4">
                                <label class="form-label">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Buscar por nombre, email, documento..."
                                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Ciudad</label>
                                <select class="form-select" name="city">
                                    <option value="">Todas las ciudades</option>
                                    <?php foreach ($cities as $city): ?>
                                        <option value="<?= htmlspecialchars($city) ?>" 
                                                <?= ($_GET['city'] ?? '') === $city ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($city) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Estado</label>
                                <select class="form-select" name="status">
                                    <option value="">Todos</option>
                                    <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2 d-md-flex">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                    <a href="/?action=clients" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de clientes -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($clients)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay clientes registrados</h5>
                                <p class="text-muted">Comienza creando tu primer cliente</p>
                                <a href="/?action=clients&subaction=create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Crear Cliente
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Contacto</th>
                                            <th>Documento</th>
                                            <th>Ubicación</th>
                                            <th>Estado</th>
                                            <th>Creado</th>
                                            <th width="150">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clients as $client): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong class="text-primary"><?= htmlspecialchars($client['name']) ?></strong>
                                                        <?php if ($client['notes']): ?>
                                                            <br><small class="text-muted">
                                                                <i class="fas fa-sticky-note"></i> 
                                                                <?= htmlspecialchars(substr($client['notes'], 0, 30)) ?>...
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($client['email']): ?>
                                                        <div>
                                                            <i class="fas fa-envelope text-muted"></i>
                                                            <a href="mailto:<?= htmlspecialchars($client['email']) ?>">
                                                                <?= htmlspecialchars($client['email']) ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($client['phone']): ?>
                                                        <div>
                                                            <i class="fas fa-phone text-muted"></i>
                                                            <a href="tel:<?= htmlspecialchars($client['phone']) ?>">
                                                                <?= htmlspecialchars($client['phone']) ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($client['document_number']): ?>
                                                        <div>
                                                            <span class="badge bg-secondary">
                                                                <?= strtoupper($client['document_type']) ?>
                                                            </span>
                                                            <br><?= htmlspecialchars($client['document_number']) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin documento</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($client['city']): ?>
                                                        <div>
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                            <?= htmlspecialchars($client['city']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($client['country'] && $client['country'] !== 'Colombia'): ?>
                                                        <small class="text-muted"><?= htmlspecialchars($client['country']) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $client['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= $client['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($client['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="/?action=clients&subaction=edit&id=<?= $client['id'] ?>" 
                                                           class="btn btn-outline-primary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="deleteClient(<?= $client['id'] ?>, '<?= htmlspecialchars($client['name'], ENT_QUOTES) ?>')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el cliente <strong id="clientName"></strong>?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" style="display: inline;" id="deleteForm">
                        <input type="hidden" name="action" value="clients">
                        <input type="hidden" name="subaction" value="delete">
                        <input type="hidden" name="id" id="deleteClientId">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteClient(id, name) {
            document.getElementById('clientName').textContent = name;
            document.getElementById('deleteClientId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>