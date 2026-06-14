<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$pageTitle = 'OffHour Watches Pakistan | Authentic Swiss Timepieces';

// Get selected collection filter (if any)
$collection = isset($_GET['collection']) ? $_GET['collection'] : 'all';

// Fetch watches (filtered or all)
if ($collection !== 'all' && $collection !== '') {
    $stmt = $pdo->prepare("SELECT * FROM watches WHERE collection_name = ? ORDER BY created_at DESC");
    $stmt->execute([$collection]);
} else {
    $stmt = $pdo->query("SELECT * FROM watches ORDER BY created_at DESC");
}
$watches = $stmt->fetchAll();

// Fetch distinct collections for filter bar
$collections = $pdo->query("SELECT DISTINCT collection_name FROM watches WHERE collection_name IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

require_once 'includes/header.php';
?>

    <section class="hero">
        <div>
            <h1>OFFHOUR WATCHES</h1>
            <p>Authentic Swiss timepieces in Pakistan • Swiss Made since 1853 • Iconic collections: PRX, Gentleman, Seastar • Free delivery nationwide • Authorized retailer</p>
            <a href="#products" class="btn-shop">Shop OffHour Watches Now</a>
        </div>
    </section>

    <main class="container">
        <div class="section-title" id="products">
            <p>DISCOVER AUTHENTIC WATCHES IN PAKISTAN</p>
            <h2>Swiss Precision Meets Pakistani Style</h2>
        </div>

        <!-- Collection Filter -->
        <div class="filter-bar">
            <a href="index.php?collection=all" class="filter-link <?= ($collection === 'all') ? 'active' : '' ?>">All</a>
            <?php foreach ($collections as $col): ?>
                <a href="index.php?collection=<?= e($col) ?>" class="filter-link <?= ($collection === $col) ? 'active' : '' ?>"><?= e($col) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="product-grid">
            <?php if (count($watches) > 0): ?>
                <?php foreach ($watches as $watch): ?>
                    <div class="product-card">
                        <img src="<?= e($watch['image']) ?>" alt="<?= e($watch['title']) ?>">
                        <div class="product-info">
                            <div class="brand"><?= e($watch['brand']) ?></div>
                            <h3 class="product-title"><?= e($watch['title']) ?></h3>
                            <div class="card-footer">
                                <span class="price"><?= formatPrice($watch['price']) ?></span>
                                <form action="cart_add.php" method="POST">
                                    <input type="hidden" name="watch_id" value="<?= (int) $watch['id'] ?>">
                                    <button type="submit" class="btn-add">ADD TO CART</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No watches available right now. Please check back soon.</p>
            <?php endif; ?>
        </div>

        <div class="load-more-container">
            <p class="feature-text">Featured: <?= count($watches) ?> Authentic Models • Nationwide Delivery • 2-Year Warranty</p>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>
