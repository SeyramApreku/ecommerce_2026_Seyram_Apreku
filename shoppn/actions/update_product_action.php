<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/product_upload.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/admin/product.php');
    exit;
}

$newImageFilename = null;
$id = false;

try {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        throw new RuntimeException('Invalid request. Please try again.');
    }

    $fields = [
        'product_id',
        'product_cat',
        'product_brand',
        'product_title',
        'product_price',
        'product_desc',
        'product_keywords'
    ];

    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || !is_string($_POST[$field])) {
            throw new RuntimeException('Invalid product details.');
        }
    }

    $id = filter_var(
        $_POST['product_id'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $cat = filter_var(
        $_POST['product_cat'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $brand = filter_var(
        $_POST['product_brand'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $title = trim(strip_tags($_POST['product_title']));
    $price = trim($_POST['product_price']);
    $desc = trim(strip_tags($_POST['product_desc']));
    $keywords = trim(strip_tags($_POST['product_keywords']));

    if (!$id || !$cat || !$brand) {
        throw new RuntimeException('Invalid product, category, or brand ID.');
    }

    if (strlen($title) < 2 || strlen($title) > 200) {
        throw new RuntimeException('Title must be 2–200 characters.');
    }

    if (
        !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $price) ||
        (float) $price <= 0
    ) {
        throw new RuntimeException(
            'Enter a positive price with up to two decimal places.'
        );
    }

    if (strlen($desc) > 500 || strlen($keywords) > 100) {
        throw new RuntimeException(
            'Description must not exceed 500 characters and keywords 100.'
        );
    }

    $controller = new ProductController();
    $product = $controller->getProductById($id);

    if (!$product) {
        throw new RuntimeException('Product not found.');
    }

    if (
        !$controller->getCategoryById($cat) ||
        !$controller->getBrandById($brand)
    ) {
        throw new RuntimeException('The selected category or brand does not exist.');
    }

    $newImageFilename = upload_product_image(
        $_FILES['product_image'] ?? null,
        false
    );

    $imageFilename = $newImageFilename ?? $product['product_image'];

    if (!$controller->updateProduct(
        $id,
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $imageFilename,
        $keywords
    )) {
        throw new RuntimeException('Product could not be updated.');
    }

    $_SESSION['success'] = 'Product updated.';
} catch (PDOException $e) {
    if ($newImageFilename !== null) {
        @unlink(__DIR__ . '/../images/products/' . $newImageFilename);
    }

    error_log($e->getMessage());
    $_SESSION['error'] = 'Product could not be updated.';
} catch (RuntimeException $e) {
    if ($newImageFilename !== null) {
        @unlink(__DIR__ . '/../images/products/' . $newImageFilename);
    }

    $_SESSION['error'] = $e->getMessage();
}

$destination = BASE_URL . '/views/admin/product.php';

if (isset($_SESSION['error']) && $id) {
    $destination .= '?edit_id=' . $id;
}

header('Location: ' . $destination);
exit;