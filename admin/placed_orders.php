<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

// Fetch total number of orders
$total_orders_query = $conn->prepare("SELECT COUNT(*) AS total FROM `orders`");
$total_orders_query->execute();
$total_orders = $total_orders_query->fetch(PDO::FETCH_ASSOC)['total'];

// Fetch total price of all orders
$total_price_query = $conn->prepare("SELECT SUM(total_price) AS total_price FROM `orders`");
$total_price_query->execute();
$total_price = $total_price_query->fetch(PDO::FETCH_ASSOC)['total_price'];

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

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../admin_css/placed_order1.css">
</head>

<body>

   <?php include 'admin_header.php' ?>

   <!-- placed orders section starts  -->

   <section class="placed-orders">

      <h1 class="heading">Placed Orders</h1>

      <!-- Display total orders and total price -->
      <p>Total Orders: <?= $total_orders; ?></p>
      <p>Total Price of All Orders: <?= number_format($total_price, 2); ?></p>

      <div class="table_header">
         <p>Order Details</p>
         <div>
            <input placeholder="order number">
            <button class="add_new">Search</button>
         </div>
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
                  <th>Payment Status</th>
                
               </tr>
            </thead>
            <tbody>
               <?php
               // Fetch all orders regardless of payment status
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
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
                        <td><?= $fetch_orders['total_price']; ?></td>
                        <td><?= $fetch_orders['method']; ?></td>
                        <td><?= $fetch_orders['payment_status']; ?></td>
                        <td>
                          
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<p class="empty">No orders placed yet!</p>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <!-- placed orders section ends -->

</body>

</html>