<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM menu_items WHERE available = 1");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="css/verification.css">
</head>
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
<body>
    <div class="banner">
        <h2>Order Cart</h2>
        <p>Experience the authentic flavors of Lebanon</p>
    </div>
</body>
</html>