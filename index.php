<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM menu_items WHERE available = 1");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="css/index.css">
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
            <li><a href="admin/admin_login.php">Admin</a></li>          </ul>
    </nav>
</header>
<body>
    <div class="banner">
        <div class="slides">
            <img src="images/Chicken_Shawarma.webp" alt="Restaurant Banner" class="slide active">
            <img src="images/Tabbouli.jpeg" alt="Restaurant Banner" class="slide">
            <img src="images/Hummus.webp" alt="Restaurant Banner" class="slide">
        </div>
        <div class="overlay">
            <h2>Welcome to Lavantine Eatery</h2>
            <p>Featured items: Shawarma Platter, Housemade Hummus, and Fresh Tabbouli</p>
            <a href="menu.php" class="order-btn">Order Now</a>
        </div>  
    </div>

    <div class="divider1">
    </div>


    <div class="about">
        <h2>About Us</h2>
        <p>Just two girls from Dearborn who want to share the amazing flavors of Levant with the world. What started out as our hobbies became a passion. After everyone in our lives telling us how amazing our food is, we decided it would be a crime not to share it with the rest of the world. </p>
    </div>

    <div class="divider2">
    </div>

    <div class="contact">
        <h2>Contact Us</h2>
        <p>Have questions or want to place a custom order? Reach out to us!</p>
        <ul>
            <li>Email: yomama@lavantineeatery.com</li>
            <li>Phone: (555) 123-4567</li>
        </ul>
    </div>

    <div class="divider3">
    </div>

    <script src="js/index.js"></script>
</body>
</html>