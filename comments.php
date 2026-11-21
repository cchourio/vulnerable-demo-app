<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Reseñas — Norte';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_POST['author'] ?? 'Cliente';
    $body = $_POST['body'] ?? '';

    if (trim($body) !== '') {
        $stmt = db()->prepare('INSERT INTO comments (author, body) VALUES (?, ?)');
        $stmt->execute([$author, $body]);
        $ok = true;
    }
}

$comments = db()->query('SELECT * FROM comments ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/header.php';
?>
<main>
    <h1 class="page-title">Reseñas de clientes</h1>
    <p class="page-lead">Lo que cuentan quienes ya compraron en Norte.</p>

    <div style="display:grid;gap:1.25rem;grid-template-columns:1fr;align-items:start">
        <section class="panel">
            <?php if ($ok): ?>
                <p class="alert alert-ok">Gracias. Tu reseña ya está publicada.</p>
            <?php endif; ?>

            <h2 style="margin:0 0 0.75rem;font-family:var(--display);font-size:1.25rem;letter-spacing:-0.02em">Escribe tu reseña</h2>
            <form method="post" action="comments.php">
                <label for="author">Nombre</label>
                <input type="text" id="author" name="author" required placeholder="Tu nombre">

                <label for="body">Experiencia</label>
                <textarea id="body" name="body" required placeholder="¿Qué te pareció el producto o el servicio?"></textarea>

                <button class="btn" type="submit" style="margin-top:1rem">Publicar reseña</button>
            </form>
        </section>

        <section class="panel">
            <h2 style="margin:0 0 0.25rem;font-family:var(--display);font-size:1.25rem;letter-spacing:-0.02em">Comunidad</h2>
            <p class="muted" style="margin-top:0"><?= count($comments) ?> reseñas</p>

            <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <strong><?= $c['author'] ?></strong>
                    <span class="comment-meta"> · <?= htmlspecialchars($c['created_at'] ?? '') ?></span>
                    <div style="margin-top:0.35rem"><?= $c['body'] ?></div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>