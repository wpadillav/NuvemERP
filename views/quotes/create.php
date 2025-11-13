<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cotización</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }
        .product-search-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .product-search-item:hover {
            background-color: #f8f9fa;
        }
        .quote-items-table {
            min-height: 200px;
        }
        .item-row:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../components/nav.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-plus text-primary"></i> Crear Cotización</h2>
                    <a href="/?action=quotes" class="btn btn-outline-secondary">
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

                <form method="POST" id="quoteForm">
                    <input type="hidden" name="action" value="quotes">
                    <input type="hidden" name="subaction" value="create">
                    <input type="hidden" name="items" id="itemsData" value="">

                    <div class="row">
                        <!-- Información de la Cotización -->
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary"></i> Información General</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="client_id" class="form-label">
                                                Cliente <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="client_id" name="client_id" required>
                                                <option value="">Seleccionar cliente...</option>
                                                <?php foreach ($clients as $client): ?>
                                                    <option value="<?= $client['id'] ?>" 
                                                            <?= ($_POST['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($client['name']) ?>
                                                        <?php if ($client['email']): ?>
                                                            - <?= htmlspecialchars($client['email']) ?>
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="issue_date" class="form-label">
                                                Fecha de Cotización <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="issue_date" name="issue_date" 
                                                   value="<?= $_POST['issue_date'] ?? date('Y-m-d') ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="valid_until" class="form-label">
                                                Válida Hasta <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="valid_until" name="valid_until" 
                                                   value="<?= $_POST['valid_until'] ?? date('Y-m-d', strtotime('+30 days')) ?>" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">Estado</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="draft" <?= ($_POST['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                                    Borrador
                                                </option>
                                                <option value="sent" <?= ($_POST['status'] ?? '') === 'sent' ? 'selected' : '' ?>>
                                                    Enviada
                                                </option>
                                                <option value="delivered" <?= ($_POST['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>
                                                    Entregada
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notas</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                  placeholder="Notas adicionales sobre la cotización..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Items de la Cotización -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-list text-primary"></i> Items de la Cotización</h5>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="showAddItemModal()">
                                        <i class="fas fa-plus"></i> Agregar Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive quote-items-table">
                                        <table class="table" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Producto</th>
                                                    <th width="15%">Cantidad</th>
                                                    <th width="15%">Precio Unit.</th>
                                                    <th width="10%">Desc. %</th>
                                                    <th width="15%">Subtotal</th>
                                                    <th width="15%">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemsTableBody">
                                                <tr id="noItemsRow">
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                                        <br>No hay items agregados
                                                        <br><small>Use el botón "Agregar Item" para comenzar</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="col-lg-4">
                            <div class="card position-sticky" style="top: 20px;">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-calculator text-success"></i> Resumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span id="subtotalAmount">$0</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="apply_tax" name="apply_tax" checked onchange="updateTotals()">
                                            <label class="form-check-label" for="apply_tax">
                                                Aplicar IVA (<?= $taxRate ?>%)
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>IVA (<?= $taxRate ?>%):</span>
                                        <span id="taxAmount">$0</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Descuento:</span>
                                        <span id="discountAmount">$0</span>
                                    </div>

                                    <hr>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong>Total:</strong>
                                        <strong class="text-success" id="totalAmount">$0</strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            Items: <span id="totalItems">0</span>
                                        </small>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success" disabled id="submitBtn">
                                            <i class="fas fa-save"></i> Crear Cotización
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline-info" onclick="previewQuote()" disabled id="previewBtn">
                                            <i class="fas fa-eye"></i> Vista Previa
                                        </button>
                                        
                                        <a href="/?action=quotes" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para agregar item -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus text-primary"></i> Agregar Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productSearch" class="form-label">
                                    Buscar Producto <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="productSearch" 
                                           placeholder="Escriba para buscar productos..." autocomplete="off">
                                    <div class="product-search-results d-none" id="searchResults"></div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="itemQuantity" class="form-label">
                                    Cantidad <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="itemQuantity" 
                                       min="1" step="1" value="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="itemPrice" class="form-label">
                                    Precio Unitario <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="itemPrice" 
                                           min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="itemDiscount" class="form-label">Descuento %</label>
                                <input type="number" class="form-control" id="itemDiscount" 
                                       min="0" max="100" step="0.01" value="0">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="itemSubtotal" class="form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" id="itemSubtotal" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="itemDescription" class="form-label">Descripción Adicional</label>
                            <textarea class="form-control" id="itemDescription" rows="2" 
                                      placeholder="Descripción o notas adicionales..."></textarea>
                        </div>

                        <!-- Información del producto seleccionado -->
                        <div class="card bg-light d-none" id="selectedProductInfo">
                            <div class="card-body">
                                <h6 class="card-title">Producto Seleccionado</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong id="selectedProductName">-</strong><br>
                                        <small class="text-muted">SKU: <span id="selectedProductSku">-</span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            Stock: <span id="selectedProductStock">-</span><br>
                                            Precio: $<span id="selectedProductPrice">-</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="selectedProductId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="addItem()" disabled id="addItemBtn">
                        <i class="fas fa-plus"></i> Agregar Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        let quoteItems = [];
        let products = [];
        const taxRate = <?= $taxRate ?>;
        let addItemModal;

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            addItemModal = new bootstrap.Modal(document.getElementById('addItemModal'));
            setupEventListeners();
        });

        // Configurar event listeners
        function setupEventListeners() {
            // Búsqueda de productos con debounce
            let searchTimeout;
            document.getElementById('productSearch').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchProducts, 300); // 300ms delay
            });
            
            // Cálculo automático del subtotal
            ['itemQuantity', 'itemPrice', 'itemDiscount'].forEach(id => {
                document.getElementById(id).addEventListener('input', calculateItemSubtotal);
            });

            // Validación del formulario
            document.getElementById('addItemForm').addEventListener('input', validateItemForm);
        }

        // Buscar productos con AJAX en tiempo real
        async function searchProducts() {
            const query = document.getElementById('productSearch').value.trim();
            const resultsDiv = document.getElementById('searchResults');
            
            if (query.length < 2) {
                resultsDiv.classList.add('d-none');
                return;
            }

            try {
                // Mostrar indicador de carga
                resultsDiv.innerHTML = '<div class="product-search-item text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
                resultsDiv.classList.remove('d-none');

                // Realizar búsqueda AJAX
                const response = await fetch(`/?action=products&subaction=search&ajax=1&q=${encodeURIComponent(query)}`);
                
                if (!response.ok) {
                    throw new Error('Error en la búsqueda');
                }

                const filteredProducts = await response.json();

                if (filteredProducts.length === 0) {
                    resultsDiv.innerHTML = '<div class="product-search-item text-muted">No se encontraron productos</div>';
                } else {
                    resultsDiv.innerHTML = filteredProducts.map(product => `
                        <div class="product-search-item" onclick="selectProduct(${product.id}, '${product.name}', '${product.sku}', ${product.price}, ${product.stock})">
                            <strong>${product.name}</strong>
                            <br><small class="text-muted">SKU: ${product.sku} | Stock: ${product.stock} | $${parseFloat(product.price).toLocaleString()}</small>
                        </div>
                    `).join('');
                }
                
                resultsDiv.classList.remove('d-none');
            } catch (error) {
                console.error('Error en búsqueda:', error);
                resultsDiv.innerHTML = '<div class="product-search-item text-danger">Error al buscar productos</div>';
                resultsDiv.classList.remove('d-none');
            }
        }

        // Seleccionar producto
        function selectProduct(productId, name, sku, price, stock) {
            // Crear objeto producto con los datos recibidos
            const product = {
                id: productId,
                name: name,
                sku: sku,
                price: price,
                stock: stock
            };

            document.getElementById('selectedProductId').value = product.id;
            document.getElementById('productSearch').value = product.name;
            document.getElementById('itemPrice').value = product.price;
            
            // Mostrar información del producto
            document.getElementById('selectedProductName').textContent = product.name;
            document.getElementById('selectedProductSku').textContent = product.sku;
            document.getElementById('selectedProductStock').textContent = product.stock;
            document.getElementById('selectedProductPrice').textContent = parseFloat(product.price).toLocaleString();
            document.getElementById('selectedProductInfo').classList.remove('d-none');
            
            // Ocultar resultados
            document.getElementById('searchResults').classList.add('d-none');
            
            calculateItemSubtotal();
            validateItemForm();
        }

        // Calcular subtotal del item
        function calculateItemSubtotal() {
            const quantity = parseFloat(document.getElementById('itemQuantity').value) || 0;
            const price = parseFloat(document.getElementById('itemPrice').value) || 0;
            const discount = parseFloat(document.getElementById('itemDiscount').value) || 0;
            
            const subtotal = quantity * price * (1 - discount / 100);
            document.getElementById('itemSubtotal').value = subtotal.toFixed(2);
        }

        // Validar formulario de item
        function validateItemForm() {
            const productId = document.getElementById('selectedProductId').value;
            const quantity = document.getElementById('itemQuantity').value;
            const price = document.getElementById('itemPrice').value;
            
            const isValid = productId && quantity > 0 && price > 0;
            document.getElementById('addItemBtn').disabled = !isValid;
        }

        // Mostrar modal para agregar item
        function showAddItemModal() {
            // Limpiar formulario
            document.getElementById('addItemForm').reset();
            document.getElementById('selectedProductId').value = '';
            document.getElementById('selectedProductInfo').classList.add('d-none');
            document.getElementById('searchResults').classList.add('d-none');
            document.getElementById('itemQuantity').value = 1;
            document.getElementById('itemDiscount').value = 0;
            
            addItemModal.show();
        }

        // Agregar item a la cotización
        function addItem() {
            const productId = parseInt(document.getElementById('selectedProductId').value);
            const productName = document.getElementById('selectedProductName').textContent;
            const productSku = document.getElementById('selectedProductSku').textContent;
            const quantity = parseFloat(document.getElementById('itemQuantity').value);
            const price = parseFloat(document.getElementById('itemPrice').value);
            const discount = parseFloat(document.getElementById('itemDiscount').value);
            const description = document.getElementById('itemDescription').value;
            
            const item = {
                id: Date.now(), // ID temporal
                product_id: productId,
                product_name: productName,
                product_sku: productSku,
                quantity: quantity,
                price: price,
                discount_percentage: discount,
                subtotal: quantity * price * (1 - discount / 100),
                description: description
            };

            quoteItems.push(item);
            updateItemsTable();
            updateSummary();
            addItemModal.hide();
        }

        // Actualizar tabla de items
        function updateItemsTable() {
            const tbody = document.getElementById('itemsTableBody');
            
            if (quoteItems.length === 0) {
                tbody.innerHTML = `
                    <tr id="noItemsRow">
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <br>No hay items agregados
                            <br><small>Use el botón "Agregar Item" para comenzar</small>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = quoteItems.map((item, index) => `
                <tr class="item-row">
                    <td>
                        <strong>${item.product_name}</strong>
                        <br><small class="text-muted">SKU: ${item.product_sku}</small>
                        ${item.description ? `<br><small class="text-info">${item.description}</small>` : ''}
                    </td>
                    <td>${item.quantity}</td>
                    <td>$${item.price.toLocaleString()}</td>
                    <td>${item.discount_percentage > 0 ? item.discount_percentage + '%' : '-'}</td>
                    <td><strong>$${item.subtotal.toLocaleString()}</strong></td>
                    <td>
                        <button type="button" class="btn btn-outline-primary btn-sm me-1" onclick="editItem(${index})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItem(${index})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Eliminar item
        function removeItem(index) {
            if (confirm('¿Está seguro de eliminar este item?')) {
                quoteItems.splice(index, 1);
                updateItemsTable();
                updateSummary();
            }
        }

        // Función para actualizar totales cuando cambia el checkbox de IVA
        function updateTotals() {
            updateSummary();
        }

        // Actualizar resumen
        function updateSummary() {
            const subtotal = quoteItems.reduce((sum, item) => sum + item.subtotal, 0);
            const discountAmount = quoteItems.reduce((sum, item) => sum + (item.quantity * item.price * item.discount_percentage / 100), 0);
            const applyTax = document.getElementById('apply_tax').checked;
            const taxAmount = applyTax ? subtotal * (taxRate / 100) : 0;
            const total = subtotal + taxAmount;

            document.getElementById('subtotalAmount').textContent = '$' + subtotal.toLocaleString();
            document.getElementById('discountAmount').textContent = '$' + discountAmount.toLocaleString();
            document.getElementById('taxAmount').textContent = '$' + taxAmount.toLocaleString();
            document.getElementById('totalAmount').textContent = '$' + total.toLocaleString();
            document.getElementById('totalItems').textContent = quoteItems.length;

            // Habilitar/deshabilitar botones
            const hasItems = quoteItems.length > 0;
            document.getElementById('submitBtn').disabled = !hasItems;
            document.getElementById('previewBtn').disabled = !hasItems;

            // Actualizar datos para enviar
            document.getElementById('itemsData').value = JSON.stringify(quoteItems);
        }

        // Vista previa
        function previewQuote() {
            // Implementar vista previa
            alert('Función de vista previa en desarrollo');
        }

        // Ocultar resultados al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.position-relative')) {
                document.getElementById('searchResults').classList.add('d-none');
            }
        });
    </script>
</body>
</html>