<?php
session_start();
require 'config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Online Banking</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Dashboard</h2>
    <p>Welcome, <?php echo $user['fullname']; ?></p>
    <p>Account Number: <?php echo $user['account_number']; ?></p>
    <p>Balance: $<?php echo number_format($user['balance'],2); ?></p>

    <div class="dashboard-links">
        <a href="deposit.php">Deposit</a>
        <a href="withdraw.php">Withdraw</a>
        <a href="transfer.php">Transfer</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>
