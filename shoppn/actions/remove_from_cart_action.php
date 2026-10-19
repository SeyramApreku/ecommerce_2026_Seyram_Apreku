<?php
require_once __DIR__ . '/../core/cart_helpers.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    cart_redirect('Use the cart form to remove items.');
}
if (!cart_valid_token($_POST['csrf_token'] ?? null)) {
    cart_redirect('Your request expired. Please refresh the cart and try again.');
}
$submitted = $_POST['remove'] ?? [];
if (!is_array($submitted) || !$submitted || count($submitted) > 500) {
    cart_redirect('Select at least one item to remove.');
}
$ids = [];
foreach ($submitted as $value) {
    $id = cart_positive_int($value);
    if ($id === false) {
        cart_redirect('Invalid product ID. No items were removed.');
    }
    $ids[$id] = $id;
}
try {
    $controller = new CartController();
    $controller->removeItems(array_values($ids), get_ip());
    cart_redirect('Selected items removed from your cart.', true);
} catch (InvalidArgumentException $e) {
    cart_redirect($e->getMessage());
} catch (Throwable $e) {
    error_log($e->getMessage());
    cart_redirect('The items could not be removed. Please try again.');
}
