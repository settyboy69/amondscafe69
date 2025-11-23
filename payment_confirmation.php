<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:home.php');
    exit;
}

if (isset($_POST['submit'])) {
    $order_id = $_POST['order_id'];
    $transaction_number = $_POST['transaction_number'];
    $screenshot = $_FILES['screenshot'];

    // Initialize screenshot path to null
    $screenshot_path = null;

    // Handle file upload if a screenshot is provided
    if ($screenshot['name']) {
        // Validate file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($screenshot['type'], $allowed_types)) {
            // Check if uploads directory exists
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true); // Create the directory if it doesn't exist
            }
            $screenshot_path = 'uploads/' . time() . '_' . basename($screenshot['name']);
            if (move_uploaded_file($screenshot['tmp_name'], $screenshot_path)) {
                // File uploaded successfully
            } else {
                $message[] = 'Failed to upload screenshot. Please try again.';
            }
        } else {
            $message[] = 'Invalid file type! Please upload a JPEG, PNG, or GIF image.';
        }
    }

    // Update the order with transaction number and screenshot if provided
    $update_order = $conn->prepare("UPDATE `orders` SET transaction_number = ?, screenshot = ? WHERE id = ?");
    $update_order->execute([$transaction_number, $screenshot_path, $order_id]);

    // Deduct stock and clear cart items
    $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $select_cart_items->execute([$user_id]);

    while ($fetch_cart = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
        $products_id = $fetch_cart['pid'];
        $quantity = $fetch_cart['quantity'];

        // Get current product quantity
        $get_quantity = $conn->prepare("SELECT quantity FROM `products` WHERE id = ?");
        $get_quantity->execute([$products_id]);
        $current_quantity = $get_quantity->fetchColumn();

        if ($current_quantity >= $quantity) {
            $new_quantity = $current_quantity - $quantity;
            $update_quantity = $conn->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
            $update_quantity->execute([$new_quantity, $products_id]);
        } else {
            $message[] = "Not enough stock for product: " . $fetch_cart['name'];
        }
    }

    // Clear the cart
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    if ($delete_cart->execute([$user_id])) {
        $message[] = 'Cart cleared successfully.';
    } else {
        $message[] = 'Failed to clear the cart.';
    }

    // Set a success message
    $message[] = 'Payment confirmation submitted successfully!';

    // Redirect to orders.php after processing
    header('Location: orders.php');
    exit; // Ensure that no further code is executed after the redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Confirmation</title>
   <link rel="stylesheet" href="user_css/onlinepay.css">
   <link rel="stylesheet" href="user_css/index.css">
</head>
<body>
    
   <!-- header section starts  -->
 <?php
        include 'components/header.php'; // Include the header component
    ?>
   <!-- header section ends -->

   <section class="payment-confirmation">
      <h1>Confirm Your Payment</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <input type="hidden" name="order_id" value="<?= $_GET['order_id']; ?>">
         <label for="transaction_number">Transaction Number:</label>
         <input type="text" name="transaction_number" required>
         
         <label for="screenshot">Upload Screenshot (optional):</label>
         <input type="file" name="screenshot" accept="image/*">
         
         <input type="submit" name="submit" value="Submit Confirmation">
      </form>

      <?php
      // Display messages if any
      if (isset($message)) {
          foreach ($message as $msg) {
              echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
          }
      }
      ?>
   </section>
      <!-- footer section starts  -->
<?php
        include 'components/footer.php'; // Include the footer component
    ?>
   <!-- footer section ends -->

</body>
</html>