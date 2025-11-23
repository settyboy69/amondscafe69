<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

?>

<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <button onclick="this.parentElement.remove();">Close</button>
      </div>
      ';
    }
}
?>

<?php
$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../admin_css/dashboard1.css">
</head>

<body>

    <div class="container">
        <header>
            <h1 style="text-align: center;">Admin Dashboard</h1>
            <p style="text-align: center;">LOGIN:  <?= $fetch_profile['name']; ?></p>
        </header>

        <!-- Navigation -->
        <nav>
            <ul class="navigation">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_stock.php">Product</a></li>
                <li><a href="placed_orders.php">Orders</a></li>
                <li><a href="admin_accounts.php">Admins</a></li>
                <li><a href="users_accounts.php">Users</a></li>
                <li><a href="admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Sign Out</a></li>
            </ul>
        </nav>

        <main class="dashboard">

            <div class="box">
    <?php
    $total_pendings = 0;
    $total_pending_count = 0; // Initialize a counter for the number of pending orders
    $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
    $select_pendings->execute(['pending']);
    
    while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
        $total_pendings += $fetch_pendings['total_price'];
        $total_pending_count++; // Increment the counter for each pending order
    }
    ?>
    <div>
        <div class="numbers">₱<?= number_format($total_pendings, 0); ?></div> <!-- No decimal places -->
        <div class="boxName"> Pendings Orders: <?= $total_pending_count; ?></div> <!-- Display the count of pending orders -->
    </div>
    <div class="link">
        <a href="pending_orders.php">View Pending Orders</a>
    </div>
</div>



<div class="box">
    <?php
    $total_completes = 0;
    $completed_orders_count = 0; // Initialize a counter for completed orders
    $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
    $select_completes->execute(['completed']);
    
    while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
        $total_completes += $fetch_completes['total_price'];
        $completed_orders_count++; // Increment the counter for each completed order
    }
    ?>
    <div>
        <div class="numbers">₱<?= number_format($total_completes, 0); ?></div>
        <div class="count">Total Completed Orders: <?= $completed_orders_count; ?></div> <!-- Display the count -->
    </div>
    <div class="link">
        <a href="completed_orders.php">View Completed Orders</a>
    </div>
</div>




<div class="box">
    <?php
    // Prepare and execute a query to get total orders and total price
    $select_orders = $conn->prepare("SELECT COUNT(*) AS total_orders, SUM(total_price) AS total_price FROM `orders`");
    $select_orders->execute();
    $order_data = $select_orders->fetch(PDO::FETCH_ASSOC);

    // Extract values from the fetched data
    $numbers_of_orders = $order_data['total_orders'];
    $total_price = $order_data['total_price'] ?? 0; // Default to 0 if total_price is null
    ?>
    
    <div>
        <div class="numbers">₱<?= number_format($total_price, 0); ?></div>
        <div class="boxName">Total Orders: <?= $numbers_of_orders; ?></div>
    </div>
    
    <div class="link">
        <a href="placed_orders.php">View All Orders</a>
    </div>
</div>


<div class="box">
                    <div>
                        <div class="numbers">₱<?= $total_completes; ?></div>
                        <div class="boxName">Earnings</div>
                    </div>
                    <div class="link">
                        <a href="earnings.php">View Earnings</a>
                    </div>
                </div>

           


                <div class="box">
                    <?php
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                    $numbers_of_products = $select_products->rowCount();
                    ?>
                    <div>
                        <div class="numbers"><?= $numbers_of_products; ?></div>
                        <div class="boxName">Products Added</div>
                    </div>
                    <div class="link">
                        <a href="products.php">View Products</a>
                    </div>
                </div>

                <div class="box">
                    <?php
                    $select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();
                    $numbers_of_users = $select_users->rowCount();
                    ?>
                    <div>
                        <div class="numbers"><?= $numbers_of_users; ?></div>
                        <div class="boxName">User  Accounts</div>
                    </div>
                    <div class="link">
                        <a href="users_accounts.php">View Users</a>
                    </div>
                </div>

                <div class="box">
                    <div>
                     
                        <div class="boxName">ADMIN ACTIVITY HISTORY</div>
                    </div>
                    <div class="link">
                        <a href="product_history.php">View </a>
                    </div>
                </div>
    
                <div class="box">
                    <div>
                     
                        <div class="boxName">STOCKS</div>
                    </div>
                    <div class="link">
                        <a href="quantity_history.php">View </a>
                    </div>
                </div>

                <div class="box">
                    <div>
                     
                        <div class="boxName">ONLINE PAYMENTS</div>
                    </div>
                    <div class="link">
                        <a href="online_payment.php">View </a>
                    </div>
                </div>

                </div>
                
        </main>
    </div>