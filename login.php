<?php
session_start();
require 'config/db.php';

$message = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['account_number'] = $user['account_number'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "<p class='error'>Invalid email or password!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php echo $message; ?>
    <form method="POST">
        Email: <input type="email" name="email" required>
        Password: <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p><a href="register.php">Don't have an account? Register</a></p>
</div>
</body>
</html>
