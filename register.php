<?php

include 'components/connect.php';

session_start();

// Generate a random CAPTCHA code
if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = substr(str_shuffle(MD5(microtime())), 0, 6);
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $captcha = $_POST['captcha'];

   // Check CAPTCHA code
   if ($captcha != $_SESSION['captcha_code']) {
      $message[] = 'Invalid CAPTCHA code!';
   } else {
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
      $select_user->execute([$email, $number]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $message[] = 'email or number already exists!';
      }else{
         if($pass != $cpass){
            $message[] = 'confirm password not matched!';
         }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $cpass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if($select_user->rowCount() > 0){
               $_SESSION['user_id'] = $row['id'];
               header('location:index.php');
            }
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
   <title>Register | Eerie Portal</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS files -->
   <link rel="stylesheet" href="user_css/index.css">
  

   <style>
      /* ===== Eerie Dark Theme ===== */
body {
   font-family: 'Poppins', sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f5e6d3; /* Light coffee cream background */
   color: #4b2e05;
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
   min-height: 100vh;
   background-image: radial-gradient(circle at top left, #f3d7b8, #d2b48c 70%);
}

h2 {
   color: #5c4033;
   text-shadow: 0 0 6px rgba(255, 245, 230, 0.8);
   margin-bottom: 20px;
   text-align: center;
   letter-spacing: 1px;
   font-weight: 600;
}

.form-container {
   background-color: rgba(255, 250, 245, 0.95);
   border: 2px solid #c2a079;
   border-radius: 12px;
   box-shadow: 0 0 25px rgba(150, 100, 50, 0.2);
   padding: 40px;
   width: 90%;
   max-width: 400px;
   text-align: center;
   animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
   from { opacity: 0; transform: translateY(20px); }
   to { opacity: 1; transform: translateY(0); }
}

label {
   display: block;
   margin-bottom: 8px;
   font-weight: 600;
   font-size: 14px;
   color: #4b2e05;
   text-align: left;
}

label.required::after {
   content: " *";
   color: #c08457;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"],
input[type="number"] {
   width: 100%;
   max-width: 320px;
   padding: 10px;
   margin-bottom: 18px;
   border: 2px solid #d9b38c;
   border-radius: 8px;
   background-color: #fffaf3;
   color: #4b2e05;
   font-size: 14px;
   transition: all 0.3s;
   outline: none;
}

input:focus {
   border-color: #b6854d;
   box-shadow: 0 0 10px rgba(182, 133, 77, 0.4);
   background-color: #fff6eb;
}

.btn {
   padding: 10px 20px;
   background-color: #b6854d;
   color: #fff;
   border: none;
   border-radius: 8px;
   cursor: pointer;
   font-size: 16px;
   transition: 0.3s;
   width: 100%;
   max-width: 320px;
   text-transform: uppercase;
   letter-spacing: 1px;
   box-shadow: 0 3px 10px rgba(146, 92, 43, 0.3);
}

.btn:hover {
   background-color: #a8733a;
   box-shadow: 0 0 15px rgba(146, 92, 43, 0.5);
}

p {
   color: #6b4c2b;
   font-size: 14px;
   margin-top: 15px;
}

a {
   color: #b6854d;
   text-decoration: none;
   border-bottom: 1px solid transparent;
   transition: 0.3s;
}

a:hover {
   color: #8f6332;
   border-bottom: 1px solid #8f6332;
   text-shadow: 0 0 5px rgba(210, 180, 140, 0.5);
}

h3 {
   color: #8f6332;
   text-shadow: 0 0 4px rgba(255, 235, 205, 0.6);
   margin-top: 10px;
}

strong {
   color: #b6854d;
   text-shadow: 0 0 8px rgba(182, 133, 77, 0.4);
}

/* Responsive adjustments */
@media (max-width: 600px) {
   .form-container {
      padding: 30px 20px;
   }
   h2 {
      font-size: 20px;
   }
}

   </style>
</head>

<body>

   <!-- header section -->
   <?php include 'components/header.php'; ?>

   <!-- Registration Form -->
   <section class="form-container">
      <h2>New User Registration</h2>
      <form action="" method="post" onsubmit="return validateForm()">
         <label for="name" class="required">Name</label>
         <input type="text" id="name" name="name" required placeholder="Enter your name" maxlength="50">

         <label for="email" class="required">Email</label>
         <input type="email" id="email" name="email" required placeholder="Enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">

         <label for="number" class="required">Phone Number</label>
         <input type="tel" id="number" name="number" required placeholder="Enter your number" maxlength="11">

         <label for="pass" class="required">Password</label>
         <input type="password" id="pass" name="pass" required placeholder="Enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">

         <label for="cpass" class="required">Confirm Password</label>
         <input type="password" id="cpass" name="cpass" required placeholder="Confirm your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">

         <label for="captcha" class="required">Verification Code</label>
         <input type="text" id="captcha" name="captcha" required placeholder="Enter CAPTCHA code" maxlength="6">
         <h3>CAPTCHA Code: <strong><?php echo $_SESSION['captcha_code']; ?></strong></h3>

         <input type="submit" value="Register Now" name="submit" class="btn">
         <p>Already have an account? <a href="login.php">Login now</a></p>
      </form>
   </section>

   <script>
      function validateForm() {
         var password = document.getElementById("pass");
         var confirmPassword = document.getElementById("cpass");

         password.style.borderColor = "";
         confirmPassword.style.borderColor = "";

         if (password.value.length < 8) {
             alert("Password must be at least 8 characters long.");
             password.style.borderColor = "red";
             confirmPassword.style.borderColor = "red";
             return false;
         }

         if (password.value !== confirmPassword.value) {
             alert("Passwords do not match.");
             password.style.borderColor = "red";
             confirmPassword.style.borderColor = "red";
             return false;
         }

         return true;
      }
   </script>
</body>
</html>
