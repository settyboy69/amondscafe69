<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['update_payment'])) {

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

// New queries to get total price and total orders
$total_price_query = $conn->prepare("SELECT SUM(total_price) AS total_price, COUNT(id) AS total_orders FROM `orders` WHERE payment_status='completed'");
$total_price_query->execute();
$total_data = $total_price_query->fetch(PDO::FETCH_ASSOC);
$total_price = $total_data['total_price'];
$total_orders = $total_data['total_orders'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../admin_css/completed_orders.css">


</head>

<body>

   <?php include 'admin_header.php' ?>

   <!-- placed orders section starts  -->

   <section class="placed-orders">

      <h1 class="heading">placed orders</h1>
      
      <div class="order-summary">
   <h2 id="total-orders">Total Orders: <?= $total_orders; ?></h2>
   <h2 id="total-price">Total Price: ₱<?= number_format($total_price, 2); ?></h2>
</div>

      <div class="table_header">
         <p>Order Details</p>
         <div>
            <input placeholder="order number">
            <button class="add_new">search</button>
            <button class="reset-btn" onclick="resetTable()">Reset</button>
         </div>
      </div>

      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>ORDER #</th>
                  <th>Date</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th>products</th>
                  <th>Price</th>
                  <th>PaymentType</th>
                  <th>Status</th>
          
               </tr>
            </thead>
            <tbody>
               <?php
               $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status='completed'");
               $select_orders->execute();
               if ($select_orders->rowCount() > 0) {
                  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><?= $fetch_orders['user_id']; ?></td>
                        <td><?= $fetch_orders['id']; ?> </td>
                        <td><?= $fetch_orders['placed_on']; ?></td>
                        <td><?= $fetch_orders['name']; ?></td>
                        <td><?= $fetch_orders['email']; ?></td>
                        <td><?= $fetch_orders['number']; ?></td>
                        <td><?= $fetch_orders['address']; ?></td>
                        <td><?= $fetch_orders['total_products']; ?></td>
                        <td><?= $fetch_orders['total_price']; ?></td>
                        <td><?= $fetch_orders['method']; ?></td>
                        <td><?= $fetch_orders['payment_status']; ?></td>
                       
                     </tr>
               <?php
                  }
               } else {
                  echo '<p class="empty">no orders placed yet!</p>';
               }
               ?>
            </tbody>
         </table>
      </div>

      <script>
   function resetTable() {
      // Get the table body
      var tableBody = document.querySelector('.table tbody');
      
      // Clear the inner HTML of the table body
      tableBody.innerHTML = '';
      
      // Optionally, you can also display a message
      var message = document.createElement('tr');
      message.innerHTML = '<td colspan="12" class="empty">No orders displayed!</td>';
      tableBody.appendChild(message);
      
      // Reset total orders and total price
      document.getElementById('total-orders').innerText = 'Total Orders: 0';
      document.getElementById('total-price').innerText = 'Total Price: ₱0.00';
   }
</script>


   </section>

   <!-- placed orders section ends -->

</body>

</html>