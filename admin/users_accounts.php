<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

// Check if the admin is logged in
if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   try {
       // Start a transaction
       $conn->beginTransaction();

       // Delete the user from the users table
       $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
       if (!$delete_users->execute([$delete_id])) {
           throw new Exception("Failed to delete user.");
       }

       // Delete related orders
       $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
       if (!$delete_order->execute([$delete_id])) {
           throw new Exception("Failed to delete orders.");
       }

       // Delete related cart items
       $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
       if (!$delete_cart->execute([$delete_id])) {
           throw new Exception("Failed to delete cart.");
       }

       // Commit the transaction
       $conn->commit();
       header('location:users_accounts.php');
       exit();
   } catch (Exception $e) {
       // Rollback the transaction in case of error
       $conn->rollBack();
       echo "Error: " . $e->getMessage();
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../admin_css/user_account1.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <!-- User accounts section starts -->
   <section class="accounts">
      <h1 class="heading">User  Management</h1>

      <div class="table_header">
         <p>User Details</p>
         <div>
            <input placeholder="Customer name" type="text">
            <button class="add_new">Search</button>
         </div>
      </div>

      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Fetch user accounts from the database
               $select_account = $conn->prepare("SELECT * FROM `users`");
               $select_account->execute();
               if ($select_account->rowCount() > 0) {
                  while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><span><?= htmlspecialchars($fetch_accounts['id']); ?></span></td>
                        <td><span><?= htmlspecialchars($fetch_accounts['name']); ?></span></td>
                        <td><span><?= htmlspecialchars($fetch_accounts['email']); ?></span></td>
                        <td><span><?= htmlspecialchars($fetch_accounts['address']); ?></span></td>
                        <td>
                           <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account?');">
                              <button><i class="fa-solid fa-trash"></i></button>
                           </a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="5" class="empty">No accounts available</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>

   </section>
   <!-- User accounts section ends -->
               
</body>

</html>