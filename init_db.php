<?php
require_once __DIR__ . '/config.php';

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

if (file_exists(DB_PATH)) {
    unlink(DB_PATH);
}

$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        role TEXT DEFAULT "user"
    );

    CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        price REAL
    );

    CREATE TABLE comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        author TEXT NOT NULL,
        body TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    );
');

$users = [
    ['admin', 'admin123', 'admin@norte.store', 'admin'],
    ['maria', 'password', 'maria@norte.store', 'user'],
    ['pedro', '123456', 'pedro@norte.store', 'user'],
];

$stmt = $pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)');
foreach ($users as $u) {
    $stmt->execute($u);
}

$products = [
    ['Aurora 14 Laptop', 'Ultrabook 14" con batería de todo el día y teclado silencioso.', 899.99],
    ['Teclado Norte Key', 'Mecánico low-profile, layout ES, switches táctiles.', 79.50],
    ['Monitor Vale 27', 'IPS 144Hz, bordes finos, ideal para edición y gaming.', 249.00],
    ['Webcam Lumen HD', '1080p con micrófono dual y corrección de luz.', 45.00],
];

$stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (?, ?, ?)');
foreach ($products as $p) {
    $stmt->execute($p);
}

$comments = [
    ['Ana R.', 'La Aurora 14 llegó en un día. Batería real de 10 horas.'],
    ['Luis M.', 'Atención rápida y el monitor Vale se ve excelente.'],
];

$stmt = $pdo->prepare('INSERT INTO comments (author, body) VALUES (?, ?)');
foreach ($comments as $c) {
    $stmt->execute($c);
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración — Norte</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <nav class="nav">
        <a class="brand" href="index.php"><span class="brand-mark"></span> Norte</a>
    </nav>
</header>
<main class="auth-shell">
    <div class="panel panel-narrow">
        <h1 class="page-title">Listo</h1>
        <p class="page-lead">Catálogo y usuarios de ejemplo cargados correctamente.</p>
        <a class="btn" href="index.php">Ir a la tienda</a>
    </div>
</main>
</body>
</html>