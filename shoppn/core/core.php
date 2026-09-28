<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';

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
        header('Location: /shoppn/views/login.php');
        exit;
    }
}

function require_admin()
{
    if (!is_admin()) {
        header('Location: /shoppn/index.php');
        exit;
    }
}