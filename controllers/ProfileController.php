<?php
/**
 * Clase ProfileController
 *
 * Encargada de gestionar el acceso a la sección de perfil del usuario.
 * Aplica control de acceso básico basado en la sesión iniciada.
 */
class ProfileController {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /?controller=Auth&action=login');
            exit;
        }
        $this->userModel = new UserModel();
    }

    /**
     * Acción por defecto: muestra la vista del perfil del usuario autenticado.
     */
    public function index() {
        // Obtener información completa del usuario
        $userProfile = $this->userModel->getUserProfileByUsername($_SESSION['user']['username']);
        
        if (!$userProfile) {
            // Si no se encuentra el usuario, cerrar sesión por seguridad
            session_destroy();
            header('Location: /?controller=Auth&action=login');
            exit;
        }

        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        require_once __DIR__ . '/../views/profile/index.php';
    }

    /**
     * Muestra el formulario para cambiar contraseña
     */
    public function changePassword() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validaciones
            if (empty($currentPassword)) {
                $errors[] = "La contraseña actual es obligatoria";
            }

            if (empty($newPassword)) {
                $errors[] = "La nueva contraseña es obligatoria";
            } elseif (strlen($newPassword) < 6) {
                $errors[] = "La nueva contraseña debe tener al menos 6 caracteres";
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = "Las contraseñas nuevas no coinciden";
            }

            if ($currentPassword === $newPassword) {
                $errors[] = "La nueva contraseña debe ser diferente a la actual";
            }

            if (empty($errors)) {
                if ($this->userModel->changeUserPassword($_SESSION['user']['username'], $currentPassword, $newPassword)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Contraseña cambiada exitosamente'];
                    header('Location: /?controller=Profile&action=index');
                    exit;
                } else {
                    $errors[] = "La contraseña actual es incorrecta o hubo un error al cambiarla";
                }
            }
        }

        // Obtener información del usuario para mostrar en el formulario
        $userProfile = $this->userModel->getUserProfileByUsername($_SESSION['user']['username']);
        
        require_once __DIR__ . '/../views/profile/change_password.php';
    }
}
