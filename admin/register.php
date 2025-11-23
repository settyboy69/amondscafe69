<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);  // Securely hash the password
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Check if the username already exists
   $check_user = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $check_user->execute([$name]);

   if ($check_user->rowCount() > 0) {
      // Username already exists
      $message[] = 'Username already exists!';
   } else {
      // Insert new admin into the database
      $insert_admin = $conn->prepare("INSERT INTO `admin` (name, password) VALUES (?, ?)");
      $insert_admin->execute([$name, $pass]);

      if ($insert_admin) {
         $message[] = 'Admin registered successfully!';
         // Redirect to the login page after successful registration
         header('location:admin_login.php');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>

   <link rel="stylesheet" href="../css/custom_login.css">
</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

</body>
<section class="box">

   <form action="register.php" method="POST">
      <h1>Register Admin</h1>
      <ul>
         <li><label for="name">User name</label></li>
         <li><input type="text" name="name" maxlength="20" required placeholder="" oninput="this.value = this.value.replace(/\s/g, '')"></li>
         <li><label for="pass">Password</label></li>
         <li><input type="password" name="pass" maxlength="20" required placeholder="" oninput="this.value = this.value.replace(/\s/g, '')"></li>
      </ul>
      <input type="submit" value="Register now" name="submit" class="button">
   </form>

</section>


</html>
