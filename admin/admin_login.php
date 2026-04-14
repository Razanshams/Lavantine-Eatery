<?php 
session_start(); 
require_once '../includes/db.php';
if(isset($_SESSION['admin_logged_in'])){
    header('Location: dashboard.php');
    exit();
}
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $password = $_POST ['password'];
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt -> execute([$username]); 
    $admin = $stmt -> fetch (PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin['password_hash'])){
        $_SESSION ['admin_logged_in'] = true; 
        header ('Location: dashboard.php');
        exit(); 
    } else {
        $error = 'invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title> Admin Login - Lavantine Eatery</title>
    <link rel="stylesheet" href= "../css/admin.css">
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
    <div class="login-container">
        <h2>Welcome, Admin</h2>
        <p>Please sign in to continue</p>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>