<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$pageTitle = 'Order Confirmed | OffHour Watches';

$orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();

require_once 'includes/header.php';
?>

    <main class="container">
        <div class="success-box">
            <i class="fa-solid fa-circle-check" style="font-size:60px; color:#22c55e;"></i>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you, <?= e($order['full_name']) ?>. Your order <strong>#<?= (int) $order['id'] ?></strong> has been received and will be processed shortly.</p>

            <div class="order-details">
                <div class="detail-row">
                    <span>Shipping To</span>
                    <span><?= e($order['full_name']) ?>, <?= e($order['address']) ?>, <?= e($order['city']) ?></span>
                </div>
                <div class="detail-row">
                    <span>Phone</span>
                    <span><?= e($order['phone']) ?></span>
                </div>
                <div class="detail-row">
                    <span>Payment Method</span>
                    <span><?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : e($order['payment_method']) ?></span>
                </div>

                <hr>

                <?php foreach ($orderItems as $item): ?>
                    <div class="detail-row">
                        <span><?= e($item['title']) ?> x<?= (int) $item['quantity'] ?></span>
                        <span><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                    </div>
                <?php endforeach; ?>

                <hr>

                <div class="detail-row total">
                    <span>Total</span>
                    <span class="price"><?= formatPrice($order['total_amount']) ?></span>
                </div>
            </div>

            <a href="index.php" class="btn-shop" style="margin-top:30px;">Continue Shopping</a>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>
