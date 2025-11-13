<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../components/nav.php'; ?>

    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-invoice text-primary"></i> Cotizaciones</h2>
            <a href="/?action=quotes&subaction=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Cotización
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

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                                <p class="mb-0">Total Cotizaciones</p>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">$<?= number_format($stats['total_amount'], 0, ',', '.') ?></h4>
                                <p class="mb-0">Valor Total</p>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= number_format($stats['sent']) ?></h4>
                                <p class="mb-0">Enviadas</p>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= number_format($stats['delivered']) ?></h4>
                                <p class="mb-0">Entregadas</p>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="fas fa-check-circle"></i>
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
                    <input type="hidden" name="action" value="quotes">
                    
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                               placeholder="Número, cliente...">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="draft" <?= ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' ?>>
                                Borrador
                            </option>
                            <option value="sent" <?= ($_GET['status'] ?? '') === 'sent' ? 'selected' : '' ?>>
                                Enviada
                            </option>
                            <option value="delivered" <?= ($_GET['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>
                                Entregada
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="/?action=quotes" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de cotizaciones -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Lista de Cotizaciones
                    <?php if (!empty($quotes)): ?>
                        <span class="badge bg-secondary"><?= count($quotes) ?></span>
                    <?php endif; ?>
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportTable()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($quotes)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay cotizaciones</h5>
                        <p class="text-muted">No se encontraron cotizaciones con los filtros aplicados.</p>
                        <a href="/?action=quotes&subaction=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primera Cotización
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Válida Hasta</th>
                                    <th>Items</th>
                                    <th>Subtotal</th>
                                    <th>IVA</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotes as $quote): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($quote['quote_number']) ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <?= htmlspecialchars($quote['client_name']) ?>
                                                <?php if ($quote['client_email']): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($quote['client_email']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($quote['issue_date'])) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $validUntil = date('Y-m-d', strtotime($quote['valid_until']));
                                            $today = date('Y-m-d');
                                            $isExpired = $validUntil < $today;
                                            ?>
                                            <span class="<?= $isExpired ? 'text-danger' : 'text-muted' ?>">
                                                <?= date('d/m/Y', strtotime($quote['valid_until'])) ?>
                                                <?php if ($isExpired): ?>
                                                    <i class="fas fa-exclamation-triangle ms-1" title="Expirada"></i>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= $quote['total_items'] ?? 0 ?> items
                                            </span>
                                        </td>
                                        <td>$<?= number_format($quote['subtotal'], 0, ',', '.') ?></td>
                                        <td>$<?= number_format($quote['tax_amount'], 0, ',', '.') ?></td>
                                        <td>
                                            <strong>$<?= number_format($quote['total'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'draft' => 'bg-warning',
                                                'sent' => 'bg-info',
                                                'delivered' => 'bg-success'
                                            ][$quote['status']] ?? 'bg-secondary';
                                            
                                            $statusLabel = [
                                                'draft' => 'Borrador',
                                                'sent' => 'Enviada',
                                                'delivered' => 'Entregada'
                                            ][$quote['status']] ?? 'Desconocido';
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusLabel ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/?action=quotes&subaction=view&id=<?= $quote['id'] ?>" 
                                                   class="btn btn-outline-info btn-sm" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if (in_array($quote['status'], ['draft', 'sent'])): ?>
                                                    <a href="/?action=quotes&subaction=edit&id=<?= $quote['id'] ?>" 
                                                       class="btn btn-outline-primary btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <button type="button" 
                                                        class="btn btn-outline-success btn-sm" 
                                                        onclick="generatePDF(<?= $quote['id'] ?>)" 
                                                        title="Generar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                
                                                <?php if ($quote['status'] === 'draft'): ?>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            onclick="confirmDelete(<?= $quote['id'] ?>)" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
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
                    <p>¿Está seguro de que desea eliminar esta cotización?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Nota:</strong> Esta acción no se puede deshacer. La cotización será marcada como eliminada.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="quotes">
                        <input type="hidden" name="subaction" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Cotización
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para confirmar eliminación
        function confirmDelete(id) {
            document.getElementById('deleteId').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Función para generar PDF
        function generatePDF(id) {
            window.open('/?action=quotes&subaction=pdf&id=' + id, '_blank');
        }

        // Función para exportar tabla
        function exportTable() {
            // Implementar exportación a CSV o Excel
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            window.location.href = '?' + params.toString();
        }

        // Función para imprimir tabla
        function printTable() {
            window.print();
        }

        // Auto-envío del formulario al cambiar filtros
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        // Filtro en tiempo real
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        // Establecer fecha por defecto (último mes)
        if (!document.getElementById('date_from').value) {
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            document.getElementById('date_from').value = lastMonth.toISOString().split('T')[0];
        }

        // Actualizar automáticamente estado de cotizaciones expiradas
        setInterval(function() {
            const expiredRows = document.querySelectorAll('tr:has(.text-danger)');
            expiredRows.forEach(row => {
                const statusBadge = row.querySelector('.badge');
                if (statusBadge && statusBadge.textContent === 'Pendiente') {
                    statusBadge.className = 'badge bg-dark';
                    statusBadge.textContent = 'Expirada';
                }
            });
        }, 60000); // Verificar cada minuto
    </script>
</body>
</html>