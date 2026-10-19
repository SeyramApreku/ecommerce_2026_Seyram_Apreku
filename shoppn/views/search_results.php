<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';
$controller = new ProductController();
$rawQuery = $_GET['user_query'] ?? '';
$query = is_string($rawQuery) ? trim($rawQuery) : '';
$queryError = $query === '' ? 'Enter a product name or keyword to search.' : '';
if (strlen($query) > 100) {
    $queryError = 'Search must be 100 characters or less.';
}
$products = $queryError ? [] : $controller->searchProducts($query);
require __DIR__ . '/layout/header.php';
?>

<h2>Search results</h2>
<div class="shop-layout">
    <?php require __DIR__ . '/layout/sidebar.php'; ?>
    <section class="shop-content">
        <?php if ($queryError): ?>
            <p role="alert"><?= htmlspecialchars($queryError, ENT_QUOTES, 'UTF-8') ?></p>
        <?php else: ?>
            <h3>Results for “<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>”</h3>
            <?php if (!$products): ?>
                <p>No products found.</p>
            <?php else: ?>
                <?php require __DIR__ . '/layout/product_cards.php'; ?>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
