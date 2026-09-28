<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shoppn</title>
</head>
<body>
    <header>
        <h1>Shoppn</h1>
        <nav>
            <a href="<?= BASE_URL ?>/index.php">Home</a>

            <?php if (is_logged_in()): ?>
                <span>Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer') ?></span>
                <a href="<?= BASE_URL ?>/views/account/my_account.php">My Account</a>
                <a href="<?= BASE_URL ?>/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/views/register.php">Register</a>
                <a href="<?= BASE_URL ?>/views/login.php">Login</a>
            <?php endif; ?>
        </nav>
        <form action="<?= BASE_URL ?>/index.php" method="GET" role="search">
            <label for="site-search">Search products</label>
            <input id="site-search" type="search" name="user_query" placeholder="Search products">
            <button type="submit" disabled>Search</button>
        </form>
    </header>
    <main>