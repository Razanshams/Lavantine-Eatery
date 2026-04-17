<?php
session_start();
require_once 'includes/db.php';


$stmt = $pdo->query("SELECT * FROM menu_items WHERE available = 1");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="css/menu.css">
</head>

<body>
<?php require_once 'includes/nav.php'; ?>
<div class="search-bar">
    <input type="text" id="searchInput" placeholder="🔍 Search menu...">
</div>

<div class="menu-content">
    <!-- list of button categories user can click on -->
    <div class="sidebar">
        <h3>Categories</h3>
        <ul>
            <li><button class="filter-btn active" data-category="all">All</button></li>
            <li><button class="filter-btn" data-category="Appetizers">Appetizers</button></li>
            <li><button class="filter-btn" data-category="Entrees">Entrees</button></li>
            <li><button class="filter-btn" data-category="Desserts">Desserts</button></li>
            <li><button class="filter-btn" data-category="Drinks">Drinks</button></li>
        </ul>
    </div>

    <div class="menu-container">
        <!-- menu items from database displayed here -->
        <?php foreach ($items as $item): ?>
            <div class="menu-item" data-category="<?= $item['category'] ?>" data-name="<?= $item['name'] ?>" data-id="<?= $item['id'] ?>">
                <h2><?= $item['name'] ?> - $<?= $item['price'] ?></h2>
                <p><?= $item['description'] ?></p>
                <img src="images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="menu-image">
                <!-- post method for the order cart -->
                <form method="post" action="order.php">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <button type="submit" class="order-btn">Order Now</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- javascript for filtering and searching -->
 <script src="js/menu.js"></script>
</body>
</html>