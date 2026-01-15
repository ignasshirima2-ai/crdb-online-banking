<?php
session_start();
require 'config/db.php';

$message = "";

if(isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $account_number = rand(10000000,99999999);
    $balance = 0;

    $sql = "INSERT INTO users (fullname, email, password, account_number, balance, created_at) 
            VALUES ('$fullname','$email','$password','$account_number','$balance',NOW())";
    
    if(mysqli_query($conn, $sql)){
        $message = "<p class='success'>Registration successful! <a href='login.php'>Login here</a></p>";
    } else {
        $message = "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php echo $message; ?>
    <form method="POST">
        Full Name: <input type="text" name="fullname" required>
        Email: <input type="email" name="email" required>
        Password: <input type="password" name="password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p><a href="login.php">Already have an account? Login</a></p>
</div>
</body>
</html>
