<?php
session_start();

require_once 'includes/db.php';

//if the cart is empty send  customer back to order page
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0){
    header('Location: order.php');
    exit();
}

$success = '';
$error = '';

//handle form submission when customer clicks Place Order
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //grabs and cleans the customers name and email from the form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    //make sure boths fields arent empty
    if (empty($name) || empty($email)){
        $error = 'Please fill in all fields.';
    }
    //make sure the email is valid 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Please enter a valid email address.';
    }
    else{
        //insert new order into the orders table with customer details
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);

        //get ID of order we just created so we can link items to it
        $orderId = $pdo->lastInsertId();

        //loop through every item in the cart and save it to order_items table
        foreach ($_SESSION['cart'] as $itemId => $quantity){
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?,?,?)");
            $stmt->execute([$orderId, $itemId, $quantity]);
        }

        //clear the cart after order placed
        $_SESSION['cart'] = [];
        $success = 'Your order has been placed successfully!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Lavantine Eatery</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>

<?php require_once 'includes/nav.php'; ?>

<div class="checkout-container">

    <?php if ($success): ?>
        <!-- show success message after order placed -->
        <div class="success">
            <h2>🎉 Order Placed!</h2>
            <p><?= $success ?></p>
            <a href="menu.php" class="continue-btn">Continue Shopping</a>
        </div>

    <?php else: ?>

        <h2>Checkout</h2>

        <!--show error message if something went wrong -->
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <!-- Order summary showing all cart items and total -->
        <div class="checkout-summary">
            <h3>Order Summary</h3>
            <?php 
            $total = 0;
            //loop through cart items and fetch details from database
            foreach ($_SESSION['cart'] as $itemId => $quantity):
                $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
                $stmt->execute([$itemId]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                //calc subtotal for item
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
            ?>
                <div class="summary-item">
                    <span><?= $item['name'] ?> x<?= $quantity ?></span>
                    <span>$<?= number_format($subtotal, 2) ?></span>
                </div>
            <?php endforeach; ?>

            <!-- display the grand total -->
            <div class="summary-total">
                <strong>Total: $<?= number_format($total, 2) ?></strong>
            </div>
        </div>

        <!-- form for customer to enter their name and email -->
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