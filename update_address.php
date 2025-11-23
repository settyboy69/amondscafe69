<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:index.php');
};

if (isset($_POST['submit'])) {

   $address = $_POST['building'] . ', ' . $_POST['street'] . ', ' . $_POST['barangay'] . ', ' . $_POST['city'] . ', ' . $_POST['remarks']  . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'address saved!';
     // Redirect to checkout.php
     header('location:checkout.php');
     exit(); // Stop further execution
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
 
     <link rel="stylesheet" href="user_css/index.css">
   <link rel="stylesheet" href="user_css/update_address.css">


</head>

<body>

   <?php
        include 'components/header.php'; // Include the header component
    ?>

   <section class="form-container addressStyle">
        <a href="javascript:history.back()" class="back-link">Back</a>
      <form action="" method="post">
    <h3>Your Address</h3>
    
    <label for="building">Building No.</label>
    <input type="text" class="boxx" id="building" placeholder="building no." required maxlength="50" name="building">

    <label for="street">Street</label>
    <input type="text" class="boxx" id="street" placeholder="street" required maxlength="50" name="street">

    <label for="barangay">Barangay</label>
    <input type="text" class="boxx" id="barangay" placeholder="barangay" required maxlength="50" name="barangay">

    <label for="city">City</label>
    <input type="text" class="boxx" id="city" placeholder="city" required maxlength="50" name="city">

    <label for="remarks">Remarks</label>
    <input type="text" class="boxx" id="remarks" placeholder="remarks" required maxlength="50" name="remarks">

    <label for="pin_code">Post Code</label>
    <input type="number" class="boxx" id="pin_code" placeholder="post code" required max="999999" min="0" name="pin_code">
    
         <input type="submit" value="save address" name="submit" class="btn">
      </form>

   </section>


</body>







