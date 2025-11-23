<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    exit;
}

if (isset($_POST['submit'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);
    if ($check_cart->rowCount() > 0) {
        if ($address == '') {
            $message[] = 'Please add your address!';
        } else {
            // Insert order
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);
    
            // Retrieve the last inserted order ID
            $order_id = $conn->lastInsertId();
    
            // Deduct stock
            $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart_items->execute([$user_id]);
            $stock_sufficient = true; // Flag to check stock sufficiency
    
            while ($fetch_cart = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
                $products_id = $fetch_cart['pid'];
                $quantity = $fetch_cart['quantity'];
            
                $get_quantity = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $get_quantity->execute([$products_id]);
                $product_details = $get_quantity->fetch(PDO::FETCH_ASSOC);
                $current_quantity = $product_details['quantity'];
            
                if ($current_quantity >= $quantity) {
                    $new_quantity = $current_quantity - $quantity; // Calculate new quantity
                    $update_quantity = $conn->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
                    $update_quantity->execute([$new_quantity, $products_id]);
            
                   // Log the deducted quantity
$insert_deducted_history = $conn->prepare("INSERT INTO `quantity_history` (product_id, name, quantity, action, updated_at) VALUES (?, ?, ?, ?, NOW())");
$insert_deducted_history->execute([$products_id, $product_details['name'], $quantity, 'Quantity Bought']);

// Log the remaining quantity
$insert_remaining_history = $conn->prepare("INSERT INTO `quantity_history` (product_id, name, quantity, action, updated_at) VALUES (?, ?, ?, ?, NOW())");
$insert_remaining_history->execute([$products_id, $product_details['name'], $new_quantity, 'Remaining quantity']);

                    
            
                    // Add a message about the new quantity
                    $message[] = "Stock updated for product: " . $product_details['name'] . ". New quantity: " . $new_quantity;
                } else {
                    $message[] = "Not enough stock for product: " . $fetch_cart['name'];
                    $stock_sufficient = false; // Set flag to false if stock is insufficient
                }
            }
            
    
            // Clear the cart only if stock was sufficient
            if ($stock_sufficient) {
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                if ($delete_cart->execute([$user_id])) {
                    $message[] = 'Cart cleared successfully.';
                } else {
                    $message[] = 'Failed to clear the cart.';
                }
            }
    
            // Add the transaction number to the success message
            $message[] = 'Order placed successfully! Transaction Number: ' . $order_id;
    
            // Check and redirect for payment method
            if ($method == 'GCASH' || $method == 'MAYA') {
                header('Location: payment_confirmation.php?order_id=' . $order_id);
                exit;
            } elseif ($method == 'cash on delivery') {
                header('Location: orders.php');
                exit;
            }
        }
    } else {
        $message[] = 'Your cart is empty';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"  content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file links -->
    <link rel="stylesheet" href="user_css/user_header.css">
    <link rel="stylesheet" href="user_css/index.css">
    <link rel="stylesheet" href="user_css/checkout.css">
</head>

<body>

    <!-- Header section starts -->
    <?php include 'components/header.php'; ?>
    <!-- Header section ends -->

    <div class="heading"></div>

    <section class="checkout styleTheCheckout">

        <h1 class="title">ORDER SUMMARY</h1>

        <form action="" method="post">

            <div class="cart-items">
                <h3>Cart Items</h3>
                <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                ?>
                        <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">₱<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
                <?php
                    }
                } else {
                    echo '<p class="empty">Your cart is empty!</p>';
                }
                ?>
                <p class="grand-total"><span class="name">Total:</span><span class="price">₱<?= $grand_total; ?></span></p>
                <a href="cart.php" class="btn">View Cart</a>
            </div>

            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
            <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
            <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
            <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

            <div class="user-info">
                <h3>Your Info</h3>
                <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
                <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
                <a href="update_profile.php" class="btn">Update Info</a>
                <h3>Delivery Address</h3>
                <p><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {
                                                                echo 'Please enter your address';
                                                            } else {
                                                                echo $fetch_profile['address'];
                                                            } ?></span></p>
                <a href="update_address.php" class="btn">Update Address</a>
                <select name="method" class="box" required>
                    <option value="" disabled selected>Select Payment Method</option>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="GCASH">GCASH</option>
                    <option value="MAYA">MAYA</option>
                </select>
                <input type="submit" value="Place Order" class="btn <?php if ($fetch_profile['address'] == '') {
                                                                        echo 'disabled';
                                                                    } ?>" style="width:100%; background-color:#e76a4a;" name="submit">
            </div>

        </form>

        <?php
        // Display messages if any
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . $msg . '</p>';
            }
        }
        ?>

    </section>


</body>

</html>