<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    $new_quantity = filter_var($new_quantity, FILTER_SANITIZE_NUMBER_INT);

    // Update the quantity in the products table
    $update_quantity = $conn->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
    $update_quantity->execute([$new_quantity, $product_id]);

    // Log the quantity change in the quantity history
    $insert_quantity_history = $conn->prepare("INSERT INTO `quantity_history`(product_id, name, quantity, action) VALUES(?, ?, ?, ?)");
    $insert_quantity_history->execute([$product_id, $_POST['product_name'], $new_quantity, 'NEW quantity Added']);

    $message[] = 'Quantity updated successfully!';
}

// Fetch all products
$show_products = $conn->prepare("SELECT * FROM `products`");
$show_products->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Stock</title>
   <link rel="stylesheet" href="../admin_css/admin_prd1.css">
   <style>
/* Style for the smaller number input box */
input[type="number"] {
    width: 25px; /* Set a fixed width */
    padding: 5px; /* Smaller padding */
    border: 2px solid #007BFF; /* Blue border */
    border-radius: 3px; /* Slightly rounded corners */
    font-size: 14px; /* Smaller font size */
    transition: border-color 0.3s ease; /* Smooth transition for border color */
}

/* Focus effect */
input[type="number"]:focus {
    border-color: #0056b3; /* Darker blue when focused */
    outline: none; /* Remove default outline */
}

/* Disable default spinner */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none; /* Remove default spinner */
    margin: 0; /* Remove margin */
}

/* Placeholder styling (if you use it) */
input[type="number"]::placeholder {
    color: #888; /* Placeholder color */
    opacity: 1; /* Make sure placeholder is fully opaque */
}

/* Style for invalid input */
input[type="number"]:invalid {
    border-color: red; /* Red border for invalid input */
}
    </style>
</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="manage-stock">
    <h3>Manage Product Stock</h3>

    <?php

    ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>STOCK</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($show_products->rowCount() > 0) {
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?= $fetch_products['id']; ?></td>
                        <td><?= $fetch_products['name']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="number" name="quantity" value="<?= $fetch_products['quantity']; ?>" min="0" required>
                                <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                                <input type="hidden" name="product_name" value="<?= $fetch_products['name']; ?>">
                                <input type="submit" name="update_quantity" value="Update" class="btn">
                            </form>
                        </td>
                        <td>
                            <a href="products.php?delete=<?= $fetch_products['id']; ?>" onclick="return confirm('Delete this product?');"><button>Delete</button></a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="4" class="empty">No products found!</td></tr>';
            }
            ?>
        </tbody>
    </table>
</section>

</body>
</html>