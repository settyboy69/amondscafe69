<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Fetch all transactions
$select_transactions = $conn->prepare("SELECT * FROM `orders` WHERE `method` IN ('GCash', 'Maya')");
$select_transactions->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>GCash and Maya Transactions</title>
   <link rel="stylesheet" href="../admin_css/placed_order1.css">

   <style>
      .modal {
   display: none; /* Hidden by default */
   position: fixed; /* Stay in place */
   z-index: 1; /* Sit on top */
   left: 0;
   top: 0;
   width: 100%; /* Full width */
   height: 100%; /* Full height */
   overflow: auto; /* Enable scroll if needed */
   background-color: rgb(0,0,0); /* Fallback color */
   background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

.modal-content {
   margin: auto;
   display: block;
   width: 80%;
   max-width: 700px;
}

.close {
   position: absolute;
   top: 15px;
   right: 35px;
   color: white;
   font-size: 40px;
   font-weight: bold;
   cursor: pointer;
}
   </style>
</head>

<body>

   <?php include 'admin_header.php' ?>

   <section class="transactions">

      <h1 class="heading">GCash and Maya Transactions</h1>

      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Transaction Number</th>
                  <th>Screenshot</th>
               </tr>
            </thead>
            <tbody>
   <?php
   if ($select_transactions->rowCount() > 0) {
      while ($fetch_transaction = $select_transactions->fetch(PDO::FETCH_ASSOC)) {
   ?>
         <tr>
            <td><?= $fetch_transaction['id']; ?></td>
            <td><?= $fetch_transaction['name']; ?></td>
            <td><?= $fetch_transaction['transaction_number']; ?></td>
            <td>
   <?php
   $screenshotPath = '../uploads/' . $fetch_transaction['screenshot'];
   echo "<button class='view-screenshot' onclick=\"openModal('$screenshotPath')\">View Screenshot</button>";
   echo "<p>Image Path: $screenshotPath</p>"; // Debugging line
   ?>
</td>
         </tr>
   <?php
      }
   } else {
      echo '<tr><td colspan="4" class="empty">No transactions found!</td></tr>';
   }
   ?>
</tbody>
         </table>
      </div>
   </section>

   <!-- Modal for Screenshot -->
   <div id="screenshotModal" class="modal">
      <span class="close" onclick="closeModal()">&times;</span>
      <img class="modal-content" id="modalImage" alt="Screenshot">
   </div>

   <script>
   function openModal(imageSrc) {
      console.log("Opening modal with image source: " + imageSrc); // Debug log
      document.getElementById("modalImage").src = imageSrc;
      document.getElementById("screenshotModal").style.display = "block";
   }

   function closeModal() {
      document.getElementById("screenshotModal").style.display = "none";
   }
</script>

</body>

</html>