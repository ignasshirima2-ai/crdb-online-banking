<?php
session_start();
require 'config/db.php';
$message = "";

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Handle withdrawal
if(isset($_POST['withdraw'])){
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance FROM users WHERE id='$user_id'"));

    if($amount > 0 && $amount <= $user['balance']){
        // Deduct balance
        mysqli_query($conn, "UPDATE users SET balance = balance - $amount WHERE id='$user_id'");
        // Log transaction
        mysqli_query($conn, "INSERT INTO transactions (sender_account, receiver_account, amount, type, date)
                             VALUES ('{$_SESSION['account_number']}', '', $amount, 'withdraw', NOW())");
        $message = "<p class='success'>Withdrawal successful!</p>";
    } elseif($amount <= 0){
        $message = "<p class='error'>Enter a valid amount!</p>";
    } else {
        $message = "<p class='error'>Insufficient balance!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Withdraw Money</h2>
    <?php echo $message; ?>
    <form method="POST">
        Amount: <input type="number" name="amount" required>
        <button type="submit" name="withdraw">Withdraw</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>
