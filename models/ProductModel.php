<?php

require_once __DIR__ . '/Database.php';

/**
 * Modelo para la gestión de productos.
 * Maneja todas las operaciones CRUD y validaciones para productos.
 */
class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crea un nuevo producto.
     *
     * @param array $data Datos del producto
     * @return int|false ID del producto creado o false en caso de error
     */
    public function create($data) {
        try {
            // Validar que el SKU sea único para este usuario
            if ($this->skuExists($data['sku'], $data['user_id'])) {
                throw new Exception("El SKU '{$data['sku']}' ya existe para este usuario");
            }

            $sql = "INSERT INTO products (user_id, sku, name, description, price, cost, stock, min_stock, category, status) 
                    VALUES (:user_id, :sku, :name, :description, :price, :cost, :stock, :min_stock, :category, :status)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':sku' => $data['sku'],
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null,
                ':price' => $data['price'] ?? 0.00,
                ':cost' => $data['cost'] ?? 0.00,
                ':stock' => $data['stock'] ?? 0,
                ':min_stock' => $data['min_stock'] ?? 0,
                ':category' => $data['category'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los productos de un usuario.
     *
     * @param int $userId ID del usuario
     * @param array $filters Filtros opcionales (search, category, status)
     * @return array Lista de productos
     */
    public function getByUser($userId, $filters = []) {
        try {
            $sql = "SELECT * FROM products WHERE user_id = :user_id AND deleted_at IS NULL";
            $params = [':user_id' => $userId];

            // Filtro de búsqueda
            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR sku LIKE :search OR description LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }

            // Filtro por categoría
            if (!empty($filters['category'])) {
                $sql .= " AND category = :category";
                $params[':category'] = $filters['category'];
            }

            // Filtro por estado
            if (!empty($filters['status'])) {
                $sql .= " AND status = :status";
                $params[':status'] = $filters['status'];
            }

            $sql .= " ORDER BY name";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting products: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un producto por su ID.
     *
     * @param int $id ID del producto
     * @param int $userId ID del usuario (para verificar permisos)
     * @return array|null Datos del producto o null si no existe
     */
    public function getById($id, $userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM products WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            error_log("Error getting product by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un producto existente.
     *
     * @param int $id ID del producto
     * @param array $data Nuevos datos del producto
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data, $userId) {
        try {
            // Verificar que el producto pertenece al usuario
            $product = $this->getById($id, $userId);
            if (!$product) {
                return false;
            }

            // Validar SKU único si cambió
            if ($data['sku'] !== $product['sku'] && $this->skuExists($data['sku'], $userId, $id)) {
                throw new Exception("El SKU '{$data['sku']}' ya existe para este usuario");
            }

            $sql = "UPDATE products SET 
                        sku = :sku, 
                        name = :name, 
                        description = :description, 
                        price = :price, 
                        cost = :cost, 
                        stock = :stock, 
                        min_stock = :min_stock, 
                        category = :category, 
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id AND user_id = :user_id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':sku' => $data['sku'],
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null,
                ':price' => $data['price'] ?? 0.00,
                ':cost' => $data['cost'] ?? 0.00,
                ':stock' => $data['stock'] ?? 0,
                ':min_stock' => $data['min_stock'] ?? 0,
                ':category' => $data['category'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un producto (soft delete).
     *
     * @param int $id ID del producto
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se eliminó correctamente
     */
    public function delete($id, $userId) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE products SET deleted_at = CURRENT_TIMESTAMP 
                 WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un SKU ya existe para un usuario.
     *
     * @param string $sku SKU a verificar
     * @param int $userId ID del usuario
     * @param int|null $excludeId ID del producto a excluir (para actualizaciones)
     * @return bool True si el SKU existe
     */
    public function skuExists($sku, $userId, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM products 
                    WHERE sku = :sku AND user_id = :user_id AND deleted_at IS NULL";
            $params = [':sku' => $sku, ':user_id' => $userId];

            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error checking SKU existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene las categorías disponibles para un usuario.
     *
     * @param int $userId ID del usuario
     * @return array Lista de categorías
     */
    public function getCategories($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT DISTINCT category FROM products 
                 WHERE user_id = :user_id AND category IS NOT NULL AND deleted_at IS NULL 
                 ORDER BY category"
            );
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualiza el stock de un producto.
     *
     * @param int $id ID del producto
     * @param int $newStock Nuevo stock
     * @param int $userId ID del usuario (para verificar permisos)
     * @return bool True si se actualizó correctamente
     */
    public function updateStock($id, $newStock, $userId) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE products SET stock = :stock, updated_at = CURRENT_TIMESTAMP 
                 WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL"
            );
            
            return $stmt->execute([
                ':id' => $id,
                ':stock' => $newStock,
                ':user_id' => $userId
            ]);
        } catch (Exception $e) {
            error_log("Error updating stock: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene productos con stock bajo.
     *
     * @param int $userId ID del usuario
     * @return array Lista de productos con stock bajo
     */
    public function getLowStockProducts($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM products 
                 WHERE user_id = :user_id AND deleted_at IS NULL 
                 AND status = 'active' AND stock <= min_stock 
                 ORDER BY stock ASC"
            );
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting low stock products: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca productos por texto.
     *
     * @param string $search Texto de búsqueda
     * @param int $userId ID del usuario
     * @param int $limit Límite de resultados
     * @return array Lista de productos encontrados
     */
    public function search($search, $userId, $limit = 10) {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, sku, name, price, stock FROM products 
                 WHERE user_id = :user_id AND deleted_at IS NULL 
                 AND status = 'active'
                 AND (name LIKE :search OR sku LIKE :search OR description LIKE :search)
                 ORDER BY name LIMIT :limit"
            );
            
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching products: " . $e->getMessage());
            return [];
        }
    }
}
?>