<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

$total_price = 0; // Initialize total price

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings History</title>
    <link rel="stylesheet" href="../admin_css/earnings.css">
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="earnings-history">
        <h1 class="heading">Earnings History</h1>

        <?php
        $total_price = 0; // Initialize total price variable
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status='completed'");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                $total_price += $fetch_orders['total_price']; // Add to total price
            }
        }
        ?>

        <?php if ($total_price > 0): ?>
            <div class="total-price">
                <h2>Total Earnings: <?= number_format($total_price, 2); ?></h2>
            </div>
        <?php else: ?>
            <p class="empty">No earnings history available!</p>
        <?php endif; ?>

        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                       
                        <th>Products</th>
                        <th>Price</th>
                    
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reset the cursor to the beginning of the result set
                    $select_orders->execute(); // Re-execute the query to fetch data again
                    if ($select_orders->rowCount() > 0) {
                        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <tr>
                                <td><?= $fetch_orders['user_id']; ?></td>
                                
                                <td><?= $fetch_orders['total_products']; ?></td>
                                <td><?= $fetch_orders['total_price']; ?></td>
                                <td><?= $fetch_orders['method']; ?></td>
                                <td><?= $fetch_orders['payment_status']; ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>