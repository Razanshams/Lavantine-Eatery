<?php
session_start();
require_once 'includes/db.php';

//if cart is empty, redirect back to order page
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0){
    header('Location: order.php');
    exit();
}
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    if (empty($name) || empty($email)){
        $error = 'Please fill in all fields.';
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Please enter a valis email address.';
    }
    else{
        //insert order in order table 
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);
        $orderId = $pdo->lastInsertId();

        //insert each cart item into order_items table
        foreach ($_SESSION['cart'] as $itemId => $quantity){
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?,?,?)");
            $stmt ->execute([$orderId, $itemId, $quantity]);
        }

        //clear cart
        $_SESSION['cart'] = [];
        $success = 'Your order has been places successfully';
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title> Checkout - Lavantine Eatery</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
<?php require_once 'includes/nav.php'; ?>
<div class = "checkout-container">
    <?php if ($success): ?>
        <div class="success">
            <h2> 🎉 Order Placed!</h2>
            <p><?= $success ?></p>
            <a href="menu.php" class="continue-btn">Continue Shopping</a>
        </div>
    <?php else: ?>      
        <h2>Checkout</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <div class="checkout-summary">
            <h3>Order Summary</h3>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $itemId => $quantity):
                $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
                $stmt->execute([$itemId]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
            ?>
                <div class="summary-item">
                    <span><?= $item['name'] ?> x<?= $quantity ?></span>
                    <span>$<?= number_format($subtotal, 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="summary-total">
                <strong>Total: $<?= number_format($total, 2) ?></strong>
            </div>
        </div>

        <div class="checkout-form">
            <h3>Your Details</h3>
            <form method="POST" action="">
                <label>Name</label>
                <input type="text" name="name" required placeholder="Your full name">

                <label>Email</label>
                <input type="email" name="email" required placeholder="your@email.com">

                <button type="submit">Place Order</button>
            </form>
        </div>

    <?php endif; ?>
</div>
</body>
</html>