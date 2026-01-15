<?php
session_start();
require 'config/db.php';
$message = "";

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Handle deposit
if(isset($_POST['deposit'])){
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    if($amount > 0){
        // Update user balance
        mysqli_query($conn, "UPDATE users SET balance = balance + $amount WHERE id='$user_id'");
        // Log transaction
        mysqli_query($conn, "INSERT INTO transactions (sender_account, receiver_account, amount, type, date)
                             VALUES ('', '{$_SESSION['account_number']}', $amount, 'deposit', NOW())");
        $message = "<p class='success'>Deposit successful!</p>";
    } else {
        $message = "<p class='error'>Enter a valid amount!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deposit - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Deposit Money</h2>
    <?php echo $message; ?>
    <form method="POST">
        Amount: <input type="number" name="amount" required>
        <button type="submit" name="deposit">Deposit</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>
