<?php
require_once __DIR__ . '/../../core/core.php';
require_login();

require __DIR__ . '/../layout/header.php';
?>

<h2>My Account</h2>
<p>Name: <?= htmlspecialchars($_SESSION['customer_name']) ?></p>
<p>Email: <?= htmlspecialchars($_SESSION['customer_email']) ?></p>

<?php require __DIR__ . '/../layout/footer.php'; ?>