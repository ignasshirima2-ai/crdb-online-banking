<?php
session_start();
require 'config/db.php';
$message = "";

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Handle transfer
if(isset($_POST['transfer'])){
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];
    $sender_acc = $_SESSION['account_number'];

    $sender = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance FROM users WHERE account_number='$sender_acc'"));
    $receiver_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE account_number='$receiver'"));

    if(!$receiver_data){
        $message = "<p class='error'>Receiver account not found!</p>";
    } elseif($amount <= 0){
        $message = "<p class='error'>Enter a valid amount!</p>";
    } elseif($amount > $sender['balance']){
        $message = "<p class='error'>Insufficient balance!</p>";
    } else {
        // Deduct from sender
        mysqli_query($conn, "UPDATE users SET balance = balance - $amount WHERE account_number='$sender_acc'");
        // Add to receiver
        mysqli_query($conn, "UPDATE users SET balance = balance + $amount WHERE account_number='$receiver'");
        // Log transaction
        mysqli_query($conn, "INSERT INTO transactions (sender_account, receiver_account, amount, type, date)
                             VALUES ('$sender_acc', '$receiver', $amount, 'transfer', NOW())");
        $message = "<p class='success'>Transfer successful!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transfer - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Transfer Money</h2>
    <?php echo $message; ?>
    <form method="POST">
        Receiver Account Number: <input type="text" name="receiver" required>
        Amount: <input type="number" name="amount" required>
        <button type="submit" name="transfer">Transfer</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>
