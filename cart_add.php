<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watch_id'])) {
    $watchId = (int) $_POST['watch_id'];

    // Verify the watch exists
    $stmt = $pdo->prepare("SELECT id, title FROM watches WHERE id = ?");
    $stmt->execute([$watchId]);
    $watch = $stmt->fetch();

    if ($watch) {
        if (isset($_SESSION['cart'][$watchId])) {
            $_SESSION['cart'][$watchId]++;
        } else {
            $_SESSION['cart'][$watchId] = 1;
        }

        $_SESSION['success'] = e($watch['title']) . ' added to cart.';
    } else {
        $_SESSION['error'] = 'Watch not found.';
    }
}

// Redirect back to the page the user came from (or home)
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $redirect);
exit;
