<?php

require_once __DIR__ . '/../models/ClientModel.php';

class ClientController {
    private $clientModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->clientModel = new ClientModel();
        
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
            'status' => $_GET['status'] ?? '',
            'city' => $_GET['city'] ?? ''
        ];

        $clients = $this->clientModel->getByUser($this->getUserId(), $filters);
        $cities = $this->clientModel->getCities($this->getUserId());
        $stats = $this->clientModel->getStats($this->getUserId());
        
        require_once __DIR__ . '/../views/clients/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }

        $documentTypes = [
            'cc' => 'Cédula de Ciudadanía',
            'ce' => 'Cédula de Extranjería', 
            'nit' => 'NIT',
            'passport' => 'Pasaporte',
            'ti' => 'Tarjeta de Identidad'
        ];
        require_once __DIR__ . '/../views/clients/create.php';
    }

    private function handleCreate() {
        try {
            $data = $this->validateClientData($_POST);
            $data['user_id'] = $this->getUserId();

            $clientId = $this->clientModel->create($data);

            if ($clientId) {
                $_SESSION['success'] = 'Cliente creado exitosamente';
                header('Location: /?action=clients');
            } else {
                $_SESSION['error'] = 'Error al crear el cliente';
                header('Location: /?action=clients&subaction=create');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=clients&subaction=create');
        }
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: /?action=clients');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
            return;
        }

        $client = $this->clientModel->getById($this->getUserId(), $id);
        if (!$client) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: /?action=clients');
            exit;
        }

        $documentTypes = [
            'cc' => 'Cédula de Ciudadanía',
            'ce' => 'Cédula de Extranjería', 
            'nit' => 'NIT',
            'passport' => 'Pasaporte',
            'ti' => 'Tarjeta de Identidad'
        ];
        require_once __DIR__ . '/../views/clients/edit.php';
    }

    private function handleEdit($id) {
        try {
            $data = $this->validateClientData($_POST);

            if ($this->clientModel->update($id, $data, $this->getUserId())) {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
                header('Location: /?action=clients');
            } else {
                $_SESSION['error'] = 'Error al actualizar el cliente';
                header('Location: /?action=clients&subaction=edit&id=' . $id);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /?action=clients&subaction=edit&id=' . $id);
        }
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?action=clients');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: /?action=clients');
            exit;
        }

        if ($this->clientModel->delete($id, $this->getUserId())) {
            $_SESSION['success'] = 'Cliente eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el cliente';
        }

        header('Location: /?action=clients');
        exit;
    }

    public function search() {
        header('Content-Type: application/json');

        $search = $_GET['q'] ?? '';
        if (empty($search)) {
            echo json_encode([]);
            exit;
        }

        $clients = $this->clientModel->search($search, $this->getUserId(), 10);
        echo json_encode($clients);
        exit;
    }

    private function validateClientData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'El nombre es requerido';
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no tiene un formato válido';
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        return [
            'name' => trim($data['name']),
            'email' => trim($data['email'] ?? ''),
            'phone' => trim($data['phone'] ?? ''),
            'document_type' => $data['document_type'] ?? 'cc',
            'document_number' => trim($data['document_number'] ?? ''),
            'address' => trim($data['address'] ?? ''),
            'city' => trim($data['city'] ?? ''),
            'country' => trim($data['country'] ?? 'Colombia'),
            'notes' => trim($data['notes'] ?? ''),
            'status' => in_array($data['status'] ?? 'active', ['active', 'inactive']) ? $data['status'] : 'active'
        ];
    }
}
