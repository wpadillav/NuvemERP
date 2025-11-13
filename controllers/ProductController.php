<?php

require_once __DIR__ . '/../models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        $products = $this->productModel->getByUser($this->getUserId(), $filters);
        $categories = $this->productModel->getCategories($this->getUserId());
        
        require_once __DIR__ . '/../views/products/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        $categories = $this->productModel->getCategories($this->getUserId());
        require_once __DIR__ . '/../views/products/create.php';
    }

    private function handleCreate() {
        try {
            $data = $this->validateProductData($_POST);
            $data['user_id'] = $this->getUserId();

            $productId = $this->productModel->create($data);

            if ($productId) {
                $_SESSION['success'] = 'Producto creado exitosamente';
                header('Location: /?action=products');
            } else {
                $_SESSION['error'] = 'Error al crear el producto';
                header('Location: /?action=products&subaction=create');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=products&subaction=create');
        }
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de producto no válido';
            header('Location: /?action=products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
            return;
        }

        $product = $this->productModel->getById($id, $this->getUserId());
        if (!$product) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: /?action=products');
            exit;
        }

        $categories = $this->productModel->getCategories($this->getUserId());
        require_once __DIR__ . '/../views/products/edit.php';
    }

    private function handleEdit($id) {
        try {
            $data = $this->validateProductData($_POST);

            if ($this->productModel->update($id, $data, $this->getUserId())) {
                $_SESSION['success'] = 'Producto actualizado exitosamente';
                header('Location: /?action=products');
            } else {
                $_SESSION['error'] = 'Error al actualizar el producto';
                header('Location: /?action=products&subaction=edit&id=' . $id);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=products&subaction=edit&id=' . $id);
        }
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?action=products');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de producto no válido';
            header('Location: /?action=products');
            exit;
        }

        if ($this->productModel->delete($id, $this->getUserId())) {
            $_SESSION['success'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el producto';
        }

        header('Location: /?action=products');
        exit;
    }

    private function validateProductData($data) {
        $errors = [];

        if (empty($data['sku'])) {
            $errors[] = 'El SKU es requerido';
        }
        if (empty($data['name'])) {
            $errors[] = 'El nombre es requerido';
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        return [
            'sku' => trim($data['sku']),
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'price' => floatval($data['price'] ?? 0),
            'cost' => floatval($data['cost'] ?? 0),
            'stock' => intval($data['stock'] ?? 0),
            'min_stock' => intval($data['min_stock'] ?? 0),
            'category' => trim($data['category'] ?? ''),
            'status' => in_array($data['status'] ?? 'active', ['active', 'inactive']) ? $data['status'] : 'active'
        ];
    }

    public function search() {
        // Este método es para búsquedas AJAX desde las cotizaciones
        header('Content-Type: application/json');
        
        if (!isset($_GET['ajax']) || $_GET['ajax'] !== '1') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }

        $query = $_GET['q'] ?? '';
        $filters = [
            'search' => $query,
            'status' => 'active' // Solo productos activos para cotizaciones
        ];

        $products = $this->productModel->getByUser($this->getUserId(), $filters);
        
        // Formatear productos para AJAX
        $formattedProducts = array_map(function($product) {
            return [
                'id' => $product['id'],
                'name' => $product['name'],
                'sku' => $product['sku'],
                'price' => floatval($product['price']),
                'stock' => intval($product['stock']),
                'description' => $product['description']
            ];
        }, $products);

        echo json_encode($formattedProducts);
        exit;
    }
}
