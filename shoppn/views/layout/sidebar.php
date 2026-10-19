<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
$sidebarController = new ProductController();
$categories = $sidebarController->getAllCategories();
$brands = $sidebarController->getAllBrands();
?>

<aside>
    <h3>Categories</h3>
    <?php if (!$categories): ?>
        <p>No categories added yet.</p>
    <?php else: ?>
        <ul class="sidebar-list">
            <?php foreach ($categories as $category): ?>
                <li><a href="<?= BASE_URL ?>/index.php?cat=<?= (int) $category['cat_id'] ?>"><?= htmlspecialchars($category['cat_name'], ENT_QUOTES, 'UTF-8') ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>Brands</h3>
    <?php if (!$brands): ?>
        <p>No brands added yet.</p>
    <?php else: ?>
        <ul class="sidebar-list">
            <?php foreach ($brands as $brand): ?>
                <li><a href="<?= BASE_URL ?>/index.php?brand=<?= (int) $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name'], ENT_QUOTES, 'UTF-8') ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</aside>
