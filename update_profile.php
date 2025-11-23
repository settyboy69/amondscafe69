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
   // Only handle email and password updates
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   
   // Email update logic
   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if ($select_email->rowCount() > 0) {
         $message[] = 'email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   // Password update logic
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password updated successfully!';
         } else {
            $message[] = 'please enter a new password!';
         }
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
   <title>update profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="user_css/index.css">

   <link rel="stylesheet" href="user_css/update_profile.css">

</head>

<body>

   <!-- header section starts  -->
  <?php
        include 'components/header.php'; // Include the header component
    ?>
   <!-- header section ends -->

   <section class="form-container update-form">
    <form action="" method="post" aria-labelledby="update-profile-title">
        <h3 id="update-profile-title">Update Profile</h3>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>" class="boxx" maxlength="50" readonly>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="<?= htmlspecialchars($fetch_profile['email']); ?>" class="boxx" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
        </div>

        <div class="form-group">
            <label for="number">Phone Number:</label>
            <input type="number" id="number" name="number" value="<?= htmlspecialchars($fetch_profile['number']); ?>" class="boxx" min="0" max="9999999999" maxlength="10" readonly>
        </div>

        <div class="form-group">
            <label for="old_pass">Old Password:</label>
            <input type="password" id="old_pass" name="old_pass" placeholder="Enter your old password" class="boxx" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
        </div>

        <div class="form-group">
            <label for="new_pass">New Password:</label>
            <input type="password" id="new_pass" name="new_pass" placeholder="Enter your new password" class="boxx" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
        </div>

        <div class="form-group">
            <label for="confirm_pass">Confirm New Password:</label>
            <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm your new password" class="boxx" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
        </div>

        <input type="submit" value="Update Now" name="submit" class="btn">
    </form>
</section>






   <!-- custom js file link  -->

</body>

</html>