<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <?php
        $id = (int) $product['product_id'];
        $filename = basename((string) ($product['product_image'] ?? ''));
        $hasImage = $filename !== '' && is_file(__DIR__ . '/../../images/products/' . $filename);
        ?>
        <article class="product-card">
            <?php if ($hasImage): ?>
                <img src="<?= BASE_URL ?>/images/products/<?= rawurlencode($filename) ?>" alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
            <?php else: ?>
                <div class="product-image-placeholder">Image unavailable</div>
            <?php endif; ?>
            <div class="product-card-body">
                <h4><?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?></h4>
                <p>GH₵ <?= number_format((float) $product['product_price'], 2) ?></p>
                <div class="product-actions">
                    <a class="button" href="<?= BASE_URL ?>/views/single_product.php?pro_id=<?= $id ?>">Details</a>
                    <a class="button" href="<?= htmlspecialchars(cart_add_url($id), ENT_QUOTES, 'UTF-8') ?>">Add to Cart</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>
