<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /shoppn/views/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['error'] = 'Enter a valid email and password.';
    header('Location: /shoppn/views/login.php');
    exit;
}

$controller = new CustomerController();
$result = $controller->login($email, $password);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
    header('Location: /shoppn/views/login.php');
    exit;
}

$customer = $result['customer'];
session_regenerate_id(true);

$_SESSION['customer_id'] = (int) $customer['customer_id'];
$_SESSION['customer_name'] = $customer['customer_name'];
$_SESSION['customer_email'] = $customer['customer_email'];
$_SESSION['user_role'] = (int) $customer['user_role'];

header('Location: /shoppn/index.php');
exit;