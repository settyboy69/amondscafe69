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
  <link rel="stylesheet" href="../admin_css/admin_header.css">
  </head>

<body>

    <div class="container">
        <header>
            <h1 style="text-align: center;">Admin Dashboard</h1>
            <?php
         $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
        </header>

        <!-- Navigation -->
        <nav>
            <ul class="navigation">
            <li><a href="admin_dashboard.php">ADMIN PAGE</a></li>
                <li><a href="products.php">Product</a></li>
                <li><a href="placed_orders.php">Orders</a></li>
                <li><a href="admin_accounts.php">Admins</a></li>
                <li><a href="users_accounts.php">Users</a></li>
                <li><a href="admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn" style="background-color: transparent;">Sign Out</a></li>
            </ul>
        </nav>

        <main class="dashboard">
            <div class="cardbox">




   </section>

</header>