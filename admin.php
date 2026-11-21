<?php
require_once __DIR__ . '/config.php';
require_login();

$pageTitle = 'Panel — Norte';
$users = db()->query('SELECT id, username, email, role, password FROM users')->fetchAll(PDO::FETCH_ASSOC);
$productCount = (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$reviewCount = (int) db()->query('SELECT COUNT(*) FROM comments')->fetchColumn();

require __DIR__ . '/includes/header.php';
?>
<main>
    <div class="section-head">
        <div>
            <h1 class="page-title">Panel</h1>
            <p class="page-lead" style="margin:0">
                Hola, <?= htmlspecialchars($_SESSION['user']) ?> ·
                <?= htmlspecialchars($_SESSION['role'] ?? 'user') ?>
            </p>
        </div>
        <a class="btn btn-ghost" href="logout.php">Cerrar sesión</a>
    </div>

    <div class="stats">
        <div class="stat">
            <strong><?= count($users) ?></strong>
            <span>Usuarios</span>
        </div>
        <div class="stat">
            <strong><?= $productCount ?></strong>
            <span>Productos</span>
        </div>
        <div class="stat">
            <strong><?= $reviewCount ?></strong>
            <span>Reseñas</span>
        </div>
    </div>

    <section class="panel" style="margin-bottom:1.25rem">
        <h2 style="margin:0 0 0.75rem;font-family:var(--display);font-size:1.25rem">Integraciones</h2>
        <p class="muted">Claves de la tienda (configuración actual).</p>
        <ul class="muted">
            <li>API payments: <span class="mono"><?= htmlspecialchars(API_KEY) ?></span></li>
            <li>Session secret: <span class="mono"><?= htmlspecialchars(SECRET_TOKEN) ?></span></li>
            <li>Admin fallback: <span class="mono"><?= htmlspecialchars(ADMIN_PASS) ?></span></li>
        </ul>
    </section>

    <section class="panel">
        <h2 style="margin:0 0 0.75rem;font-family:var(--display);font-size:1.25rem">Usuarios</h2>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td><span class="mono"><?= htmlspecialchars($u['password']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>