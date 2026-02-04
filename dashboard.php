<?php
session_start();
require 'config/db.php';

/* Protect page */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Fetch user info */
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_q);

/* Fetch recent transactions */
$trx_q = mysqli_query($conn,
    "SELECT * FROM transactions 
     WHERE sender_account='{$user['account_number']}'
        OR receiver_account='{$user['account_number']}'
     ORDER BY created_at DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRDB Online Banking</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dark.css">
    <script src="assets/js/darkmode.js"></script>
</head>
<body>

<div class="layout">

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <img src="assets/images/crdb-logo.png" class="logo" alt="CRDB Bank">
    <h3>CRDB BANK</h3>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="deposit.php">➕ Deposit</a>
    <a href="withdraw.php">➖ Withdraw</a>
    <a href="transfer.php">🔁 Transfer</a>
    <a href="transactions.php">📄 Transactions</a>
    <a href="reports/statement.php">📑 PDF Statement</a>
    <a href="logout.php" class="logout">🚪 Logout</a>

    <button onclick="toggleDark()">🌙 Dark Mode</button>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main">

    <!-- Welcome -->
    <div class="welcome-card">
        <h2>Welcome to CRDB Online Banking</h2>
        <p>Hello <strong><?= htmlspecialchars($user['fullname']) ?></strong></p>
        <p>Account Number: <strong><?= $user['account_number'] ?></strong></p>
    </div>

    <!-- Balance -->
    <div class="balance-card">
        <h4>Current Balance</h4>
        <h1>Tshs. <?= number_format($user['balance'], 2) ?></h1>
    </div>

    <!-- Action Buttons -->
    <div class="actions">
        <a href="deposit.php">Deposit</a>
        <a href="withdraw.php">Withdraw</a>
        <a href="transfer.php">Transfer</a>
    </div>

    <!-- Recent Transactions -->
    <h3>Recent Transactions</h3>

    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>

            <?php while($trx = mysqli_fetch_assoc($trx_q)): ?>
            <tr>
                <td><?= $trx['id'] ?></td>
                <td><?= ucfirst($trx['transaction_type']) ?></td>
                <td><?= $trx['sender_account'] ?></td>
                <td><?= $trx['receiver_account'] ?></td>
                <td class="<?= $trx['transaction_type'] == 'deposit' ? 'amount-in' : 'amount-out' ?>">
                    <?= number_format($trx['amount'], 2) ?>
                </td>
                <td><?= $trx['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</div>

</body>
</html>
