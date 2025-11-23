<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Us | The Amonds</title>
    <link rel="stylesheet" href="user_css/index.css">
    <style>
        body {
   font-family: 'Poppins', sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f7efe5; /* Creamy beige background */
   color: #4b2e05;
}

.container {
   width: 85%;
   margin: 40px auto;
   padding: 25px;
   background: #fffaf5;
   border-radius: 12px;
   box-shadow: 0 0 25px rgba(180, 120, 60, 0.15);
}

h1, h2 {
   color: #5c4033; /* Mocha color */
   font-weight: 600;
   text-align: center;
   text-shadow: 0 0 5px rgba(240, 200, 160, 0.3);
}

.map {
   width: 100%;
   height: 400px;
   border: none;
   border-radius: 10px;
   box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
   margin: 25px 0;
}

.contact-info, .hours {
   background: #fffaf3;
   padding: 25px;
   border-radius: 10px;
   box-shadow: 0 3px 15px rgba(150, 100, 50, 0.1);
   margin-top: 20px;
   transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.contact-info:hover, .hours:hover {
   transform: translateY(-4px);
   box-shadow: 0 6px 20px rgba(120, 70, 30, 0.2);
}

.contact-info h2, .hours h2 {
   color: #b6854d; /* Warm coffee brown */
   margin-bottom: 10px;
}

.contact-info p, .hours p, li {
   color: #4b2e05;
   line-height: 1.6;
   font-size: 15px;
}

ul {
   list-style-type: none;
   padding: 0;
}

li {
   margin-bottom: 6px;
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
   text-shadow: 0 0 5px rgba(210, 180, 140, 0.4);
}

/* Optional header styling */
header {
   background-color: #d2b48c;
   color: #fff;
   padding: 15px 0;
   text-align: center;
   font-size: 22px;
   font-weight: bold;
   letter-spacing: 1px;
   box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 768px) {
   .container {
      width: 90%;
      padding: 15px;
   }
   .map {
      height: 300px;
   }
   h1, h2 {
      font-size: 20px;
   }
}

    </style>
</head>
<body>

<?php
        include 'components/header.php'; // Include the header component
    ?>

<header>
    <h1>VISIT OUR CAKE SHOP</h1>
    <!-- Navigation and other header content -->
</header>

<div class="container">
    <h2></h2>
    <div class="map">
        
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13000.64437988214!2d121.05414969912817!3d14.362773573195463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d0c6ab0b49f1%3A0x75f8f1fba8c7dfca!2sHotel%20Sogo%20-%20San%20Pedro!5e0!3m2!1sen!2sph!4v1761800679125!5m2!1sen!2sph"  width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    
    <div class="contact-info">
        <h2>Contact Information</h2>
        <p><strong>Address:</strong> Somewhere in San Pedro</p>
        <p><strong>Phone:</strong> 0919-454-3122</p>
        <p><strong>Email:</strong> amonds@gmail.com</p>
    </div>

    <div class="hours">
        <h2>Where Open</h2>
        <p><strong>WEEKDAYS AND WEEKENDS</strong> 11:00 AM - 10:00 PM</p>
    </div>
</div>



</body>
</html>