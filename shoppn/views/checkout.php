<?php
require_once __DIR__ . '/../core/cart_helpers.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_login();
$controller = new CartController();
if ($controller->getCartCount(get_ip()) === 0) {
    cart_redirect('Add a product before checking out.');
}
$total = $controller->getCartTotal(get_ip());
require __DIR__ . '/layout/header.php';
?>

<section class="shop-content">
    <h2>Checkout</h2>
    <p>Your subtotal is <strong>GH₵ <?= number_format($total, 2) ?></strong>.</p>
    <p>Online payment will be available soon.</p>
    <a class="button" href="<?= BASE_URL ?>/views/cart.php">Back to Cart</a>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
