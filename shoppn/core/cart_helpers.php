<?php
require_once __DIR__ . '/core.php';

function cart_csrf_token()
{
    if (!isset($_SESSION['cart_csrf_token'])) {
        $_SESSION['cart_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['cart_csrf_token'];
}

function cart_valid_token($token)
{
    return is_string($token) && hash_equals(cart_csrf_token(), $token);
}

function cart_positive_int($value, $maximum = PHP_INT_MAX)
{
    if (!is_string($value) && !is_int($value)) {
        return false;
    }
    return filter_var($value, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => $maximum]
    ]);
}

function cart_redirect($message, $success = false, $url = null)
{
    $_SESSION[$success ? 'cart_success' : 'cart_error'] = $message;
    header('Location: ' . ($url ?? BASE_URL . '/views/cart.php'), true, 303);
    exit;
}

// Only allow a return to one of our product pages, never an external URL.
function cart_return_url($candidate)
{
    $fallback = BASE_URL . '/index.php';
    if (!is_string($candidate) || preg_match('/[\x00-\x20\\\\]/', $candidate)) {
        return $fallback;
    }
    $parts = parse_url($candidate);
    if ($parts === false || isset($parts['host']) || isset($parts['scheme'])) {
        return $fallback;
    }
    $path = $parts['path'] ?? '';
    $allowed = [
        BASE_URL . '/index.php',
        BASE_URL . '/views/home.php',
        BASE_URL . '/views/search_results.php',
        BASE_URL . '/views/single_product.php'
    ];
    if (!in_array($path, $allowed, true)) {
        return $fallback;
    }
    parse_str($parts['query'] ?? '', $query);
    $safeQuery = [];
    foreach (['cat', 'brand', 'pro_id'] as $key) {
        $value = cart_positive_int($query[$key] ?? null);
        if ($value !== false) {
            $safeQuery[$key] = $value;
        }
    }
    if (isset($query['user_query']) && is_string($query['user_query'])) {
        $safeQuery['user_query'] = substr($query['user_query'], 0, 100);
    }
    return $path . ($safeQuery ? '?' . http_build_query($safeQuery) : '');
}

function cart_add_url($productId)
{
    return BASE_URL . '/actions/add_to_cart_action.php?' . http_build_query([
        'add_cart' => (int) $productId,
        'csrf_token' => cart_csrf_token(),
        'return_to' => cart_return_url($_SERVER['REQUEST_URI'] ?? '')
    ]);
}
