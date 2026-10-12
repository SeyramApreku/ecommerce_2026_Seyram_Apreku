<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$editing = false;

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);

if (isset($_GET['edit_id'])) {
    $rawId = $_GET['edit_id'];

    $id = is_string($rawId)
        ? filter_var($rawId, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ])
        : false;

    if (!$id) {
        $error = 'Invalid product ID.';
    } else {
        $editing = $controller->getProductById($id);

        if (!$editing) {
            $error = 'Product not found.';
        }
    }
}

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
$products = $controller->getAllProducts();

$canSave = !empty($categories) && !empty($brands);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../layout/header.php';
?>

<h2>Manage products</h2>
<p>Add products to your shop or update an existing product.</p>

<?php if ($error): ?>
    <p role="alert"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success-message" role="status">
        <?= htmlspecialchars($success) ?>
    </p>
<?php endif; ?>

<?php if (!$canSave): ?>
    <p role="alert">
        Add at least one brand and category before adding products.
    </p>
<?php endif; ?>

<div class="product-admin-layout">
    <section>
        <h3><?= $editing ? 'Edit product' : 'Add product' ?></h3>

        <form
            id="product-form"
            action="<?= BASE_URL ?>/actions/<?= $editing ? 'update_product_action.php' : 'add_product_action.php' ?>"
            method="POST"
            enctype="multipart/form-data"
        >
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
            >

            <?php if ($editing): ?>
                <input
                    type="hidden"
                    name="product_id"
                    value="<?= (int) $editing['product_id'] ?>"
                >
            <?php endif; ?>

            <div class="product-field">
                <label for="product-title">Product title</label>
                <input
                    id="product-title"
                    type="text"
                    name="product_title"
                    value="<?= htmlspecialchars($editing['product_title'] ?? '') ?>"
                    minlength="2"
                    maxlength="200"
                    required
                >
            </div>

            <div class="product-field">
                <label for="product-category">Category</label>
                <select
                    id="product-category"
                    name="product_cat"
                    required
                >
                    <option value="">Select a category</option>

                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= (int) $category['cat_id'] ?>"
                            <?= (int) ($editing['product_cat'] ?? 0) === (int) $category['cat_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($category['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="product-field">
                <label for="product-brand">Brand</label>
                <select
                    id="product-brand"
                    name="product_brand"
                    required
                >
                    <option value="">Select a brand</option>

                    <?php foreach ($brands as $brand): ?>
                        <option
                            value="<?= (int) $brand['brand_id'] ?>"
                            <?= (int) ($editing['product_brand'] ?? 0) === (int) $brand['brand_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($brand['brand_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="product-field">
                <label for="product-price">Price in GHS</label>
                <input
                    id="product-price"
                    type="number"
                    name="product_price"
                    min="0.01"
                    max="99999999.99"
                    step="0.01"
                    value="<?= $editing ? htmlspecialchars(number_format((float) $editing['product_price'], 2, '.', '')) : '' ?>"
                    required
                >
            </div>

            <div class="product-field">
                <label for="product-description">Description</label>
                <textarea
                    id="product-description"
                    name="product_desc"
                    rows="4"
                    maxlength="500"
                ><?= htmlspecialchars($editing['product_desc'] ?? '') ?></textarea>
                <small>Maximum 500 characters.</small>
            </div>

            <div class="product-field">
                <label for="product-keywords">Keywords</label>
                <input
                    id="product-keywords"
                    type="text"
                    name="product_keywords"
                    value="<?= htmlspecialchars($editing['product_keywords'] ?? '') ?>"
                    maxlength="100"
                    placeholder="For example: pink, bag, accessories"
                >
                <small>Maximum 100 characters.</small>
            </div>

            <div class="product-field">
                <label for="product-image">
                    <?= $editing ? 'Replace image' : 'Product image' ?>
                </label>

                <?php if ($editing && !empty($editing['product_image'])): ?>
                    <img
                        class="product-image-preview"
                        src="<?= BASE_URL ?>/images/products/<?= rawurlencode($editing['product_image']) ?>"
                        alt="<?= htmlspecialchars($editing['product_title']) ?>"
                    >
                    <small>Leave the file input empty to keep this image.</small>
                <?php endif; ?>

                <input
                    id="product-image"
                    type="file"
                    name="product_image"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                    <?= $editing ? '' : 'required' ?>
                >
                <small>JPG, PNG, GIF, or WebP. Maximum 2 MB.</small>
            </div>

            <p id="product-form-error" class="field-error" aria-live="polite"></p>

            <button type="submit" <?= $canSave ? '' : 'disabled' ?>>
                <?= $editing ? 'Save changes' : 'Add product' ?>
            </button>

            <?php if ($editing): ?>
                <a class="cancel-link" href="<?= BASE_URL ?>/views/admin/product.php">
                    Cancel
                </a>
            <?php endif; ?>
        </form>
    </section>

    <section class="admin-list">
        <h3>All products</h3>

        <?php if (!$products): ?>
            <p>No products added yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['product_image'])): ?>
                                        <img
                                            class="product-thumbnail"
                                            src="<?= BASE_URL ?>/images/products/<?= rawurlencode($product['product_image']) ?>"
                                            alt=""
                                        >
                                    <?php endif; ?>

                                    <?= htmlspecialchars($product['product_title']) ?>
                                </td>
                                <td><?= htmlspecialchars($product['cat_name']) ?></td>
                                <td><?= htmlspecialchars($product['brand_name']) ?></td>
                                <td class="product-price">
                                    GHS <?= number_format((float) $product['product_price'], 2) ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/views/admin/product.php?edit_id=<?= (int) $product['product_id'] ?>">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>

<script src="<?= BASE_URL ?>/js/validate.js"></script>

<?php require __DIR__ . '/../layout/footer.php'; ?>