<?php
require_once __DIR__ . '/../core/core.php';
require __DIR__ . '/layout/header.php';

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<h2>Create an account</h2>

<?php if ($error): ?>
    <p role="alert"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form id="register-form" action="<?= BASE_URL ?>/actions/register_action.php" method="POST">
    <label>Full name
        <input type="text" name="name" required maxlength="100">
    </label><br>

    <label>Email
        <input type="email" name="email" required maxlength="50">
    </label><br>

    <label>Password
        <input
            type="password"
            name="password"
            required
            minlength="8"
            maxlength="72"
            autocomplete="new-password"
        >
        <small>
            Use uppercase and lowercase letters, a number, and a special character.
            Minimum 8 characters; no spaces.
        </small>
    </label><br>

    <label>Country
        <select name="country" required>
            <option value="">Select a country</option>
            <option value="Ghana">Ghana</option>
            <option value="Other">Other</option>
        </select>
    </label><br>

    <label>City
        <input type="text" name="city" required maxlength="30">
    </label><br>

    <label>Contact number
        <input type="tel" name="contact" required maxlength="15">
    </label><br>

    <button type="submit">Register</button>
</form>

<script src="<?= BASE_URL ?>/js/validate.js"></script>

<?php require __DIR__ . '/layout/footer.php'; ?>