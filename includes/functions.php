<?php
/**
 * Helper Functions
 * OffHour Watches
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Make sure the cart array always exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // format: [watch_id => quantity]
}

/**
 * Format a number as PKR currency, e.g. "PKR 185,000"
 */
function formatPrice($amount): string
{
    return 'PKR ' . number_format((float) $amount, 0);
}

/**
 * Get the total number of items currently in the cart (sum of quantities)
 */
function getCartCount(): int
{
    return array_sum($_SESSION['cart']);
}

/**
 * Escape output for safe HTML rendering
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
