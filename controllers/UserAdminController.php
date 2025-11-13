<?php

class UserAdminController {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /?controller=Auth&action=login');
            exit;
        }

        // Verificar que el usuario tenga permisos de administrador
        if (!$this->hasAdminAccess()) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No tienes permisos para acceder a esta sección'];
            header('Location: /?controller=Dashboard&action=index');
            exit;
        }

        $this->userModel = new UserModel();
    }

    /**
     * Verifica si el usuario actual tiene acceso de administrador
     * 
     * @return bool
     */
    private function hasAdminAccess() {
        $userProfile = (new UserModel())->getUserProfileByUsername($_SESSION['user']['username']);
        
        if (!$userProfile || !$userProfile['roles']) {
            return false;
        }

        $userRoles = explode(', ', $userProfile['roles']);
        return in_array('admin', $userRoles) || in_array('root', $userRoles);
    }

    public function index() {
        $users = $this->userModel->getAllUsersWithRoles();
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);
        require __DIR__ . '/../views/useradmin/index.php';
    }

    public function create() {
        $roles = $this->userModel->getAllRoles();
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $roleIds = $_POST['roles'] ?? [];

            // Validaciones
            if (empty($username)) {
                $errors[] = "El nombre de usuario es obligatorio";
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = "El nombre de usuario ya existe";
            }

            if (empty($password)) {
                $errors[] = "La contraseña es obligatoria";
            } elseif (strlen($password) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres";
            }

            if ($password !== $confirmPassword) {
                $errors[] = "Las contraseñas no coinciden";
            }

            if (empty($roleIds)) {
                $errors[] = "Debe seleccionar al menos un rol";
            }

            if (empty($errors)) {
                if ($this->userModel->createUserWithRoles($username, $password, $roleIds)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Usuario creado exitosamente'];
                    header('Location: /?controller=UserAdmin&action=index');
                    exit;
                } else {
                    $errors[] = "Error al crear el usuario";
                }
            }
        }

        require __DIR__ . '/../views/useradmin/create.php';
    }

    public function edit() {
        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de usuario no válido'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Usuario no encontrado'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        $roles = $this->userModel->getAllRoles();
        $userRoleIds = $user['role_ids'] ? explode(',', $user['role_ids']) : [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $roleIds = $_POST['roles'] ?? [];

            // Validaciones
            if (empty($username)) {
                $errors[] = "El nombre de usuario es obligatorio";
            } elseif ($this->userModel->usernameExists($username, $userId)) {
                $errors[] = "El nombre de usuario ya existe";
            }

            if (empty($roleIds)) {
                $errors[] = "Debe seleccionar al menos un rol";
            }

            if (empty($errors)) {
                if ($this->userModel->updateUser($userId, $username, $roleIds)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Usuario actualizado exitosamente'];
                    header('Location: /?controller=UserAdmin&action=index');
                    exit;
                } else {
                    $errors[] = "Error al actualizar el usuario";
                }
            }
        }

        require __DIR__ . '/../views/useradmin/edit.php';
    }

    public function changePassword() {
        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de usuario no válido'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Usuario no encontrado'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validaciones
            if (empty($newPassword)) {
                $errors[] = "La nueva contraseña es obligatoria";
            } elseif (strlen($newPassword) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres";
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = "Las contraseñas no coinciden";
            }

            if (empty($errors)) {
                if ($this->userModel->changePassword($userId, $newPassword)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Contraseña cambiada exitosamente'];
                    header('Location: /?controller=UserAdmin&action=index');
                    exit;
                } else {
                    $errors[] = "Error al cambiar la contraseña";
                }
            }
        }

        require __DIR__ . '/../views/useradmin/change_password.php';
    }

    public function delete() {
        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de usuario no válido'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        // Evitar que el usuario se elimine a sí mismo
        if ($userId == $_SESSION['user']['id']) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No puedes eliminar tu propia cuenta'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Usuario no encontrado'];
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deleteUser($userId)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Usuario eliminado exitosamente'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Error al eliminar el usuario'];
            }
            header('Location: /?controller=UserAdmin&action=index');
            exit;
        }

        require __DIR__ . '/../views/useradmin/delete.php';
    }
}
