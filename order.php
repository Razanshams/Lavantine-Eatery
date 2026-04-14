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
<?php require_once 'includes/nav.php'; ?>
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
                <div class="order-item" data-price="<?= $item['price'] ?>">
                    <img src="images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="order-image">
                    <div class="order-item-details">
                        <h3><?= $item['name'] ?></h3>
                        <p>$<?= number_format($item['price'], 2) ?></p>
                        <div class="quantity-controls">
                            <button class="qty-btn" data-id="<?= $itemId ?>" data-action="decrease">-</button>
                            <span class="qty-display"><?= $quantity ?></span>
                            <button class="qty-btn" data-id="<?= $itemId ?>" data-action="increase">+</button>
                        </div>
                        <p>Subtotal: <span class="subtotal">$<?= number_format($subtotal, 2) ?></span></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="order-total">
                <h3>Total: <span class="total-display">$<?= number_format($total, 2) ?></span></h3>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="menu.php">Browse the menu</a> to add items.</p>
        <?php endif; ?>
    </div>
    <script src="js/cart.js"></script>
</body>
</html>