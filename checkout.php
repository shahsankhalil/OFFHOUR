<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$pageTitle = 'Checkout | OffHour Watches';

// Get selected watch IDs either from the cart form (POST) or from a previous
// checkout attempt stored in the session (e.g. after a validation error)
if (!empty($_POST['selected'])) {
    $selectedIds = $_POST['selected'];
} else {
    $selectedIds = $_SESSION['checkout_selected'] ?? [];
}

$selectedIds = array_map('intval', $selectedIds);

if (empty($selectedIds)) {
    $_SESSION['error'] = 'Please select at least one watch to order.';
    header('Location: cart.php');
    exit;
}

// Only keep IDs that are actually in the cart
$selectedIds = array_filter($selectedIds, function ($id) {
    return isset($_SESSION['cart'][$id]);
});

if (empty($selectedIds)) {
    $_SESSION['error'] = 'Selected items could not be found in your cart.';
    header('Location: cart.php');
    exit;
}

// Fetch the selected watches
$placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
$stmt = $pdo->prepare("SELECT * FROM watches WHERE id IN ($placeholders)");
$stmt->execute(array_values($selectedIds));
$rows = $stmt->fetchAll();

$items = [];
$total = 0;

foreach ($rows as $watch) {
    $quantity = $_SESSION['cart'][$watch['id']];
    $subtotal = $watch['price'] * $quantity;
    $total += $subtotal;

    $items[] = [
        'watch'    => $watch,
        'quantity' => $quantity,
        'subtotal' => $subtotal,
    ];
}

// Remember which items were selected for checkout (used by place_order.php)
$_SESSION['checkout_selected'] = array_values($selectedIds);

require_once 'includes/header.php';
?>

    <main class="container">
        <div class="section-title">
            <p>FINALIZE YOUR PURCHASE</p>
            <h2>Checkout</h2>
        </div>

        <div class="checkout-grid">

            <!-- Order Summary -->
            <div class="checkout-summary">
                <h3>Order Summary</h3>
                <?php foreach ($items as $item): ?>
                    <div class="summary-row">
                        <div class="cart-item-info">
                            <img src="<?= e($item['watch']['image']) ?>" alt="<?= e($item['watch']['title']) ?>">
                            <div>
                                <div class="brand"><?= e($item['watch']['brand']) ?></div>
                                <div><?= e($item['watch']['title']) ?></div>
                                <div style="color:#71717a; font-size:13px;">Qty: <?= (int) $item['quantity'] ?></div>
                            </div>
                        </div>
                        <span><?= formatPrice($item['subtotal']) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="summary-total">
                    <span>Total</span>
                    <span class="price"><?= formatPrice($total) ?></span>
                </div>
            </div>

            <!-- Shipping & Payment Form -->
            <div class="checkout-form">
                <h3>Shipping Address</h3>

                <?php if (!empty($_SESSION['form_errors'])): ?>
                    <div class="alert alert-error">
                        <ul style="margin:0; padding-left:20px;">
                            <?php foreach ($_SESSION['form_errors'] as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['form_errors']); ?>
                <?php endif; ?>

                <form action="place_order.php" method="POST">

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="03XX-XXXXXXX" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>

                    <h3 style="margin-top:30px;">Payment Method</h3>

                    <div class="payment-option">
                        <label class="payment-label active">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span>
                                <strong>Cash on Delivery</strong>
                                <small>Pay with cash when your order arrives at your doorstep.</small>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn-shop" style="width:100%; margin-top:30px;">Place Order</button>
                </form>
            </div>

        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>
