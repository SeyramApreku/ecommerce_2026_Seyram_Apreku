<?php
require_once __DIR__ . '/../core/cart_helpers.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$returnUrl = cart_return_url($_GET['return_to'] ?? '');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    cart_redirect('Use an Add to Cart link to add a product.', false, $returnUrl);
}
$productId = cart_positive_int($_GET['add_cart'] ?? null);
if ($productId === false) {
    cart_redirect('Invalid product ID.', false, $returnUrl);
}
if (!cart_valid_token($_GET['csrf_token'] ?? null)) {
    cart_redirect('Your request expired. Please try again.', false, $returnUrl);
}

try {
    $productController = new ProductController();
    if (!$productController->getProductById($productId)) {
        cart_redirect('Product not found.', false, $returnUrl);
    }
    $controller = new CartController();
    $ip = get_ip();
    if ($controller->isInCart($productId, $ip)) {
        cart_redirect('This item is already in your cart. Go to cart to update quantity.', false, $returnUrl);
    }
    $customerId = is_logged_in() ? (int) $_SESSION['customer_id'] : null;
    if (!$controller->addToCart($productId, 1, $ip, $customerId)) {
        cart_redirect('This item is already in your cart. Go to cart to update quantity.', false, $returnUrl);
    }
    cart_redirect('Product added to your cart.', true, $returnUrl);
} catch (InvalidArgumentException $e) {
    cart_redirect($e->getMessage(), false, $returnUrl);
} catch (Throwable $e) {
    error_log($e->getMessage());
    cart_redirect('The product could not be added. Please try again.', false, $returnUrl);
}
