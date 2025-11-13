<!-- Vista de perfil del usuario -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sitio web 2</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    <div class="container mt-5">
        <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
        <p>Esta es una vista de Admnistración de Usuarios.</p>
    </div>
    <div class="container mt-4">
        <h2>👥 Administración de Usuarios</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                <?= htmlspecialchars($message['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <a class="btn btn-primary mb-3" href="/?controller=UserAdmin&action=create">➕ Crear nuevo usuario</a>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Roles</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td>
                            <?php if ($u['roles']): ?>
                                <?php foreach (explode(', ', $u['roles']) as $role): ?>
                                    <span class="badge bg-secondary me-1"><?= htmlspecialchars($role) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Sin roles</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($u['created_at']))) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="/?controller=UserAdmin&action=edit&id=<?= $u['id'] ?>" 
                                   class="btn btn-outline-primary" title="Editar">
                                    ✏️
                                </a>
                                <a href="/?controller=UserAdmin&action=changePassword&id=<?= $u['id'] ?>" 
                                   class="btn btn-outline-warning" title="Cambiar contraseña">
                                    🔑
                                </a>
                                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                                <a href="/?controller=UserAdmin&action=delete&id=<?= $u['id'] ?>" 
                                   class="btn btn-outline-danger" title="Eliminar">
                                    🗑️
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container mt-5">
        <button type="button" class="btn btn-primary">Regresar</button>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>