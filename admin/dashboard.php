<?php
//start session to check if the admin logged in
session_start();

//connects to database
require_once '../includes/db.php';

//if admin not logged in redirect to login page
if(!isset($_SESSION['admin_logged_in'])){
    header('Location: admin_login.php');
    exit();
}

//empty success and error messages to start
$success = '';
$error = '';

//handle form submissions for admin actions (add, edit, delete, toggle, update status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    //delete menu item from database
    if ($action === 'delete_item') {
        $id = $_POST['item_id'];
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'Item deleted successfully!';
    }

    //toggle menu items availability on or off
    if ($action === 'toggle_item') {
        $id = $_POST['item_id'];
        $stmt = $pdo->prepare("UPDATE menu_items SET available = NOT available WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'Item updated successfully!';
    }

    //update the status of customer order
    if ($action === 'update_status') {
        $id = $_POST['order_id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $success = 'Order status updated!';
    }

    //add a new menu item to database
    if ($action === 'add_item') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = trim($_POST['image']);

        //insert data thru prepare statments
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $image]);
        $success = 'Item added successfully!';
    }

    //edit item in database
    if ($action === 'edit_item') {
        $id = $_POST['item_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = trim($_POST['image']);

        //insert data
        $stmt = $pdo->prepare("UPDATE menu_items SET name=?, description=?, price=?, category=?, image=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $category, $image, $id]);
        $success = 'Item updated successfully!';
    }
}

//fetch all menu items from database
$menuItems = $pdo->query("SELECT * FROM menu_items")->fetchAll(PDO::FETCH_ASSOC);

//fetch all orders newest first
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Lavantine Eatery</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php require_once '../includes/nav.php'; ?>

    <!-- welcome message and logout button -->
    <div class="dashboard-header">
        <h2>Welcome, Admin!</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- success and error messages -->
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Add New Menu Item form -->
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

    <!-- Edit Menu Item form (hidden shown when edit button clicked) -->
    <div class="admin-section" id="edit-form" style="display:none;">
        <h3>Edit Menu Item</h3>
        <form method="POST" action="">
            <input type="hidden" name="action" value="edit_item">

            <input type="hidden" name="item_id" id="edit-id">

            <label>Name</label>
            <input type="text" name="name" id="edit-name" required>

            <label>Description</label>
            <input type="text" name="description" id="edit-description" required>

            <label>Price</label>
            <input type="number" step="0.01" name="price" id="edit-price" required>

            <label>Category</label>
            <select name="category" id="edit-category">
                <option value="Appetizers">Appetizers</option>
                <option value="Entrees">Entrees</option>
                <option value="Desserts">Desserts</option>
                <option value="Drinks">Drinks</option>
            </select>

            <label>Image filename</label>
            <input type="text" name="image" id="edit-image">

            <button type="submit">Save Changes</button>
            <!-- Cancel button hides the edit form without submitting -->
            <button type="button" onclick="document.getElementById('edit-form').style.display='none'">Cancel</button>
        </form>
    </div>

    <!-- Menu Items table -->
    <div class="admin-section">
        <h3>Menu Items</h3>

        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
                <!-- loop through all menu items and display each as a row -->
                <?php foreach ($menuItems as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['category'] ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <!-- Show Yes or No based on available value -->
                    <td><?= $item['available'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <!-- toggle availability form -->
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="toggle_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit">Toggle</button>
                        </form>
                        <!-- delete item form -->
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <!-- edit button stores item data as data attributes  so JavaScript reads these to fill in the edit form -->
                        <button class="edit-btn" 
                            data-id="<?= $item['id'] ?>"
                            data-name="<?= htmlspecialchars($item['name']) ?>"
                            data-description="<?= htmlspecialchars($item['description']) ?>"
                            data-price="<?= $item['price'] ?>"
                            data-category="<?= $item['category'] ?>"
                            data-image="<?= $item['image'] ?>">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Orders table -->
    <div class="admin-section">
        <h3>Orders</h3>
        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <!-- loop through all orders and display each as a row -->
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['customer_name'] ?></td>
                    <td><?= $order['customer_email'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        <!-- dropdown to update order status -->
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

<script src="../js/admin.js"></script>
</body>
</html>