<?php

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProductModel.php';

class QuoteController {
    private $quoteModel;
    private $clientModel;
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->quoteModel = new QuoteModel();
        $this->clientModel = new ClientModel();
        $this->productModel = new ProductModel();
        
        if (!$this->isAuthenticated()) {
            header('Location: /?controller=Auth&action=login');
            exit;
        }
    }

    private function isAuthenticated() {
        return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
    }

    private function getUserId() {
        return $_SESSION['user']['id'];
    }

    public function index() {
        // Manejar subacciones POST (como delete)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subaction'])) {
            $subaction = $_POST['subaction'];
            if (method_exists($this, $subaction)) {
                $this->$subaction();
                return;
            }
        }

        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'client_id' => $_GET['client_id'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];

        $quotes = $this->quoteModel->getByUser($this->getUserId(), $filters);
        $clients = $this->clientModel->getByUser($this->getUserId());
        $statuses = $this->quoteModel->getStatuses();
        $stats = $this->quoteModel->getStatsByUser($this->getUserId());
        
        require_once __DIR__ . '/../views/quotes/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        $clients = $this->clientModel->getByUser($this->getUserId());
        $taxRate = 19; // IVA 19%
        require_once __DIR__ . '/../views/quotes/create.php';
    }

    private function handleCreate() {
        try {
            $data = $this->validateQuoteData($_POST);
            $data['user_id'] = $this->getUserId();

            // Procesar items si se enviaron
            $items = [];
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $itemsJson = $_POST['items'];
                $itemsData = json_decode($itemsJson, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($itemsData)) {
                    $items = $this->processQuoteItems($itemsData);
                }
            }

            $quoteId = $this->quoteModel->create($data);

            if ($quoteId && !empty($items)) {
                // Agregar items a la cotización
                foreach ($items as $item) {
                    // Obtener información del producto para la descripción
                    $product = $this->productModel->getById($item['product_id'], $this->getUserId());
                    $description = $product ? $product['name'] : 'Producto no encontrado';
                    
                    // Agregar descripción adicional si se proporcionó
                    if (!empty($item['description'])) {
                        $description .= ' - ' . $item['description'];
                    }
                    
                    // Convertir formato de campos para que coincida con el modelo
                    $itemData = [
                        'product_id' => $item['product_id'],
                        'description' => $description,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount_percentage' => $item['discount_percentage'] ?? 0
                    ];
                    
                    $this->quoteModel->addItem($quoteId, $itemData);
                }
                
                // Recalcular totales
                $this->quoteModel->recalculateTotals($quoteId);
            }

            if ($quoteId) {
                $_SESSION['success'] = 'Cotización creada exitosamente';
                header('Location: /?action=quotes&subaction=edit&id=' . $quoteId);
            } else {
                $_SESSION['error'] = 'Error al crear la cotización';
                header('Location: /?action=quotes&subaction=create');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=quotes&subaction=create');
        }
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de cotización no válido';
            header('Location: /?action=quotes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
            return;
        }

        $quote = $this->quoteModel->getById($id, $this->getUserId());
        if (!$quote) {
            $_SESSION['error'] = 'Cotización no encontrada';
            header('Location: /?action=quotes');
            exit;
        }

        $clients = $this->clientModel->getByUser($this->getUserId());
        $taxRate = 19; // IVA 19%
        $quoteItemsRaw = $this->quoteModel->getItems($id);
        
        // Normalizar items para JavaScript
        $quoteItems = array_map(function($item) {
            return [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'] ?: $item['description'],
                'product_sku' => $item['product_sku'] ?: 'N/A',
                'quantity' => floatval($item['quantity']),
                'price' => floatval($item['unit_price']), // Mapear unit_price a price
                'unit_price' => floatval($item['unit_price']),
                'discount_percentage' => 0, // Calcular desde total si es necesario
                'subtotal' => floatval($item['total']), // Mapear total a subtotal
                'total' => floatval($item['total']),
                'description' => $item['description']
            ];
        }, $quoteItemsRaw);
        
        require_once __DIR__ . '/../views/quotes/edit.php';
    }

    private function handleEdit($id) {
        try {
            $data = $this->validateQuoteData($_POST);

            // Procesar items si se enviaron
            $items = [];
            if (isset($_POST['items']) && !empty($_POST['items'])) {
                $itemsJson = $_POST['items'];
                $itemsData = json_decode($itemsJson, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($itemsData)) {
                    $items = $this->processQuoteItems($itemsData);
                }
            }
            $data['items'] = $items;

            if ($this->quoteModel->update($id, $data, $this->getUserId())) {
                $_SESSION['success'] = 'Cotización actualizada exitosamente';
                header('Location: /?action=quotes&subaction=edit&id=' . $id);
            } else {
                $_SESSION['error'] = 'Error al actualizar la cotización';
                header('Location: /?action=quotes&subaction=edit&id=' . $id);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=quotes&subaction=edit&id=' . $id);
        }
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?action=quotes');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de cotización no válido';
            header('Location: /?action=quotes');
            exit;
        }

        if ($this->quoteModel->delete($id, $this->getUserId())) {
            $_SESSION['success'] = 'Cotización eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la cotización';
        }

        header('Location: /?action=quotes');
        exit;
    }

    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: /?action=quotes');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $status = $_POST['new_status'] ?? $_POST['status'] ?? null;

        if (!$id || !$status) {
            $_SESSION['error'] = 'Datos faltantes para cambiar estado';
            header('Location: /?action=quotes&subaction=edit&id=' . $id);
            exit;
        }

        if ($this->quoteModel->changeStatus($id, $status, $this->getUserId())) {
            $_SESSION['success'] = 'Estado actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el estado';
        }
        
        header('Location: /?action=quotes&subaction=edit&id=' . $id);
        exit;
    }

    // Método temporal para manejar la versión con guión bajo (para compatibilidad)
    public function change_status() {
        return $this->changeStatus();
    }

    public function addItem() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $quoteId = $_POST['quote_id'] ?? null;
        $itemData = [
            'product_id' => $_POST['product_id'] ?? null,
            'description' => $_POST['description'] ?? '',
            'quantity' => floatval($_POST['quantity'] ?? 1),
            'unit_price' => floatval($_POST['unit_price'] ?? 0)
        ];

        if (!$quoteId || empty($itemData['description'])) {
            echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
            exit;
        }

        if ($this->quoteModel->addItem($quoteId, $itemData)) {
            $this->quoteModel->recalculateTotals($quoteId);
            echo json_encode(['success' => true, 'message' => 'Item agregado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar item']);
        }
        exit;
    }

    private function validateQuoteData($data) {
        $errors = [];

        // Validar cliente
        if (empty($data['client_id'])) {
            $errors[] = 'Debe seleccionar un cliente';
        }

        // Validar fecha de cotización
        if (empty($data['issue_date'])) {
            $errors[] = 'La fecha de cotización es obligatoria';
        }

        // Validar fecha válida hasta
        if (!empty($data['valid_until']) && $data['valid_until'] <= $data['issue_date']) {
            $errors[] = 'La fecha válida hasta debe ser posterior a la fecha de cotización';
        }

        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }

        return [
            'client_id' => intval($data['client_id']),
            'issue_date' => $data['issue_date'],
            'valid_until' => !empty($data['valid_until']) ? $data['valid_until'] : null,
            'status' => $data['status'] ?? 'draft',
            'apply_tax' => isset($data['apply_tax']) ? 1 : 0,
            'notes' => trim($data['notes'] ?? ''),
            'terms_conditions' => trim($data['terms_conditions'] ?? '')
        ];
    }

    private function processQuoteItems($items) {
        $processedItems = [];
        
        if (!is_array($items)) {
            return $processedItems;
        }

        foreach ($items as $item) {
            // Obtener precio desde 'price' o 'unit_price'
            $unitPrice = floatval($item['price'] ?? $item['unit_price'] ?? 0);
            
            // Validar que el item tenga los campos mínimos necesarios
            if (empty($item['product_id']) || empty($item['quantity']) || $unitPrice <= 0) {
                continue;
            }

            $processedItems[] = [
                'product_id' => intval($item['product_id']),
                'quantity' => floatval($item['quantity']),
                'unit_price' => $unitPrice,
                'discount_percentage' => floatval($item['discount_percentage'] ?? 0),
                'description' => trim($item['description'] ?? '')
            ];
        }

        return $processedItems;
    }
}
