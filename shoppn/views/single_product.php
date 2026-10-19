<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';
$id = filter_var($_GET['pro_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false || $id === null) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$controller = new ProductController();
$product = $controller->getProductById($id);
if (!$product) {
    http_response_code(404);
}
$filename = $product ? basename((string) ($product['product_image'] ?? '')) : '';
$hasImage = $filename !== '' && is_file(__DIR__ . '/../images/products/' . $filename);
require __DIR__ . '/layout/header.php';
?>

<div class="shop-layout">
    <?php require __DIR__ . '/layout/sidebar.php'; ?>
    <section class="shop-content">
        <?php if (!$product): ?>
            <h2>Product not found</h2>
            <p><a href="<?= BASE_URL ?>/index.php">Back to products</a></p>
        <?php else: ?>
            <div class="product-detail">
                <?php if ($hasImage): ?>
                    <img src="<?= BASE_URL ?>/images/products/<?= rawurlencode($filename) ?>" alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>">
                <?php else: ?>
                    <div class="product-image-placeholder">Image unavailable</div>
                <?php endif; ?>
                <div>
                    <h2><?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p><strong>Price:</strong> GH₵ <?= number_format((float) $product['product_price'], 2) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($product['cat_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php if (!empty($product['product_desc'])): ?>
                        <p><?= nl2br(htmlspecialchars($product['product_desc'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($product['product_keywords'])): ?>
                        <p><strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <a class="button" href="<?= htmlspecialchars(cart_add_url($product['product_id']), ENT_QUOTES, 'UTF-8') ?>">Add to Cart</a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
