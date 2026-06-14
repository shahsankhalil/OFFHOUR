<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watch_id'])) {
    $watchId = (int) $_POST['watch_id'];

    if (isset($_SESSION['cart'][$watchId])) {
        unset($_SESSION['cart'][$watchId]);
        $_SESSION['success'] = 'Item removed from cart.';
    }
}

header('Location: cart.php');
exit;
