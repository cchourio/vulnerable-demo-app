<?php
/**
 * Inicializa la base de datos de la demo. Solo se ejecuta desde la terminal:
 *
 *   php init_db.php           crea la base si no existe
 *   php init_db.php --force   la borra y la vuelve a crear
 *
 * Genera contraseñas aleatorias para las cuentas demo, las muestra una única vez
 * y guarda solo su hash.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/config.php';

$force = in_array('--force', $argv ?? [], true);

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0750, true);
}

if (file_exists(DB_PATH)) {
    if (!$force) {
        fwrite(STDERR, "La base de datos ya existe. Usa --force para borrarla y recrearla.\n");
        exit(1);
    }
    unlink(DB_PATH);
}

$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        email TEXT,
        role TEXT NOT NULL DEFAULT "user"
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

    CREATE TABLE login_attempts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT NOT NULL,
        attempted_at INTEGER NOT NULL
    );
    CREATE INDEX idx_login_attempts_ip ON login_attempts (ip, attempted_at);
');

$users = [
    ['admin', 'admin@norte.store', 'admin'],
    ['maria', 'maria@norte.store', 'user'],
    ['pedro', 'pedro@norte.store', 'user'],
];

$stmt = $pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)');
$generated = [];
foreach ($users as [$username, $email, $role]) {
    $plain = bin2hex(random_bytes(9));
    $stmt->execute([$username, password_hash($plain, PASSWORD_DEFAULT), $email, $role]);
    $generated[] = [$username, $role, $plain];
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

echo "Base de datos creada en " . DB_PATH . "\n";
echo "Contraseñas generadas (se muestran solo esta vez):\n";
foreach ($generated as [$username, $role, $plain]) {
    printf("  %-6s (%s): %s\n", $username, $role, $plain);
}
