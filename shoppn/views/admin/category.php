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
        $error = 'Invalid category ID.';
    } else {
        $editing = $controller->getCategoryById($id);

        if (!$editing) {
            $error = 'Category not found.';
        }
    }
}

$categories = $controller->getAllCategories();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../layout/header.php';
?>

<h2>Manage categories</h2>
<p>Add categories to your shop or edit an existing category.</p>

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
        <h3><?= $editing ? 'Edit category' : 'Add category' ?></h3>

        <form
            class="catalog-form"
            action="<?= BASE_URL ?>/actions/<?= $editing ? 'update_category_action.php' : 'add_category_action.php' ?>"
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
                    name="cat_id"
                    value="<?= (int) $editing['cat_id'] ?>"
                >
            <?php endif; ?>

            <label for="category-name">Category name</label>
            <input
                id="category-name"
                type="text"
                name="cat_name"
                data-catalog-name
                value="<?= htmlspecialchars($editing['cat_name'] ?? '') ?>"
                minlength="2"
                maxlength="100"
                required
            >

            <p class="field-error" aria-live="polite"></p>

            <button type="submit">
                <?= $editing ? 'Save changes' : 'Add category' ?>
            </button>

            <?php if ($editing): ?>
                <a class="cancel-link" href="<?= BASE_URL ?>/views/admin/category.php">
                    Cancel
                </a>
            <?php endif; ?>
        </form>
    </section>

    <section class="admin-list">
        <h3>All categories</h3>

        <?php if (!$categories): ?>
            <p>No categories added yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['cat_name']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/views/admin/category.php?edit_id=<?= (int) $category['cat_id'] ?>">
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