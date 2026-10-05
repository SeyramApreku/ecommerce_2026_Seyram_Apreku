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
                <li><?= htmlspecialchars($category['cat_name']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>Brands</h3>

    <?php if (!$brands): ?>
        <p>No brands added yet.</p>
    <?php else: ?>
        <ul class="sidebar-list">
            <?php foreach ($brands as $brand): ?>
                <li><?= htmlspecialchars($brand['brand_name']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</aside>