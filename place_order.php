<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

// --- Validate shipping form ---
$fullName = trim($_POST['full_name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$city     = trim($_POST['city'] ?? '');
$payment  = $_POST['payment_method'] ?? 'cod';

$errors = [];

if ($fullName === '') {
    $errors[] = 'Full name is required.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}
if ($address === '') {
    $errors[] = 'Street address is required.';
}
if ($city === '') {
    $errors[] = 'City is required.';
}
if ($payment !== 'cod') {
    $errors[] = 'Invalid payment method.';
}

// --- Make sure we still have items selected for checkout ---
$selectedIds = $_SESSION['checkout_selected'] ?? [];

if (empty($selectedIds)) {
    $_SESSION['error'] = 'Your checkout session expired. Please select items again.';
    header('Location: cart.php');
    exit;
}

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    // checkout_selected is already in session, so checkout.php can reload the same items
    header('Location: checkout.php');
    exit;
}

// --- Fetch selected watches and build order items ---
$placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
$stmt = $pdo->prepare("SELECT * FROM watches WHERE id IN ($placeholders)");
$stmt->execute(array_values($selectedIds));
$rows = $stmt->fetchAll();

if (empty($rows)) {
    $_SESSION['error'] = 'Selected items could not be found.';
    header('Location: cart.php');
    exit;
}

$items = [];
$total = 0;

foreach ($rows as $watch) {
    $quantity = $_SESSION['cart'][$watch['id']] ?? 1;
    $subtotal = $watch['price'] * $quantity;
    $total += $subtotal;

    $items[] = [
        'watch_id' => $watch['id'],
        'title'    => $watch['title'],
        'price'    => $watch['price'],
        'quantity' => $quantity,
    ];
}

// --- Save order + order items in a transaction ---
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (full_name, phone, address, city, payment_method, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$fullName, $phone, $address, $city, $payment, $total]);

    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, watch_id, title, price, quantity) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $itemStmt->execute([$orderId, $item['watch_id'], $item['title'], $item['price'], $item['quantity']]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Something went wrong while placing your order. Please try again.';
    header('Location: cart.php');
    exit;
}

// --- Remove ordered items from the cart ---
foreach ($selectedIds as $watchId) {
    unset($_SESSION['cart'][$watchId]);
}
unset($_SESSION['checkout_selected']);

// --- Redirect to success page ---
header('Location: order_success.php?order_id=' . $orderId);
exit;
