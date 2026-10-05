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

if (
    !is_string($token) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    $_SESSION['error'] = 'Invalid request. Please try again.';
} elseif (!is_string($name)) {
    $_SESSION['error'] = 'Enter a valid category name.';
} else {
    $name = trim(strip_tags($name));

    if (strlen($name) < 2 || strlen($name) > 100) {
        $_SESSION['error'] = 'Category name must be 2–100 characters.';
    } else {
        try {
            $controller = new ProductController();

            if ($controller->addCategory($name)) {
                $_SESSION['success'] = 'Category added.';
            } else {
                $_SESSION['error'] = 'Category could not be added.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Category could not be added.';
        }
    }
}

header('Location: ' . BASE_URL . '/views/admin/category.php');
exit;