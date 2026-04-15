<?php 
session_start(); 
require_once '../includes/db.php';

//if admin is alr logged in skip login page and go to dashboard
if(isset($_SESSION['admin_logged_in'])){
    header('Location: dashboard.php');
    exit();
}
$error = '';

//handle form submission when admin clicks Login
if ($_SERVER['REQUEST_METHOD']==='POST'){
    //grab and clean the usrnme and pass from the form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    //look up the username in admins table
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]); 

    //fetch matching admin row from  database
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    //check if admin exists and if the pass matchs the hashed version in database
    if ($admin && password_verify($password, $admin['password_hash'])){
        //if login successful give let em in
        $_SESSION['admin_logged_in'] = true; 
        header('Location: dashboard.php');
        exit(); 
    } else {
        //if login failed show error message
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Lavantine Eatery</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php require_once '../includes/nav.php'; ?>

    <div class="login-container">
        <h2>Welcome, Admin</h2>
        <p>Please sign in to continue</p>

        <!--show error message if login failed -->
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <!-- login form sends username and pass thru POST -->
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