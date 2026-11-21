<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Norte — Tecnología para tu ritmo';

$q = $_GET['q'] ?? '';
$products = [];
try {
    $products = db()->query('SELECT id, name, description, price FROM products ORDER BY id LIMIT 4')->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $products = [];
}

$icons = ['💻', '⌨️', '🖥️', '📷'];

require __DIR__ . '/includes/header.php';
?>
<main>
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow">Nueva temporada · Envío 24h</span>
            <h1>Equipo que acompaña tu forma de trabajar.</h1>
            <p>
                Portátiles, monitores y accesorios curados. Compra online,
                recoge en tienda o recibe en casa con seguimiento en tiempo real.
            </p>
            <div class="actions">
                <a class="btn" href="search.php">Ver catálogo</a>
                <a class="btn btn-ghost" href="comments.php">Leer reseñas</a>
            </div>
        </div>
        <div class="hero-panel" aria-hidden="true">
            <div class="hero-panel-inner">
                <strong>Serie Aurora 14</strong>
                <span>Ligera, silenciosa y lista para el día completo.</span>
            </div>
        </div>
    </section>

    <div class="section-head">
        <div>
            <h2>Destacados</h2>
            <p>Lo más pedido esta semana</p>
        </div>
        <a href="search.php">Ver todo →</a>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $i => $p): ?>
            <article class="product">
                <div class="product-media"><?= $icons[$i % count($icons)] ?></div>
                <div class="product-body">
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p><?= htmlspecialchars($p['description']) ?></p>
                    <div class="price">$<?= number_format((float)$p['price'], 2) ?></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <section class="panel" style="margin-top:2.25rem">
        <h2 class="page-title" style="font-size:1.55rem">¿Cómo te llamamos en tu próxima visita?</h2>
        <p class="page-lead">Guarda tu nombre para personalizar recomendaciones en la tienda.</p>
        <form method="get" action="index.php" class="search-bar" style="max-width:520px">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tu nombre">
            <button class="btn" type="submit">Guardar</button>
        </form>
        <?php if ($q !== ''): ?>
            <p class="welcome">Hola, <?= $q ?>. Ya preparamos tu espacio Norte.</p>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>