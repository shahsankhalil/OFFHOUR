<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$pageTitle = 'Your Cart | OffHour Watches';

$items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $watchIds = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($watchIds), '?'));

    $stmt = $pdo->prepare("SELECT * FROM watches WHERE id IN ($placeholders)");
    $stmt->execute($watchIds);
    $rows = $stmt->fetchAll();

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
}

require_once 'includes/header.php';
?>

    <main class="container">
        <div class="section-title">
            <p>YOUR SHOPPING BAG</p>
            <h2>Cart</h2>
        </div>

        <?php if (count($items) > 0): ?>

            <form action="checkout.php" method="POST">

                <div class="cart-table-wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Watch</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected[]" value="<?= (int) $item['watch']['id'] ?>" class="item-checkbox">
                                    </td>
                                    <td class="cart-item-info">
                                        <img src="<?= e($item['watch']['image']) ?>" alt="<?= e($item['watch']['title']) ?>">
                                        <div>
                                            <div class="brand"><?= e($item['watch']['brand']) ?></div>
                                            <div><?= e($item['watch']['title']) ?></div>
                                        </div>
                                    </td>
                                    <td><?= formatPrice($item['watch']['price']) ?></td>
                                    <td>
                                        <form action="cart_update.php" method="POST" class="qty-form">
                                            <input type="hidden" name="watch_id" value="<?= (int) $item['watch']['id'] ?>">
                                            <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="1" max="10">
                                            <button type="submit" class="btn-load-more btn-small">Update</button>
                                        </form>
                                    </td>
                                    <td><?= formatPrice($item['subtotal']) ?></td>
                                    <td>
                                        <form action="cart_remove.php" method="POST">
                                            <input type="hidden" name="watch_id" value="<?= (int) $item['watch']['id'] ?>">
                                            <button type="submit" class="btn-remove">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <div class="cart-total">
                        <span>Cart Total:</span>
                        <span class="price"><?= formatPrice($total) ?></span>
                    </div>
                    <button type="submit" class="btn-shop">Order Now</button>
                </div>
            </form>

        <?php else: ?>
            <p style="text-align:center; color:#a1a1aa;">Your cart is empty. <a href="index.php" style="color:#fbbf24;">Browse our watches</a> to add something special.</p>
        <?php endif; ?>
    </main>

    <script>
        document.getElementById('select-all')?.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>

<?php require_once 'includes/footer.php'; ?>
