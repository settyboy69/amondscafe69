<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Update payment status
if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

// Delete order
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}
// Search functionality
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM `orders` WHERE payment_status='pending'";

if ($search_query) {
    $sql .= " AND (id LIKE :search OR name LIKE :search OR address LIKE :search OR payment_status LIKE :search)";
}

$select_orders = $conn->prepare($sql);

// Bind the search parameter
if ($search_query) {
    $like_search = "%" . $search_query . "%";
    $select_orders->bindParam(':search', $like_search, PDO::PARAM_STR);
}

$select_orders->execute();

// Count total pending orders
$count_pending_orders = $conn->prepare("SELECT COUNT(*) FROM `orders` WHERE payment_status='pending'");
$count_pending_orders->execute();
$total_pending_orders = $count_pending_orders->fetchColumn();

// Calculate total price of pending orders
$total_price_pending = $conn->prepare("SELECT SUM(total_price) FROM `orders` WHERE payment_status='pending'");
$total_price_pending->execute();
$sum_pending_price = $total_price_pending->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../admin_css/pending1.css">
</head>

<body>

   <?php include 'admin_header.php' ?>

   <!-- placed orders section starts  -->

   <section class="placed-orders">

<h1 class="heading">Placed Orders</h1>

<div class="order-summary">
    <p>Total Pending Orders: <?= $total_pending_orders; ?></p>
    <p>Total Price of Pending Orders: <?= number_format($sum_pending_price, 2); ?></p>
</div>

<div class="table_header">
    <p>Order Details</p>
    <form action="" method="POST">
        <div>
            <input type="text" name="search_query" placeholder="Search by order #, Name, Address, or Status">
            <button class="add_new" type="submit">Search</button>
        </div>
    </form>
</div>
<div>
    <table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Order #</th>
        <th>Date</th>
        <th>Name</th>
        <th>Address</th>
        <th>Products</th>
        <th>Price</th>
        <th>Payment Type</th>
        <th>Status</th>
        <th>Transaction Number</th> <!-- New Column Header -->
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if ($select_orders->rowCount() > 0) {
        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
    ?>
            <tr>
                <td><?= $fetch_orders['user_id']; ?></td>
                <td><?= $fetch_orders['id']; ?></td>
                <td><?= $fetch_orders['placed_on']; ?></td>
                <td><?= $fetch_orders['name']; ?></td>
                <td><?= $fetch_orders['address']; ?></td>
                <td><?= $fetch_orders['total_products']; ?></td>
                <td><?= number_format($fetch_orders['total_price'], 2); ?></td>
                <td><?= $fetch_orders['method']; ?></td>
                <td><?= $fetch_orders['payment_status']; ?></td>
                <td><?= $fetch_orders['transaction_number']; ?></td> <!-- New Column Data -->
                <td>
                    <?php if ($fetch_orders['payment_status'] == 'pending') { ?>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <select name="payment_status" class="drop-down">
                                <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                <option value="completed">completed</option>
                                <option value="canceled">canceled</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" value="update" class="btn" name="update_payment">
                               
                            </div>
                        </form>
                    <?php } else { ?>
                        <span>No actions available</span>
                    <?php } ?>
                </td>
            </tr>
    <?php
        }
    } else {
        echo '<tr><td colspan="13">No pending orders found</td></tr>';
    }
    ?>
</tbody>
    </table>
</div>
</section>

   <!-- placed orders section ends -->

</body>

</html>