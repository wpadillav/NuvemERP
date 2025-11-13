<?php

require_once __DIR__ . '/Database.php';

/**
 * Modelo para la gestión de clientes.
 * Maneja todas las operaciones CRUD y validaciones para clientes.
 */
class ClientModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crea un nuevo cliente.
     *
     * @param array $data Datos del cliente
     * @return int|false ID del cliente creado o false en caso de error
     */
    public function create($data) {
        try {
            // Validar email único si se proporciona
            if (!empty($data['email']) && $this->emailExists($data['email'], $data['user_id'])) {
                throw new Exception("El email '{$data['email']}' ya existe para este usuario");
            }

            // Validar documento único si se proporciona
            if (!empty($data['document_number']) && $this->documentExists($data['document_number'], $data['user_id'])) {
                throw new Exception("El documento '{$data['document_number']}' ya existe para este usuario");
            }

            $sql = "INSERT INTO clients (user_id, name, email, phone, document_type, document_number, address, city, country, notes, status) 
                    VALUES (:user_id, :name, :email, :phone, :document_type, :document_number, :address, :city, :country, :notes, :status)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':name' => $data['name'],
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':document_type' => $data['document_type'] ?? 'cc',
                ':document_number' => $data['document_number'] ?? null,
                ':address' => $data['address'] ?? null,
                ':city' => $data['city'] ?? null,
                ':country' => $data['country'] ?? 'Colombia',
                ':notes' => $data['notes'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating client: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los clientes de un usuario.
     *
     * @param int $userId ID del usuario
     * @param array $filters Filtros opcionales (search, status, city)
     * @return array Lista de clientes
     */
    public function getByUser($userId, $filters = []) {
        try {
            $sql = "SELECT * FROM clients WHERE user_id = :user_id AND deleted_at IS NULL";
            $params = [':user_id' => $userId];

            // Filtro de búsqueda
            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR email LIKE :search OR document_number LIKE :search OR phone LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }

            // Filtro por estado
            if (!empty($filters['status'])) {
                $sql .= " AND status = :status";
                $params[':status'] = $filters['status'];
            }

            // Filtro por ciudad
            if (!empty($filters['city'])) {
                $sql .= " AND city = :city";
                $params[':city'] = $filters['city'];
            }

            $sql .= " ORDER BY name";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting clients: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un cliente por su ID.
     *
     * @param int $id ID del cliente
     * @param int $userId ID del usuario (para verificar permisos)
     * @return array|null Datos del cliente o null si no existe
     */
    public function getById($id, $userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM clients WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            error_log("Error getting client by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un cliente existente.
     *
     * @param int $id ID del cliente
     * @param array $data Nuevos datos del cliente
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data, $userId) {
        try {
            // Verificar que el cliente pertenece al usuario
            $client = $this->getById($id, $userId);
            if (!$client) {
                return false;
            }

            // Validar email único si cambió
            if (!empty($data['email']) && $data['email'] !== $client['email'] && 
                $this->emailExists($data['email'], $userId, $id)) {
                throw new Exception("El email '{$data['email']}' ya existe para este usuario");
            }

            // Validar documento único si cambió
            if (!empty($data['document_number']) && $data['document_number'] !== $client['document_number'] && 
                $this->documentExists($data['document_number'], $userId, $id)) {
                throw new Exception("El documento '{$data['document_number']}' ya existe para este usuario");
            }

            $sql = "UPDATE clients SET 
                        name = :name, 
                        email = :email, 
                        phone = :phone, 
                        document_type = :document_type, 
                        document_number = :document_number, 
                        address = :address, 
                        city = :city, 
                        country = :country, 
                        notes = :notes, 
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id AND user_id = :user_id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':name' => $data['name'],
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':document_type' => $data['document_type'] ?? 'cc',
                ':document_number' => $data['document_number'] ?? null,
                ':address' => $data['address'] ?? null,
                ':city' => $data['city'] ?? null,
                ':country' => $data['country'] ?? 'Colombia',
                ':notes' => $data['notes'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);
        } catch (Exception $e) {
            error_log("Error updating client: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un cliente (soft delete).
     *
     * @param int $id ID del cliente
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se eliminó correctamente
     */
    public function delete($id, $userId) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE clients SET deleted_at = CURRENT_TIMESTAMP 
                 WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error deleting client: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un email ya existe para un usuario.
     *
     * @param string $email Email a verificar
     * @param int $userId ID del usuario
     * @param int|null $excludeId ID del cliente a excluir (para actualizaciones)
     * @return bool True si el email existe
     */
    public function emailExists($email, $userId, $excludeId = null) {
        try {
            if (empty($email)) return false;

            $sql = "SELECT COUNT(*) FROM clients 
                    WHERE email = :email AND user_id = :user_id AND deleted_at IS NULL";
            $params = [':email' => $email, ':user_id' => $userId];

            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error checking email existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un documento ya existe para un usuario.
     *
     * @param string $document Documento a verificar
     * @param int $userId ID del usuario
     * @param int|null $excludeId ID del cliente a excluir (para actualizaciones)
     * @return bool True si el documento existe
     */
    public function documentExists($document, $userId, $excludeId = null) {
        try {
            if (empty($document)) return false;

            $sql = "SELECT COUNT(*) FROM clients 
                    WHERE document_number = :document AND user_id = :user_id AND deleted_at IS NULL";
            $params = [':document' => $document, ':user_id' => $userId];

            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error checking document existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene las ciudades disponibles para un usuario.
     *
     * @param int $userId ID del usuario
     * @return array Lista de ciudades
     */
    public function getCities($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT DISTINCT city FROM clients 
                 WHERE user_id = :user_id AND city IS NOT NULL AND deleted_at IS NULL 
                 ORDER BY city"
            );
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting cities: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca clientes por texto.
     *
     * @param string $search Texto de búsqueda
     * @param int $userId ID del usuario
     * @param int $limit Límite de resultados
     * @return array Lista de clientes encontrados
     */
    public function search($search, $userId, $limit = 10) {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, name, email, phone, document_number FROM clients 
                 WHERE user_id = :user_id AND deleted_at IS NULL 
                 AND status = 'active'
                 AND (name LIKE :search OR email LIKE :search OR document_number LIKE :search OR phone LIKE :search)
                 ORDER BY name LIMIT :limit"
            );
            
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching clients: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de clientes para un usuario.
     *
     * @param int $userId ID del usuario
     * @return array Estadísticas de clientes
     */
    public function getStats($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as recent
                 FROM clients 
                 WHERE user_id = :user_id AND deleted_at IS NULL"
            );
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total' => 0,
                'active' => 0,
                'recent' => 0
            ];
        } catch (Exception $e) {
            error_log("Error getting client stats: " . $e->getMessage());
            return ['total' => 0, 'active' => 0, 'recent' => 0];
        }
    }

    /**
     * Obtiene los tipos de documento disponibles.
     *
     * @return array Lista de tipos de documento
     */
    public function getDocumentTypes() {
        return [
            'cc' => 'Cédula de Ciudadanía',
            'nit' => 'NIT',
            'passport' => 'Pasaporte',
            'other' => 'Otro'
        ];
    }
}
?>