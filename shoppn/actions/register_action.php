<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/views/register.php');
    exit;
}

$fields = ['name', 'email', 'password', 'country', 'city', 'contact'];

foreach ($fields as $field) {
    if (isset($_POST[$field]) && !is_string($_POST[$field])) {
        $_SESSION['error'] = 'Invalid registration details.';
        header('Location: ' . BASE_URL . '/views/register.php');
        exit;
    }
}
$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$country = trim(strip_tags($_POST['country'] ?? ''));
$city = trim(strip_tags($_POST['city'] ?? ''));
$contact = trim($_POST['contact'] ?? '');

if (
    strlen($name) < 2 || strlen($name) > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50 ||
    strlen($password) < 8 || !preg_match('/\d/', $password) ||
    !in_array($country, ['Ghana', 'Other'], true) ||
    $city === '' || strlen($city) > 30 ||
    !preg_match('/^[0-9+\-\s]{7,15}$/', $contact)
) {
    $_SESSION['error'] = 'Please check your details and try again.';
    header('Location: ' . BASE_URL . '/views/register.php');
    exit;
}

try {
    $controller = new CustomerController();
    $result = $controller->register([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'country' => $country,
        'city' => $city,
        'contact' => $contact
    ]);

    if (!$result['success']) {
        $_SESSION['error'] = $result['error'];
        header('Location: ' . BASE_URL . '/views/register.php');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['customer_id'] = $result['customer_id'];
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    $_SESSION['user_role'] = 2;

    header('Location: ' . BASE_URL . '/views/account/my_account.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Registration could not be completed.';
    header('Location: ' . BASE_URL . '/views/register.php');
    exit;
}