<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Cuenta — Norte';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
    try {
        $row = db()->query($sql)->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header('Location: admin.php');
            exit;
        }
        $error = 'Usuario o contraseña incorrectos.';
    } catch (Throwable $e) {
        $error = 'Error SQL: ' . $e->getMessage();
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
            <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="off">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required placeholder="tu usuario">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">

            <button class="btn btn-dark" type="submit" style="width:100%;margin-top:1.15rem">Entrar</button>
        </form>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>