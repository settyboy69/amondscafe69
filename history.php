<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:index.php');
    exit; // Ensure to stop script execution after redirect
}

// HTML structure
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="user_css/user_header.css">
    <link rel="stylesheet" href="user_css/index.css">
    <link rel="stylesheet" href="user_css/history.css">

    
</head>

<body>

      <?php
        include 'components/header.php'; // Include the header component
    ?>

    <section class="orders order-style">
     <a href="javascript:history.back()" class="back-link">Back</a>
        <h1 class="title">Your Order History</h1>
        <div class="box-container">

            <?php
            // Updated SQL query to order by placed_on date descending
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
            $select_orders->execute([$user_id]);

            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="box">
                        <p>Placed on: <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
                        <p>Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
                        <p>Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
                        <p>Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
                        <p>Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
                        <p>Payment method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
                        <p>Your orders: <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span></p>
                        <p>Total price: <span>₱<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span></p>
                        <p>Order Number: <span><?= htmlspecialchars($fetch_orders['id']); ?></span></p>
                        <p>Payment status: <span style="color: <?= $fetch_orders['payment_status'] == 'pending' ? 'red' : 'green'; ?>"><?= htmlspecialchars($fetch_orders['payment_status']); ?></span></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }
            ?>

        </div>
        <a href="javascript:history.back()" class="back-link">Back</a>
    </section>



</body>

</html>