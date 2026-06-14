<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watch_id'], $_POST['quantity'])) {
    $watchId  = (int) $_POST['watch_id'];
    $quantity = (int) $_POST['quantity'];

    if ($quantity < 1) {
        $quantity = 1;
    }
    if ($quantity > 10) {
        $quantity = 10;
    }

    if (isset($_SESSION['cart'][$watchId])) {
        $_SESSION['cart'][$watchId] = $quantity;
        $_SESSION['success'] = 'Cart updated.';
    }
}

header('Location: cart.php');
exit;
