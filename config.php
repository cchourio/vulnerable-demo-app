<?php
/**
 * Configuración de la app demo.
 */

define('DB_PATH', __DIR__ . '/data/demo.db');

// Los secretos de integraciones (pagos, sesiones, etc.) no viven en el código:
// se leen del entorno del servidor cuando hagan falta, p. ej. getenv('PAYMENTS_API_KEY').

if (PHP_SAPI !== 'cli') {
    header('Content-Security-Policy: '
        . "default-src 'self'; script-src 'none'; style-src 'self' 'unsafe-inline'; "
        . "img-src 'self' data:; object-src 'none'; base-uri 'none'; "
        . "form-action 'self'; frame-ancestors 'none'");
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: same-origin');
}

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        if (!file_exists(DB_PATH)) {
            http_response_code(503);
            header('Content-Type: text/plain; charset=utf-8');
            exit("Base de datos no inicializada. Ejecuta en la terminal: php init_db.php\n");
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

/** Escapa un valor para insertarlo en HTML (contenido o atributo). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        exit("Acceso denegado.\n");
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    $sent = $_POST['csrf'] ?? '';
    return is_string($sent) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $sent);
}

/** Cadena recibida por GET/POST, o $default si falta o no es texto. */
function input_str(array $src, string $key, string $default = ''): string
{
    $v = $src[$key] ?? $default;
    return is_string($v) ? $v : $default;
}
