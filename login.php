<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Cuenta — Norte';
$error = '';

const LOGIN_MAX_FAILURES = 5;
const LOGIN_WINDOW_SECONDS = 900;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = input_str($_POST, 'username');
    $password = input_str($_POST, 'password');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    try {
        $pdo = db();
        $since = time() - LOGIN_WINDOW_SECONDS;
        $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at < ?')->execute([$since]);
        $count = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at >= ?');
        $count->execute([$ip, $since]);

        if (!csrf_valid()) {
            $error = 'La sesión del formulario expiró. Vuelve a intentarlo.';
        } elseif ((int) $count->fetchColumn() >= LOGIN_MAX_FAILURES) {
            http_response_code(429);
            $error = 'Demasiados intentos fallidos. Espera unos minutos e inténtalo de nuevo.';
        } else {
            $stmt = $pdo->prepare('SELECT username, password, role FROM users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($password, $row['password'])) {
                $pdo->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
                session_regenerate_id(true);
                $_SESSION['user'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                unset($_SESSION['csrf']);
                header('Location: ' . ($row['role'] === 'admin' ? 'admin.php' : 'index.php'));
                exit;
            }

            $pdo->prepare('INSERT INTO login_attempts (ip, attempted_at) VALUES (?, ?)')->execute([$ip, time()]);
            $error = 'Usuario o contraseña incorrectos.';
        }
    } catch (Throwable $e) {
        error_log('login.php: ' . $e->getMessage());
        $error = 'No pudimos iniciar sesión. Inténtalo de nuevo más tarde.';
    }
}

require __DIR__ . '/includes/header.php';
?>
<main class="auth-shell">
    <div class="panel panel-narrow auth-card">
        <span class="eyebrow">Acceso</span>
        <h1 class="page-title">Inicia sesión</h1>
        <p class="page-lead" style="margin-bottom:0.5rem">Gestiona pedidos, envíos y tu perfil Norte.</p>

        <?php if ($error): ?>
            <p class="alert alert-error"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="off">
            <?= csrf_field() ?>
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required placeholder="tu usuario">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">

            <button class="btn btn-dark" type="submit" style="width:100%;margin-top:1.15rem">Entrar</button>
        </form>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
