<?php
require_once __DIR__ . '/core/core.php';

// Support the original Task 10 ?add_cart=N URL as well.
if (isset($_GET['add_cart'])) {
    require __DIR__ . '/actions/add_to_cart_action.php';
    exit;
}

require __DIR__ . '/views/home.php';
