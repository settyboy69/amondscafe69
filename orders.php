<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="user_css/index.css">
   <link rel="stylesheet" href="user_css/order.css">



</head>

<body>

   <!-- header section starts  -->
 <?php
        include 'components/header.php'; // Include the header component
    ?>
   <!-- header section ends -->


   <section class="orders ordersToStyle">
      <h1 class="title">ORDERS</h1>
      <div class="box-container">

      <?php
      if ($user_id == '') {
          echo '<p class="empty">please login to see your orders</p>';
      } else {
          // Update the SQL query to get the latest order
          $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status IN ('completed', 'pending') ORDER BY id DESC LIMIT 1");
          $select_orders->execute([$user_id]);
          
          if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <p>placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
                    <p>number : <span><?= $fetch_orders['number']; ?></span></p>
                    <p>address : <span><?= $fetch_orders['address']; ?></span></p>
                    <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
                    <p>your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
                    <p>total price : <span>₱<?= $fetch_orders['total_price']; ?>/-</span></p>
                    <p>Order Number: <span><?= $fetch_orders['id']; ?> </span></p>
                    <p>status:<span style="color:<?php 
                    if ($fetch_orders['payment_status'] == 'pending') {
                        echo 'red';
                    } elseif ($fetch_orders['payment_status'] == 'completed') {
                        echo 'green';
                    } elseif ($fetch_orders['payment_status'] == 'cancelled') {
                        echo 'yellow';
                    } else {
                        echo 'black'; // Default color if none of the above
                    }; ?>"><?= $fetch_orders['payment_status']; ?></span></p>
            
                    <?php if ($fetch_orders['method'] == 'GCASH' || $fetch_orders['method'] == 'MAYA') { ?>
                        <p>Transaction Number: <span><?= $fetch_orders['transaction_number']; ?></span></p>
                        <?php 
                        if ($fetch_orders['screenshot']) { ?>
                            <p>Screenshot: <img src="<?= $fetch_orders['screenshot']; ?>" alt="Screenshot" width="100"></p>
                        <?php } ?>
                    <?php } ?>
            
                    <!-- Cancel Order Form -->
                    <?php if ($fetch_orders['payment_status'] == 'pending') { // Only show cancel button if order is pending ?>
                        <form method="POST" action="cancel_order.php">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <input type="submit" name="cancel_order" value="Cancel Order" onclick="return confirm('Are you sure you want to cancel this order?');">
                        </form>
                    <?php } ?>
                </div>
            <?php
            }
          } else {
              echo '<p class="empty">no orders placed yet!</p>';
          }
      }
      ?>

      </div>
   </section>

   <!-- footer section starts  -->

   <!-- footer section ends -->

</body>
</html>