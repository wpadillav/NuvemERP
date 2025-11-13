<?php

require_once __DIR__ . '/Database.php';

/**
 * Modelo para la gestión de cotizaciones.
 * Maneja todas las operaciones CRUD para cotizaciones y sus items.
 */
class QuoteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crea una nueva cotización.
     *
     * @param array $data Datos de la cotización
     * @return int|false ID de la cotización creada o false en caso de error
     */
    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Generar número de cotización
            $quoteNumber = $this->generateQuoteNumber($data['user_id']);

            $sql = "INSERT INTO quotes (user_id, client_id, quote_number, status, issue_date, valid_until, 
                                     subtotal, tax_rate, apply_tax, tax_amount, total, notes, terms_conditions) 
                    VALUES (:user_id, :client_id, :quote_number, :status, :issue_date, :valid_until, 
                            :subtotal, :tax_rate, :apply_tax, :tax_amount, :total, :notes, :terms_conditions)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':client_id' => $data['client_id'],
                ':quote_number' => $quoteNumber,
                ':status' => $data['status'] ?? 'draft',
                ':issue_date' => $data['issue_date'] ?? date('Y-m-d'),
                ':valid_until' => $data['valid_until'] ?? null,
                ':subtotal' => $data['subtotal'] ?? 0.00,
                ':tax_rate' => $data['tax_rate'] ?? 19.00,
                ':apply_tax' => $data['apply_tax'] ?? 1,
                ':tax_amount' => $data['tax_amount'] ?? 0.00,
                ':total' => $data['total'] ?? 0.00,
                ':notes' => $data['notes'] ?? null,
                ':terms_conditions' => $data['terms_conditions'] ?? null
            ]);

            $quoteId = $this->db->lastInsertId();

            // Agregar items si se proporcionan
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $index => $item) {
                    $this->addItem($quoteId, [
                        'product_id' => $item['product_id'] ?? null,
                        'description' => $item['description'],
                        'quantity' => $item['quantity'] ?? 1,
                        'unit_price' => $item['unit_price'] ?? 0,
                        'sort_order' => $index + 1
                    ]);
                }

                // Recalcular totales
                $this->recalculateTotals($quoteId);
            }

            $this->db->commit();
            return $quoteId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error creating quote: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todas las cotizaciones de un usuario.
     *
     * @param int $userId ID del usuario
     * @param array $filters Filtros opcionales (search, status, client_id, date_from, date_to)
     * @return array Lista de cotizaciones con información del cliente
     */
    public function getByUser($userId, $filters = []) {
        try {
            $sql = "SELECT q.*, c.name as client_name, c.email as client_email,
                           (SELECT COUNT(*) FROM quote_items qi WHERE qi.quote_id = q.id) as total_items
                    FROM quotes q 
                    INNER JOIN clients c ON q.client_id = c.id 
                    WHERE q.user_id = :user_id AND q.deleted_at IS NULL";
            $params = [':user_id' => $userId];

            // Filtro de búsqueda
            if (!empty($filters['search'])) {
                $sql .= " AND (q.quote_number LIKE :search OR c.name LIKE :search OR q.notes LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }

            // Filtro por estado
            if (!empty($filters['status'])) {
                $sql .= " AND q.status = :status";
                $params[':status'] = $filters['status'];
            }

            // Filtro por cliente
            if (!empty($filters['client_id'])) {
                $sql .= " AND q.client_id = :client_id";
                $params[':client_id'] = $filters['client_id'];
            }

            // Filtro por fecha desde
            if (!empty($filters['date_from'])) {
                $sql .= " AND q.issue_date >= :date_from";
                $params[':date_from'] = $filters['date_from'];
            }

            // Filtro por fecha hasta
            if (!empty($filters['date_to'])) {
                $sql .= " AND q.issue_date <= :date_to";
                $params[':date_to'] = $filters['date_to'];
            }

            $sql .= " ORDER BY q.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting quotes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene una cotización por su ID con todos sus items.
     *
     * @param int $id ID de la cotización
     * @param int $userId ID del usuario (para verificar permisos)
     * @return array|null Datos de la cotización con items o null si no existe
     */
    public function getById($id, $userId) {
        try {
            // Obtener datos de la cotización
            $stmt = $this->db->prepare(
                "SELECT q.*, c.name as client_name, c.email as client_email, c.phone as client_phone,
                        c.document_type, c.document_number, c.address as client_address, c.city as client_city
                 FROM quotes q 
                 INNER JOIN clients c ON q.client_id = c.id 
                 WHERE q.id = :id AND q.user_id = :user_id AND q.deleted_at IS NULL"
            );
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            
            $quote = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$quote) {
                return null;
            }



            // Obtener items de la cotización
            $quote['items'] = $this->getItems($id);
            
            return $quote;
        } catch (Exception $e) {
            error_log("Error getting quote by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza una cotización existente.
     *
     * @param int $id ID de la cotización
     * @param array $data Nuevos datos de la cotización
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data, $userId) {
        try {
            // Verificar que la cotización pertenece al usuario
            $quote = $this->getById($id, $userId);
            if (!$quote) {
                return false;
            }

            $this->db->beginTransaction();

            $status = $data['status'] ?? 'draft';

            $sql = "UPDATE quotes SET 
                        client_id = :client_id, 
                        status = :status, 
                        issue_date = :issue_date, 
                        valid_until = :valid_until, 
                        apply_tax = :apply_tax,
                        notes = :notes, 
                        terms_conditions = :terms_conditions,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id AND user_id = :user_id";

            $stmt = $this->db->prepare($sql);
            $updated = $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':client_id' => $data['client_id'],
                ':status' => $status,
                ':issue_date' => $data['issue_date'] ?? date('Y-m-d'),
                ':valid_until' => $data['valid_until'] ?? null,
                ':apply_tax' => $data['apply_tax'] ?? 1,
                ':notes' => $data['notes'] ?? null,
                ':terms_conditions' => $data['terms_conditions'] ?? null
            ]);

            if ($updated && isset($data['items']) && is_array($data['items'])) {
                // Eliminar items existentes
                $this->clearItems($id);

                // Agregar nuevos items
                foreach ($data['items'] as $index => $item) {
                    $this->addItem($id, [
                        'product_id' => $item['product_id'] ?? null,
                        'description' => $item['description'],
                        'quantity' => $item['quantity'] ?? 1,
                        'unit_price' => $item['unit_price'] ?? 0,
                        'sort_order' => $index + 1
                    ]);
                }

                // Recalcular totales
                $this->recalculateTotals($id);
            }

            $this->db->commit();
            return $updated;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating quote: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una cotización (soft delete).
     *
     * @param int $id ID de la cotización
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se eliminó correctamente
     */
    public function delete($id, $userId) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE quotes SET deleted_at = CURRENT_TIMESTAMP 
                 WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error deleting quote: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Agrega un item a una cotización.
     *
     * @param int $quoteId ID de la cotización
     * @param array $itemData Datos del item
     * @return bool True si se agregó correctamente
     */
    public function addItem($quoteId, $itemData) {
        try {
            $quantity = floatval($itemData['quantity'] ?? 1);
            $unitPrice = floatval($itemData['unit_price'] ?? 0);
            $discountPercentage = floatval($itemData['discount_percentage'] ?? 0);
            
            // Calcular total con descuento
            $subtotal = $quantity * $unitPrice;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            $total = $subtotal - $discountAmount;

            $sql = "INSERT INTO quote_items (quote_id, product_id, description, quantity, unit_price, total, sort_order) 
                    VALUES (:quote_id, :product_id, :description, :quantity, :unit_price, :total, :sort_order)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':quote_id' => $quoteId,
                ':product_id' => $itemData['product_id'] ?? null,
                ':description' => $itemData['description'],
                ':quantity' => $quantity,
                ':unit_price' => $unitPrice,
                ':total' => $total,
                ':sort_order' => $itemData['sort_order'] ?? 0
            ]);
        } catch (Exception $e) {
            error_log("Error adding quote item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene los items de una cotización.
     *
     * @param int $quoteId ID de la cotización
     * @return array Lista de items
     */
    public function getItems($quoteId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT qi.*, p.name as product_name, p.sku as product_sku 
                 FROM quote_items qi 
                 LEFT JOIN products p ON qi.product_id = p.id 
                 WHERE qi.quote_id = :quote_id 
                 ORDER BY qi.sort_order, qi.id"
            );
            $stmt->execute([':quote_id' => $quoteId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting quote items: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Elimina todos los items de una cotización.
     *
     * @param int $quoteId ID de la cotización
     * @return bool True si se eliminaron correctamente
     */
    public function clearItems($quoteId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM quote_items WHERE quote_id = :quote_id");
            return $stmt->execute([':quote_id' => $quoteId]);
        } catch (Exception $e) {
            error_log("Error clearing quote items: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Recalcula los totales de una cotización basándose en sus items.
     *
     * @param int $quoteId ID de la cotización
     * @return bool True si se recalculó correctamente
     */
    public function recalculateTotals($quoteId) {
        try {
            // Obtener subtotal de los items
            $stmt = $this->db->prepare("SELECT SUM(total) FROM quote_items WHERE quote_id = :quote_id");
            $stmt->execute([':quote_id' => $quoteId]);
            $subtotal = $stmt->fetchColumn() ?: 0;

            // Obtener tasa de impuesto y si se aplica
            $stmt = $this->db->prepare("SELECT tax_rate, apply_tax FROM quotes WHERE id = :quote_id");
            $stmt->execute([':quote_id' => $quoteId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $taxRate = $row['tax_rate'] ?: 19.00;
            $applyTax = $row['apply_tax'] ?: 0;

            // Calcular impuestos y total
            $taxAmount = $applyTax ? ($subtotal * $taxRate) / 100 : 0;
            $total = $subtotal + $taxAmount;

            // Actualizar totales
            $stmt = $this->db->prepare(
                "UPDATE quotes SET subtotal = :subtotal, tax_amount = :tax_amount, total = :total 
                 WHERE id = :quote_id"
            );
            
            return $stmt->execute([
                ':quote_id' => $quoteId,
                ':subtotal' => $subtotal,
                ':tax_amount' => $taxAmount,
                ':total' => $total
            ]);
        } catch (Exception $e) {
            error_log("Error recalculating quote totals: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Genera un número único de cotización para un usuario.
     *
     * @param int $userId ID del usuario
     * @return string Número de cotización generado
     */
    public function generateQuoteNumber($userId) {
        try {
            $year = date('Y');
            $prefix = "COT-{$year}-";

            // Obtener el último número para este año y usuario
            $stmt = $this->db->prepare(
                "SELECT quote_number FROM quotes 
                 WHERE user_id = :user_id AND quote_number LIKE :prefix 
                 ORDER BY id DESC LIMIT 1"
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':prefix' => $prefix . '%'
            ]);

            $lastNumber = $stmt->fetchColumn();
            
            if ($lastNumber) {
                // Extraer el número secuencial
                $parts = explode('-', $lastNumber);
                $sequence = intval(end($parts)) + 1;
            } else {
                $sequence = 1;
            }

            return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            error_log("Error generating quote number: " . $e->getMessage());
            return 'COT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Cambia el estado de una cotización.
     *
     * @param int $id ID de la cotización
     * @param string $status Nuevo estado
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se cambió correctamente
     */
    public function changeStatus($id, $status, $userId) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE quotes SET status = :status, updated_at = CURRENT_TIMESTAMP 
                 WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            
            return $stmt->execute([
                ':id' => $id,
                ':status' => $status,
                ':user_id' => $userId
            ]);
        } catch (Exception $e) {
            error_log("Error changing quote status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas de cotizaciones para un usuario.
     *
     * @param int $userId ID del usuario
     * @return array Estadísticas de cotizaciones
     */
    public function getStatsByUser($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status = 'delivered' THEN total ELSE 0 END) as delivered_amount,
                    SUM(total) as total_amount
                 FROM quotes 
                 WHERE user_id = :user_id AND deleted_at IS NULL"
            );
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'total' => 0, 'draft' => 0, 'sent' => 0, 'delivered' => 0,
                'delivered_amount' => 0, 'total_amount' => 0
            ];
        } catch (Exception $e) {
            error_log("Error getting quote stats: " . $e->getMessage());
            return ['total' => 0, 'draft' => 0, 'sent' => 0, 'delivered' => 0,
                   'delivered_amount' => 0, 'total_amount' => 0];
        }
    }

    /**
     * Obtiene los estados disponibles para cotizaciones.
     *
     * @return array Lista de estados
     */
    public function getStatuses() {
        return [
            'draft' => 'Borrador',
            'sent' => 'Enviada',
            'delivered' => 'Entregada'
        ];
    }
}
?>