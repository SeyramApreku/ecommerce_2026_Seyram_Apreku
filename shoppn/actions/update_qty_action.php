<?php
require_once __DIR__ . '/../core/cart_helpers.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    cart_redirect('Use the cart form to update quantities.');
}
if (!cart_valid_token($_POST['csrf_token'] ?? null)) {
    cart_redirect('Your request expired. Please refresh the cart and try again.');
}
$ids = $_POST['update'] ?? [];
$quantities = $_POST['qty'] ?? [];
if (!is_array($ids) || !is_array($quantities) || !$ids
    || count($ids) !== count($quantities) || count($ids) > 500) {
    cart_redirect('Invalid cart details. No quantities were changed.');
}
$ids = array_values($ids);
$quantities = array_values($quantities);
$updates = [];
foreach ($ids as $index => $value) {
    $id = cart_positive_int($value);
    $qty = cart_positive_int($quantities[$index], 999);
    if ($id === false || $qty === false || isset($updates[$id])) {
        cart_redirect('Use valid products and whole-number quantities from 1 to 999. No quantities were changed.');
    }
    $updates[$id] = $qty;
}
try {
    $controller = new CartController();
    $controller->updateQuantities($updates, get_ip());
    cart_redirect('Cart quantities updated.', true);
} catch (InvalidArgumentException $e) {
    cart_redirect($e->getMessage());
} catch (Throwable $e) {
    error_log($e->getMessage());
    cart_redirect('The cart could not be updated. Please try again.');
}
