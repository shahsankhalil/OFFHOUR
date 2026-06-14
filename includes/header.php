<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'OffHour Watches Pakistan | Authentic Swiss Timepieces' ?></title>
    <meta name="description" content="Buy 100% authentic Tissot watches in Pakistan. Official Swiss-made luxury timepieces: PRX, Gentleman, Seastar & more. Free nationwide delivery from Islamabad.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/csscode.css">
</head>
<body>

    <header class="main-header">
        <div class="logo-section">
            <i class="fa-solid fa-watch gold-accent" style="font-size: 30px;"></i>
            <div>
                <span class="logo-text">OFFHOUR</span>
                <span class="logo-subtext">Authentic Swiss Watches</span>
            </div>
        </div>

        <nav class="nav-links">
            <a href="index.php?collection=PRX">TISSOT PRX</a>
            <a href="index.php?collection=Gentleman">GENTLEMAN</a>
            <a href="index.php?collection=Seastar">SEASTAR</a>
            <a href="index.php?collection=all">ALL WATCHES</a>
            <a href="index.php#footer">ABOUT</a>
        </nav>

        <div class="header-icons">
            <!-- <i class="fa-solid fa-magnifying-glass"></i> -->
            <!-- <i class="fa-solid fa-user"></i> -->
            <a href="cart.php" style="color:inherit;">
                <div class="cart-wrapper">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="cart-badge" id="cart-count"><?= getCartCount() ?></span>
                </div>
            </a>
        </div>
    </header>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= e($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= e($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
