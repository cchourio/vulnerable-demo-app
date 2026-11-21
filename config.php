<?php
/**
 * Configuración de la app demo.
 * INTENCIONALMENTE INSEGURA — solo fines educativos.
 */

define('DB_PATH', __DIR__ . '/data/demo.db');
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');
define('API_KEY', 'sk_live_fake_51HqJz8kLmNpQrStUvWxYz0123456789');
define('SECRET_TOKEN', 'supersecret_jwt_key_do_not_use');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        if (!file_exists(DB_PATH)) {
            header('Location: init_db.php');
            exit;
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
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