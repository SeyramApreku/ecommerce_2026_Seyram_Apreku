<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/admin/brand.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
$name = $_POST['brand_name'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    $_SESSION['error'] = 'Invalid request. Please try again.';
} elseif (!is_string($name)) {
    $_SESSION['error'] = 'Enter a valid brand name.';
} else {
    $name = trim(strip_tags($name));

    if (strlen($name) < 2 || strlen($name) > 100) {
        $_SESSION['error'] = 'Brand name must be 2–100 characters.';
    } else {
        try {
            $controller = new ProductController();

            if ($controller->addBrand($name)) {
                $_SESSION['success'] = 'Brand added.';
            } else {
                $_SESSION['error'] = 'Brand could not be added.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Brand could not be added.';
        }
    }
}

header('Location: ' . BASE_URL . '/views/admin/brand.php');
exit;