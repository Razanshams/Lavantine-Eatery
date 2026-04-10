<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $action = $_POST['action'] ?? 'add';

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === 'add') {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]++;
        } else {
            $_SESSION['cart'][$itemId] = 1;
        }
    } elseif ($action === 'increase') {
        $_SESSION['cart'][$itemId]++;
    } elseif ($action === 'decrease') {
        $_SESSION['cart'][$itemId]--;
        if ($_SESSION['cart'][$itemId] <= 0) {
            unset($_SESSION['cart'][$itemId]);
        }
    }

    header('Location: order.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Order - Lavantine Eatery</title>
    <link rel="stylesheet" href="css/order.css">
</head>
<body>
    <header>
        <nav>
            <div class="header-title">
                <img src="images/Flag_of_Lebanon_(tree).png" alt="Lebanese Flag" class="flag">
                <h1>Lavantine Eatery</h1>
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="order.php">Order</a></li>
                <li><a href="Verification.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="order-container">
        <h2>Your Order Cart</h2>
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION['cart'] as $itemId => $quantity): ?>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
                $stmt->execute([$itemId]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
                ?>
                <div class="order-item">
                    <img src="images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="order-image">
                    <div class="order-item-details">
                        <h3><?= $item['name'] ?></h3>
                        <p>$<?= number_format($item['price'], 2) ?></p>
                        <div class="quantity-controls">
                            <form method="POST">
                                <input type="hidden" name="item_id" value="<?= $itemId ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit">-</button>
                            </form>
                            <span><?= $quantity ?></span>
                            <form method="POST">
                                <input type="hidden" name="item_id" value="<?= $itemId ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit">+</button>
                            </form>
                        </div>
                        <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="order-total">
                <h3>Total: $<?= number_format($total, 2) ?></h3>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="menu.php">Browse the menu</a> to add items.</p>
        <?php endif; ?>
    </div>
</body>
</html>