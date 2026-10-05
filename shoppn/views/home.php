<?php require __DIR__ . '/layout/header.php'; ?>
<?php
$homeError = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<?php if ($homeError): ?>
    <p role="alert"><?= htmlspecialchars($homeError) ?></p>
<?php endif; ?>

<section class="welcome-section">
    <h2>Welcome to Shoppn</h2>

    <?php if (is_logged_in()): ?>
        <p>Hello, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer') ?>.</p>
    <?php else: ?>
        <p>Your next favourite find starts here.</p>
    <?php endif; ?>
</section>

<div class="shop-layout">
    <?php require __DIR__ . '/layout/sidebar.php'; ?>

    <section class="shop-content">
        <h3>Explore the shop</h3>
        <p>Our collection is coming soon. Check back for new arrivals.</p>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>