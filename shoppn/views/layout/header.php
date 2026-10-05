<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shoppn</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-row">
            <a class="site-logo" href="<?= BASE_URL ?>/index.php">Shoppn</a>

            <nav aria-label="Main navigation">
                <a href="<?= BASE_URL ?>/index.php">Home</a>

                <?php if (is_admin()): ?>
                    <a href="<?= BASE_URL ?>/views/admin/brand.php">Brands</a>
                    <a href="<?= BASE_URL ?>/views/admin/category.php">Categories</a>
                <?php endif; ?>

                <?php if (is_logged_in()): ?>
                    <a href="<?= BASE_URL ?>/views/account/my_account.php">My Account</a>
                    <a href="<?= BASE_URL ?>/logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/views/register.php">Register</a>
                    <a href="<?= BASE_URL ?>/views/login.php">Login</a>
                <?php endif; ?>
            </nav>

            <form action="<?= BASE_URL ?>/index.php" method="GET" role="search">
                <label class="visually-hidden" for="site-search">Search products</label>
                <input
                    id="site-search"
                    type="search"
                    name="user_query"
                    placeholder="Search products"
                >
                <button type="submit" disabled>Search</button>
            </form>
        </div>
    </header>
    <main>