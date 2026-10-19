<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();
$products = [];
$heading = 'Featured products';
$filterError = '';

if (isset($_GET['cat'])) {
    $catId = filter_var($_GET['cat'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($catId === false) {
        $filterError = 'Invalid category.';
    } else {
        $category = $controller->getCategoryById($catId);
        if (!$category) {
            $filterError = 'Category not found.';
        } else {
            $heading = 'Category: ' . $category['cat_name'];
            $products = $controller->getProductsByCategory($catId);
        }
    }
} elseif (isset($_GET['brand'])) {
    $brandId = filter_var($_GET['brand'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($brandId === false) {
        $filterError = 'Invalid brand.';
    } else {
        $brand = $controller->getBrandById($brandId);
        if (!$brand) {
            $filterError = 'Brand not found.';
        } else {
            $heading = 'Brand: ' . $brand['brand_name'];
            $products = $controller->getProductsByBrand($brandId);
        }
    }
} else {
    $products = $controller->getFeaturedProducts();
}

$homeError = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
require __DIR__ . '/layout/header.php';
?>

<?php if ($homeError): ?>
    <p role="alert"><?= htmlspecialchars($homeError, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<section class="welcome-section">
    <h2>Welcome to Shoppn</h2>
    <?php if (is_logged_in()): ?>
        <p>Hello, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer', ENT_QUOTES, 'UTF-8') ?>.</p>
    <?php else: ?>
        <p>Your next favourite find starts here.</p>
    <?php endif; ?>
</section>

<div class="shop-layout">
    <?php require __DIR__ . '/layout/sidebar.php'; ?>
    <section class="shop-content">
        <h3><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h3>
        <?php if ($filterError): ?>
            <p role="alert"><?= htmlspecialchars($filterError, ENT_QUOTES, 'UTF-8') ?></p>
        <?php elseif (!$products): ?>
            <p>No products found.</p>
        <?php else: ?>
            <?php require __DIR__ . '/layout/product_cards.php'; ?>
        <?php endif; ?>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
