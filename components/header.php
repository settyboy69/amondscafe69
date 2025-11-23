<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="user_css/header.css"> <!-- 🔗 Link your CSS here -->
</head>
<body>

<?php
// Ensure that the database connection and session are started
include 'connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
?>

<header class="header">
    <nav class="navbar">
        <ul class="left-nav">
            <div class="logo">
                <a href="index.php">
                    <img src="index_images/Amonds.png" alt="logo" width="40" height="40">
                </a>
            </div>
            <li class="menu-toggle"><a href="product.php">Menu</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="visit.php">Visit Us</a></li>
        </ul>

        <ul class="right-nav">
            <li>
                <div class="profile">
                    <?php
                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <li>Good Day:</li>
                        <a href="profile.php" class="name"><?= htmlspecialchars($fetch_profile['name']); ?></a>
                        <div class="flex">
                            <a href="orders.php" class="btn">ORDERS</a>
                            <a href="cart.php" class="btn">CART</a>
                            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="btn btn-danger">LOG OUT</a>
                        </div>
                    <?php } else { ?>
                        <p class="name"></p>
                        <a href="login.php"  class="btn btn-success">LOGIN</a>
                    <?php } ?>
                </div>
            </li>
        </ul>
    </nav>
</header>

</body>
</html>
