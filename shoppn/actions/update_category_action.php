<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/admin/category.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
$name = $_POST['cat_name'] ?? '';
$rawId = $_POST['cat_id'] ?? '';

$id = is_string($rawId)
    ? filter_var($rawId, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ])
    : false;

if (
    !is_string($token) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    $_SESSION['error'] = 'Invalid request. Please try again.';
} elseif (!$id || !is_string($name)) {
    $_SESSION['error'] = 'Invalid category details.';
} else {
    $name = trim(strip_tags($name));

    if (strlen($name) < 2 || strlen($name) > 100) {
        $_SESSION['error'] = 'Category name must be 2–100 characters.';
    } else {
        try {
            $controller = new ProductController();

            if (!$controller->getCategoryById($id)) {
                $_SESSION['error'] = 'Category not found.';
            } elseif ($controller->updateCategory($id, $name)) {
                $_SESSION['success'] = 'Category updated.';
            } else {
                $_SESSION['error'] = 'Category could not be updated.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Category could not be updated.';
        }
    }
}

header('Location: ' . BASE_URL . '/views/admin/category.php');
exit;