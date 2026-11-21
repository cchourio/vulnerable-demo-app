<?php
require_once __DIR__ . '/config.php';
require_admin();

$pageTitle = 'Panel — Norte';
$users = db()->query('SELECT id, username, email, role FROM users ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
$productCount = (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$reviewCount = (int) db()->query('SELECT COUNT(*) FROM comments')->fetchColumn();

require __DIR__ . '/includes/header.php';
?>
<main>
    <div class="section-head">
        <div>
            <h1 class="page-title">Panel</h1>
            <p class="page-lead" style="margin:0">
                Hola, <?= e($_SESSION['user']) ?> ·
                <?= e($_SESSION['role']) ?>
            </p>
        </div>
        <form method="post" action="logout.php" style="margin:0">
            <?= csrf_field() ?>
            <button class="btn btn-ghost" type="submit">Cerrar sesión</button>
        </form>
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
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int) $u['id'] ?></td>
                        <td><?= e($u['username']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td><?= e($u['role']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
