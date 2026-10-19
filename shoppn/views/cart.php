<?php
require_once __DIR__ . '/../core/cart_helpers.php';
require_once __DIR__ . '/../controllers/CartController.php';
$controller = new CartController();
$items = $controller->getCartItems(get_ip());
$total = $controller->getCartTotal(get_ip());
require __DIR__ . '/layout/header.php';
?>

<h2>Your shopping cart</h2>
<?php if (!$items): ?>
    <p>Your cart is empty.</p>
    <a class="button" href="<?= BASE_URL ?>/index.php">Continue Shopping</a>
<?php else: ?>
    <p>Select items to remove, or change quantities and click Update Quantity.</p>
    <p id="cart-validation-error" role="alert" hidden></p>

    <form id="cart-remove-form" class="cart-operation-form" action="<?= BASE_URL ?>/actions/remove_from_cart_action.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(cart_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    </form>
    <form id="cart-update-form" class="cart-operation-form" action="<?= BASE_URL ?>/actions/update_qty_action.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(cart_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    </form>

    <div class="cart-table-wrapper">
        <table class="cart-table">
            <caption class="visually-hidden">Products in your shopping cart</caption>
            <thead>
                <tr>
                    <th scope="col">Remove</th>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit price</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php
                    $id = (int) $item['p_id'];
                    $filename = basename((string) ($item['product_image'] ?? ''));
                    $hasImage = $filename !== '' && is_file(__DIR__ . '/../images/products/' . $filename);
                    $lineTotal = round((float) $item['product_price'] * 100) * (int) $item['qty'] / 100;
                    ?>
                    <tr>
                        <td>
                            <label class="visually-hidden" for="remove-<?= $id ?>">Remove <?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></label>
                            <input id="remove-<?= $id ?>" type="checkbox" name="remove[]" value="<?= $id ?>" form="cart-remove-form">
                        </td>
                        <td>
                            <div class="cart-product">
                                <?php if ($hasImage): ?>
                                    <img class="product-thumbnail" src="<?= BASE_URL ?>/images/products/<?= rawurlencode($filename) ?>" alt="">
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/views/single_product.php?pro_id=<?= $id ?>"><?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></a>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="update[]" value="<?= $id ?>" form="cart-update-form">
                            <label class="visually-hidden" for="qty-<?= $id ?>">Quantity for <?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></label>
                            <input id="qty-<?= $id ?>" class="cart-quantity" type="number" name="qty[]" min="1" max="999" step="1" required value="<?= (int) $item['qty'] ?>" form="cart-update-form">
                        </td>
                        <td>GH₵ <?= number_format((float) $item['product_price'], 2) ?></td>
                        <td>GH₵ <?= number_format($lineTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="4">Subtotal</th>
                    <td><strong>GH₵ <?= number_format($total, 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="cart-buttons">
        <button type="submit" form="cart-remove-form">Remove from Cart</button>
        <button type="submit" form="cart-update-form">Update Quantity</button>
        <a class="button button-secondary" href="<?= BASE_URL ?>/index.php">Continue Shopping</a>
        <a class="button" href="<?= BASE_URL ?>/views/checkout.php">Checkout</a>
    </div>
    <script src="<?= BASE_URL ?>/js/cart.js" defer></script>
<?php endif; ?>

<?php require __DIR__ . '/layout/footer.php'; ?>
