<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Reseñas — Norte';
$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim(input_str($_POST, 'author', 'Cliente'));
    $body = trim(input_str($_POST, 'body'));

    if (!csrf_valid()) {
        $error = 'La sesión del formulario expiró. Vuelve a intentarlo.';
    } elseif ($body === '' || $author === '') {
        $error = 'Escribe tu nombre y tu reseña.';
    } elseif (mb_strlen($author) > 80 || mb_strlen($body) > 2000) {
        $error = 'El nombre admite 80 caracteres y la reseña 2000.';
    } else {
        $stmt = db()->prepare('INSERT INTO comments (author, body) VALUES (?, ?)');
        $stmt->execute([$author, $body]);
        $ok = true;
    }
}

$comments = db()->query('SELECT author, body, created_at FROM comments ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/header.php';
?>
<main>
    <h1 class="page-title">Reseñas de clientes</h1>
    <p class="page-lead">Lo que cuentan quienes ya compraron en Norte.</p>

    <div style="display:grid;gap:1.25rem;grid-template-columns:1fr;align-items:start">
        <section class="panel">
            <?php if ($ok): ?>
                <p class="alert alert-ok">Gracias. Tu reseña ya está publicada.</p>
            <?php elseif ($error): ?>
                <p class="alert alert-error"><?= e($error) ?></p>
            <?php endif; ?>

            <h2 style="margin:0 0 0.75rem;font-family:var(--display);font-size:1.25rem;letter-spacing:-0.02em">Escribe tu reseña</h2>
            <form method="post" action="comments.php">
                <?= csrf_field() ?>
                <label for="author">Nombre</label>
                <input type="text" id="author" name="author" required maxlength="80" placeholder="Tu nombre">

                <label for="body">Experiencia</label>
                <textarea id="body" name="body" required maxlength="2000" placeholder="¿Qué te pareció el producto o el servicio?"></textarea>

                <button class="btn" type="submit" style="margin-top:1rem">Publicar reseña</button>
            </form>
        </section>

        <section class="panel">
            <h2 style="margin:0 0 0.25rem;font-family:var(--display);font-size:1.25rem;letter-spacing:-0.02em">Comunidad</h2>
            <p class="muted" style="margin-top:0"><?= count($comments) ?> reseñas</p>

            <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <strong><?= e($c['author']) ?></strong>
                    <span class="comment-meta"> · <?= e($c['created_at'] ?? '') ?></span>
                    <div style="margin-top:0.35rem"><?= nl2br(e($c['body'])) ?></div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
