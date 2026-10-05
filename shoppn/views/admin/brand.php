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
        $error = 'Invalid brand ID.';
    } else {
        $editing = $controller->getBrandById($id);

        if (!$editing) {
            $error = 'Brand not found.';
        }
    }
}

$brands = $controller->getAllBrands();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../layout/header.php';
?>

<h2>Manage brands</h2>
<p>Add brands to your shop or edit an existing brand.</p>

<?php if ($error): ?>
    <p role="alert"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success-message" role="status">
        <?= htmlspecialchars($success) ?>
    </p>
<?php endif; ?>

<div class="admin-layout">
    <section>
        <h3><?= $editing ? 'Edit brand' : 'Add brand' ?></h3>

        <form
            class="catalog-form"
            action="<?= BASE_URL ?>/actions/<?= $editing ? 'update_brand_action.php' : 'add_brand_action.php' ?>"
            method="POST"
        >
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
            >

            <?php if ($editing): ?>
                <input
                    type="hidden"
                    name="brand_id"
                    value="<?= (int) $editing['brand_id'] ?>"
                >
            <?php endif; ?>

            <label for="brand-name">Brand name</label>
            <input
                id="brand-name"
                type="text"
                name="brand_name"
                data-catalog-name
                value="<?= htmlspecialchars($editing['brand_name'] ?? '') ?>"
                minlength="2"
                maxlength="100"
                required
            >

            <p class="field-error" aria-live="polite"></p>

            <button type="submit">
                <?= $editing ? 'Save changes' : 'Add brand' ?>
            </button>

            <?php if ($editing): ?>
                <a class="cancel-link" href="<?= BASE_URL ?>/views/admin/brand.php">
                    Cancel
                </a>
            <?php endif; ?>
        </form>
    </section>

    <section class="admin-list">
        <h3>All brands</h3>

        <?php if (!$brands): ?>
            <p>No brands added yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                            <tr>
                                <td><?= htmlspecialchars($brand['brand_name']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/views/admin/brand.php?edit_id=<?= (int) $brand['brand_id'] ?>">
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