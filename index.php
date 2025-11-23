<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

include 'components/add_cart.php';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Kaffevin</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->

    <link rel="stylesheet" href="user_css/index1.css">

</head>

<body>

    <!-- header section starts  -->

    <?php
        include 'components/header.php'; // Include the header component
    ?>

    <!-- header section ends -->

    <!-- home section starts  -->

    <section class="hero" style="border-bottom: 2px solid #ff7f50;">
        <video autoplay loop muted>
            <source src="videos/kape.mp4" type="video/mp4">
        </video>
        <div class="hero-content">
            <h1>Welcome to Amonds</h1>
            <p>Find your favorite coffee and more</p>
            <a href="product.php" style="background-color: #ff7f50 !important" >Try Our Products Now</a>
        </div>
    </section>

    <!-- home section ends -->


    <!-- Featured Section -->
    <section class="featured">
        <h2>TRY Our Products</h2>
        <ul>
            <li class="column">
                <img src="images/kopiko.jpg" alt="Drink Image">
                <h3>Coffee</h3>
               
                <a href="product.php" class="read-more">Order Now</a> <!-- Add a link with a class "read-more" -->
            </li>
            <li class="column">
                <img src="images/impasta.jpg" alt="Drink Image">
                <h3>Pasta</h3>
                
                <a href="product.php" class="read-more">Order Now</a> <!-- Add a link with a class "read-more" -->
            </li>
            <li class="column">
                <img src="images/pande.jpg" alt="Drink Image">
                <h3>Pasties</h3>
                
                <a href="product.php" class="read-more">Order Now</a> <!-- Add a link with a class "read-more" -->
            </li>
        </ul>
    </section>


    <!-- about section starts  -->

    <section class="about" id="about" style="padding-top: 70px;">
        <div class="row">
          
            <div class="content">
                <p><strong>Every cup is crafted with love and care!</strong> Join us on a flavorful journey and uncover the heartwarming story behind our coffee – discover what makes us truly special!</p>
                <div class="button-container">
                    <a href="about_us.php" class="learn-more-button">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- about section ends -->

   <!-- Call-to-Action (CTA) Section -->
        <section class="cta-section" id="cta-section">
        <div class="row">
            <div class="column left-column">
                <h2>Create an Account to Order</h2>
                <p>Sign up for an account to order and checkout.</p>
                <a href="register.php" style="background-color: #ff7f50 !important" class="btn btn-success">Proceed to Registration</a>
            </div>
           
        </div>
    </section>


    <!-- footer section starts  -->

  

    <!-- Footer Section -->
       




    <!-- footer section ends -->
    
</>


    <!-- custom js file link  -->




</body>



</html>