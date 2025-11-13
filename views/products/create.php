<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
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
                    <h2><i class="fas fa-plus-circle text-success"></i> Crear Producto</h2>
                    <a href="/?action=products" class="btn btn-outline-secondary">
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
                    <div class="card-body">
                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="products">
                            <input type="hidden" name="subaction" value="create">

                            <div class="row">
                                <!-- SKU y Nombre -->
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">
                                        SKU <span class="text-danger">*</span>
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Código único del producto"></i>
                                    </label>
                                    <input type="text" class="form-control" id="sku" name="sku" 
                                           required maxlength="50"
                                           value="<?= htmlspecialchars($_POST['sku'] ?? '') ?>"
                                           placeholder="Ej: PROD-001">
                                    <div class="invalid-feedback">
                                        El SKU es requerido y debe ser único
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           required maxlength="255"
                                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                           placeholder="Nombre del producto">
                                    <div class="invalid-feedback">
                                        El nombre es requerido
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="3" placeholder="Descripción detallada del producto"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <!-- Precios -->
                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label">
                                        Precio de Venta
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Precio al que se vende el producto"></i>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               step="0.01" min="0"
                                               value="<?= htmlspecialchars($_POST['price'] ?? '0.00') ?>"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="cost" class="form-label">
                                        Costo
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Costo de adquisición o producción"></i>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="cost" name="cost" 
                                               step="0.01" min="0"
                                               value="<?= htmlspecialchars($_POST['cost'] ?? '0.00') ?>"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label">Categoría</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="category" name="category" 
                                               list="categories" maxlength="100"
                                               value="<?= htmlspecialchars($_POST['category'] ?? '') ?>"
                                               placeholder="Categoría del producto">
                                        <datalist id="categories">
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= htmlspecialchars($category) ?>">
                                            <?php endforeach; ?>
                                        </datalist>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Stock -->
                                <div class="col-md-4 mb-3">
                                    <label for="stock" class="form-label">
                                        Stock Actual
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Cantidad disponible en inventario"></i>
                                    </label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           min="0"
                                           value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>"
                                           placeholder="0">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="min_stock" class="form-label">
                                        Stock Mínimo
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Cantidad mínima antes de alerta"></i>
                                    </label>
                                    <input type="number" class="form-control" id="min_stock" name="min_stock" 
                                           min="0"
                                           value="<?= htmlspecialchars($_POST['min_stock'] ?? '0') ?>"
                                           placeholder="0">
                                </div>

                                <div class="col-md-4 mb-3">
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

                            <!-- Información calculada -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-calculator text-info"></i> Información Calculada
                                            </h6>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">Margen:</small>
                                                    <div id="margin">-</div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">% Margen:</small>
                                                    <div id="marginPercent">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="/?action=products" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Crear Producto
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
        // Calcular margen en tiempo real
        function calculateMargin() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const cost = parseFloat(document.getElementById('cost').value) || 0;
            
            const margin = price - cost;
            const marginPercent = cost > 0 ? ((margin / cost) * 100) : 0;
            
            document.getElementById('margin').textContent = '$' + margin.toFixed(2);
            document.getElementById('marginPercent').textContent = marginPercent.toFixed(1) + '%';
        }

        // Event listeners
        document.getElementById('price').addEventListener('input', calculateMargin);
        document.getElementById('cost').addEventListener('input', calculateMargin);

        // Calcular al cargar
        calculateMargin();

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const sku = document.getElementById('sku').value.trim();
            const name = document.getElementById('name').value.trim();
            
            if (!sku || !name) {
                e.preventDefault();
                
                if (!sku) {
                    document.getElementById('sku').classList.add('is-invalid');
                }
                if (!name) {
                    document.getElementById('name').classList.add('is-invalid');
                }
            }
        });

        // Limpiar validación al escribir
        document.getElementById('sku').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        document.getElementById('name').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    </script>
</body>
</html>