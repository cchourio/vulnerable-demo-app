<?php
if (!defined('ADMIN_USER')) {
    require_once __DIR__ . '/../config.php';
}
$pageTitle = $pageTitle ?? 'Norte — Tecnología';
$current = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <nav class="nav">
        <a class="brand" href="index.php">
            <span class="brand-mark" aria-hidden="true"></span>
            Norte
        </a>
        <div class="nav-links">
            <a href="index.php" class="<?= $current === 'index.php' ? 'is-active' : '' ?>">Inicio</a>
            <a href="search.php" class="<?= $current === 'search.php' ? 'is-active' : '' ?>">Catálogo</a>
            <a href="comments.php" class="<?= $current === 'comments.php' ? 'is-active' : '' ?>">Reseñas</a>
            <?php if (is_logged_in()): ?>
                <a href="admin.php" class="<?= $current === 'admin.php' ? 'is-active' : '' ?>">Panel</a>
                <a href="logout.php">Salir</a>
            <?php else: ?>
                <a class="nav-cta" href="login.php">Cuenta</a>
            <?php endif; ?>
        </div>
    </nav>
</header>