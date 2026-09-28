<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';
$scriptPath = $_SERVER['SCRIPT_NAME'];
$shopPosition = strpos($scriptPath, '/shoppn/');

define(
    'BASE_URL',
    $shopPosition === false
        ? '/shoppn'
        : substr($scriptPath, 0, $shopPosition) . '/shoppn'
);

function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

function is_admin()
{
    return is_logged_in() && (int) ($_SESSION['user_role'] ?? 0) === 1;
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . '/views/login.php');
        exit;
    }
}

function require_admin()
{
    if (!is_admin()) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}