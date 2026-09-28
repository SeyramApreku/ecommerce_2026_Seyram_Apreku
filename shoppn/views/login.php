<?php
require_once __DIR__ . '/../core/core.php';
require __DIR__ . '/layout/header.php';

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<h2>Login</h2>

<?php if ($error): ?>
    <p role="alert"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form id="login-form" action="<?= BASE_URL ?>/actions/login_action.php" method="POST">
    <label>Email
        <input type="email" name="email" required>
    </label><br>

    <label>Password
        <input type="password" name="password" required>
    </label><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="<?= BASE_URL ?>/views/register.php">Register</a></p>

<script src="<?= BASE_URL ?>/js/validate.js"></script>

<?php require __DIR__ . '/layout/footer.php'; ?>