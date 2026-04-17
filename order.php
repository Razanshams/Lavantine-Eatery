<?php
session_start();

require_once 'includes/db.php';

//handle form submissions from  menu page (when customer clicks Order Now)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // default action is 'add' if no action specified
    $action = $_POST['action'] ?? 'add';

    //create cart in session if doesnt exist yet
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    //add item to cart — if its already there increase qnty by 1
    if ($action === 'add') {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]++;
        } else {
            //if item not in cart yet add it with qnty 1
            $_SESSION['cart'][$itemId] = 1;
        }
    } 
    //incr qnty by 1
    elseif ($action === 'increase') {
        $_SESSION['cart'][$itemId]++;
    } 
    // Dec qnty by 1 andremove item if qnty = 0
    elseif ($action === 'decrease') {
        $_SESSION['cart'][$itemId]--;
        if ($_SESSION['cart'][$itemId] <= 0) {
            unset($_SESSION['cart'][$itemId]);
        }
    }

    //redirect back order page to avoid form resubmision
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

            <!-- loop through each item in the cart -->
            <?php foreach ($_SESSION['cart'] as $itemId => $quantity): ?>
                <?php
                //fetch item details from database using id
                $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
                $stmt->execute([$itemId]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                //calc subttl for item and add to running ttl
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
                ?>

                <!-- each cart item card, data-price is used by js to recalculate totals -->
                <div class="order-item" data-price="<?= $item['price'] ?>">
                    <img src="images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="order-image">
                    <div class="order-item-details">
                        <h3><?= $item['name'] ?></h3>
                        <p>$<?= number_format($item['price'], 2) ?></p>

                        <!--  js handles these without reloading the page -->
                        <div class="quantity-controls">
                            <!-- data-id and data-action are read by cart.js -->
                            <button class="qty-btn" data-id="<?= $itemId ?>" data-action="decrease">-</button>
                            <span class="qty-display"><?= $quantity ?></span>
                            <button class="qty-btn" data-id="<?= $itemId ?>" data-action="increase">+</button>
                        </div>

                        <!--subttl for item updated live by cart.js -->
                        <p>Subtotal: <span class="subtotal">$<?= number_format($subtotal, 2) ?></span></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- grand ttl and checkout button -->
            <div class="order-total">
                <!-- total-display span is updated live by cart.js -->
                <h3>Total: <span class="total-display">$<?= number_format($total, 2) ?></span></h3>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>

        <?php else: ?>
            <!--messge if the cart is empty -->
            <p>Your cart is empty. <a href="menu.php">Browse the menu</a> to add items.</p>
        <?php endif; ?>
    </div>

<script src="js/cart.js"></script>
</body>
</html>