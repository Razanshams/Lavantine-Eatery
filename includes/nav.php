<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cartCount += $quantity;
    }
}

// figure out if we are in the admin folder or root
$prefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>

<header>
    <!-- nav bar used for all the pages -->
    <nav>
        <div class="header-title">
            <img src="<?= $prefix ?>images/Flag_of_Lebanon_(tree).png" alt="Lebanese Flag" class="flag">
            <h1>Lavantine Eatery</h1>
        </div>
        <ul>
            <li><a href="<?= $prefix ?>index.php">Home</a></li>
            <li><a href="<?= $prefix ?>menu.php">Menu</a></li>
            <li><a href="<?= $prefix ?>order.php"> Order Cart</a></li>
                <?php if ($cartCount > 0): ?>
                     <!-- show number of items in cart in nav -->
                    <span class="cart-count"><?= $cartCount ?></span>
                <?php endif; ?>
            </a></li>
            <li><a href="<?= $prefix ?>admin/admin_login.php">Admin</a></li>
        </ul>
    </nav>
</header>