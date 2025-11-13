<?php
/**
 * Clase UserModel
 *
 * Gestiona operaciones relacionadas con usuarios, incluyendo autenticación,
 * creación de cuentas y recuperación de datos, interactuando directamente con la base de datos.
 */
class UserModel {
    private $db;

    /**
     * Constructor
     * 
     * Obtiene la instancia compartida de la conexión a base de datos (PDO).
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Autentica a un usuario mediante verificación de hash y salt almacenados.
     * Utiliza PBKDF2 con SHA-256 y libsodium para una comparación segura.
     *
     * @param string $username Nombre de usuario
     * @param string $password Contraseña en texto plano
     * @return array|false Datos del usuario (sin hash ni salt) si es válido, o false si falla
     */
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password_hash, salt FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            error_log("Usuario no encontrado: $username");
            return false;
        }

        try {
            $storedHash = sodium_hex2bin($user['password_hash']);
            $salt = sodium_hex2bin($user['salt']);

            // Deriva un hash de la contraseña ingresada usando el mismo algoritmo y parámetros
            $inputHash = hash_pbkdf2(
                'sha256',
                $password,
                $salt,
                100000,
                32,
                false
            );
            $inputHashBinary = sodium_hex2bin($inputHash);

            // Comparación segura contra ataques de timing usando sodium_memcmp
            if ($storedHash && $inputHashBinary && 
                sodium_memcmp($storedHash, $inputHashBinary) === 0) {
                
                unset($user['password_hash'], $user['salt']); // Elimina datos sensibles antes de retornar
                return $user;
            }

            error_log("Hash no coincide para usuario: $username");
        } catch (Exception $e) {
            error_log("Error crítico en autenticación: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Obtiene información básica de un usuario por su nombre de usuario.
     *
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT id, username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene información completa de un usuario con sus roles por username
     *
     * @param string $username
     * @return array|false
     */
    public function getUserProfileByUsername($username) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.created_at,
                   GROUP_CONCAT(r.id SEPARATOR ',') as role_ids,
                   GROUP_CONCAT(r.name SEPARATOR ', ') as roles
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            WHERE u.username = ?
            GROUP BY u.id, u.username, u.created_at
        ");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cambia la contraseña del usuario actual
     *
     * @param string $username
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function changeUserPassword($username, $currentPassword, $newPassword) {
        try {
            // Verificar contraseña actual
            if (!$this->authenticate($username, $currentPassword)) {
                return false;
            }

            // Obtener el usuario
            $user = $this->getUserByUsername($username);
            if (!$user) {
                return false;
            }

            // Cambiar contraseña
            return $this->changePassword($user['id'], $newPassword);
        } catch (Exception $e) {
            error_log("Error cambiando contraseña de usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo usuario almacenando la contraseña como hash seguro.
     * 
     * Utiliza PBKDF2 con SHA-256 y un salt generado aleatoriamente, almacenado junto al hash.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function createUser($username, $password) {
        $salt = random_bytes(32); // Salt aleatorio único
        $hash = hash_pbkdf2(
            'sha256',
            $password,
            $salt,
            100000,
            32,
            false
        );

        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash, salt) VALUES (:username, :hash, :salt)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':salt', sodium_bin2hex($salt)); // Almacena el salt como hexadecimal

        return $stmt->execute();
    }

    /**
     * Obtiene todos los usuarios con sus roles asignados
     *
     * @return array
     */
    public function getAllUsersWithRoles() {
        $stmt = $this->db->query("
            SELECT u.id, u.username, u.created_at, 
                   GROUP_CONCAT(r.name SEPARATOR ', ') as roles,
                   GROUP_CONCAT(r.id SEPARATOR ',') as role_ids
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            GROUP BY u.id, u.username, u.created_at 
            ORDER BY u.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un usuario por su ID con sus roles
     *
     * @param int $userId
     * @return array|false
     */
    public function getUserById($userId) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.created_at,
                   GROUP_CONCAT(r.id SEPARATOR ',') as role_ids,
                   GROUP_CONCAT(r.name SEPARATOR ', ') as roles
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            WHERE u.id = ?
            GROUP BY u.id, u.username, u.created_at
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los roles disponibles
     *
     * @return array
     */
    public function getAllRoles() {
        $stmt = $this->db->query("SELECT id, name, description FROM roles ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario con roles asignados
     *
     * @param string $username
     * @param string $password
     * @param array $roleIds Array de IDs de roles
     * @return bool
     */
    public function createUserWithRoles($username, $password, $roleIds = []) {
        try {
            $this->db->beginTransaction();

            // Crear usuario
            $salt = random_bytes(32);
            $hash = hash_pbkdf2('sha256', $password, $salt, 100000, 32, false);

            $stmt = $this->db->prepare("INSERT INTO users (username, password_hash, salt) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, sodium_bin2hex($salt)]);
            
            $userId = $this->db->lastInsertId();

            // Asignar roles
            if (!empty($roleIds)) {
                $this->assignRolesToUser($userId, $roleIds);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error creando usuario con roles: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la información de un usuario
     *
     * @param int $userId
     * @param string $username
     * @param array $roleIds
     * @return bool
     */
    public function updateUser($userId, $username, $roleIds = []) {
        try {
            $this->db->beginTransaction();

            // Actualizar usuario
            $stmt = $this->db->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->execute([$username, $userId]);

            // Remover roles existentes
            $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmt->execute([$userId]);

            // Asignar nuevos roles
            if (!empty($roleIds)) {
                $this->assignRolesToUser($userId, $roleIds);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error actualizando usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cambia la contraseña de un usuario
     *
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function changePassword($userId, $newPassword) {
        try {
            $salt = random_bytes(32);
            $hash = hash_pbkdf2('sha256', $newPassword, $salt, 100000, 32, false);

            $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, salt = ? WHERE id = ?");
            return $stmt->execute([$hash, sodium_bin2hex($salt), $userId]);
        } catch (Exception $e) {
            error_log("Error cambiando contraseña: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un usuario y sus relaciones con roles
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser($userId) {
        try {
            $this->db->beginTransaction();

            // Eliminar relaciones de roles
            $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmt->execute([$userId]);

            // Eliminar usuario
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error eliminando usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un nombre de usuario ya existe
     *
     * @param string $username
     * @param int $excludeUserId ID de usuario a excluir de la verificación
     * @return bool
     */
    public function usernameExists($username, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $params = [$username];

        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Asigna roles a un usuario
     *
     * @param int $userId
     * @param array $roleIds
     * @return void
     */
    private function assignRolesToUser($userId, $roleIds) {
        $stmt = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        foreach ($roleIds as $roleId) {
            $stmt->execute([$userId, $roleId]);
        }
    }
}
