<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Catálogo — Norte';
$term = $_GET['term'] ?? '';
$results = [];
$sqlError = '';

if ($term !== '') {
    $sql = "SELECT id, name, description, price FROM products WHERE name LIKE '%$term%' OR description LIKE '%$term%'";
    try {
        $results = db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $results = [];
        $sqlError = 'No pudimos completar la búsqueda. Intenta de nuevo.';
    }
} else {
    try {
        $results = db()->query('SELECT id, name, description, price FROM products ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $results = [];
    }
}

$icons = ['💻', '⌨️', '🖥️', '📷', '🎧', '🖱️'];

require __DIR__ . '/includes/header.php';
?>
<main>
    <h1 class="page-title">Catálogo</h1>
    <p class="page-lead">Explora productos o busca por nombre y descripción.</p>

    <form method="get" action="search.php" class="search-bar">
        <input type="search" id="term" name="term" value="<?= htmlspecialchars($term) ?>" placeholder="Buscar productos…">
        <button class="btn" type="submit">Buscar</button>
    </form>

    <?php if ($sqlError): ?>
        <p class="alert alert-error"><?= htmlspecialchars($sqlError) ?></p>
    <?php endif; ?>

    <?php if ($term !== ''): ?>
        <p class="muted" style="margin-bottom:1rem">
            Resultados para “<?= htmlspecialchars($term) ?>” · <?= count($results) ?> productos
        </p>
    <?php endif; ?>

    <?php if (!$results && !$sqlError): ?>
        <div class="panel">
            <p class="muted" style="margin:0">No encontramos productos con ese criterio.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($results as $i => $row): ?>
                <article class="product">
                    <div class="product-media"><?= $icons[$i % count($icons)] ?></div>
                    <div class="product-body">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <div class="price">$<?= number_format((float)$row['price'], 2) ?></div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>