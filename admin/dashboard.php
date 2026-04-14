<?php
session_start();
require_once '../includes/db.php';
if(!isset($_SESSION['admin_logged_in'])){
    header('Location: admin_login.php');
    exit();
}
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'delete_item') {
        $id = $_POST['item_id'];
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'Item deleted successfully!';
    }
    if ($action === 'toggle_item') {
        $id = $_POST['item_id'];
        $stmt = $pdo->prepare("UPDATE menu_items SET available = NOT available WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'Item updated successfully!';
    }
    if ($action === 'update_status') {
        $id = $_POST['order_id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $success = 'Order status updated!';
    }
    if ($action === 'add_item') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = trim($_POST['image']);

        $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $image]);
        $success = 'Item added successfully!';
    }
}
$menuItems = $pdo->query("SELECT * FROM menu_items")->fetchAll(PDO::FETCH_ASSOC);
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Lavantine Eatery</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <nav>
            <div class="header-title">
                <img src="../images/Flag_of_Lebanon_(tree).png" alt="Lebanese Flag" class="flag">
                <h1>Lavantine Eatery</h1>
            </div>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../menu.php">Menu</a></li>
                <li><a href="../order.php">Order</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    <div class="dashboard-container">
        <h2>Welcome, Admin!</h2>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <div class="admin-section">
            <h3>Add New Menu Item</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_item">
                
                <label>Name</label>
                <input type="text" name="name" required>

                <label>Description</label>
                <input type="text" name="description" required>

                <label>Price</label>
                <input type="number" step="0.01" name="price" required>

                <label>Category</label>
                <select name="category">
                    <option value="Appetizers">Appetizers</option>
                    <option value="Entrees">Entrees</option>
                    <option value="Desserts">Desserts</option>
                    <option value="Drinks">Drinks</option>
                </select>

                <label>Image filename</label>
                <input type="text" name="image" placeholder="example: hummus.webp">

                <button type="submit">Add Item</button>
            </form>
        </div>
        <div class="admin-section">
            <h3>Menu Items</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($menuItems as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['category'] ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <td><?= $item['available'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="toggle_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit">Toggle</button>
                        </form>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="admin-section">
            <h3>Orders</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['customer_name'] ?></td>
                    <td><?= $order['customer_email'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status">
                                <option value="pending">Pending</option>
                                <option value="preparing">Preparing</option>
                                <option value="ready">Ready</option>
                                <option value="completed">Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>