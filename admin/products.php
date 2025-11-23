<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $quantity = $_POST['quantity']; // New field for quantity
    $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT); // Sanitize quantity

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product already exists!';
    } else {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
    
            // Insert the product
            $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image, description, quantity) VALUES(?,?,?,?,?,?)");
            $insert_product->execute([$name, $category, $price, $image, $description, $quantity]);
    
            // Get the last inserted product ID
            $last_product_id = $conn->lastInsertId();
    
            // Insert action history for product addition
            $insert_history = $conn->prepare("INSERT INTO `action_history`(action, product_id, name, price) VALUES(?, ?, ?, ?)");
            $insert_history->execute(['Product Added', $last_product_id, $name, $price]);
    
            // Insert quantity history using the correct product ID
            $insert_quantity_history = $conn->prepare("INSERT INTO `quantity_history`(product_id, name, quantity, action) VALUES(?, ?, ?, ?)");
            $insert_quantity_history->execute([$last_product_id, $name, $quantity, 'Quantity Added']);
    
            $message[] = 'New product added!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // First, fetch the product details to get the name and price before deleting
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    
    // Check if the product exists
    if ($fetch_delete_image) {
        // Log the deletion in action history before deleting the product
        $insert_history = $conn->prepare("INSERT INTO `action_history`(action, product_id, name, price) VALUES(?, ?, ?, ?)");
        $insert_history->execute(['Product Deleted', $delete_id, $fetch_delete_image['name'], $fetch_delete_image['price']]);

        // Delete the quantity history for the product
        $delete_quantity_history = $conn->prepare("DELETE FROM `quantity_history` WHERE product_id = ?");
        $delete_quantity_history->execute([$delete_id]);


        // Delete the product image from the server
        unlink('../uploaded_img/' . $fetch_delete_image['image']);
        
        // Delete the product from the products table
        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$delete_id]);

        // Delete the product from the cart
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
        $delete_cart->execute([$delete_id]);

        // Redirect to products page
        header('location:products.php');
        exit(); // It's a good practice to call exit after a header redirect
    } else {
        // Handle the case where the product does not exist (optional)
        $message[] = 'Product not found!';
    }
}
        
        // You may want to handle displaying messages to the user
        if (isset($message)) {
            foreach ($message as $msg) {
                echo "<div class='message'>{$msg}</div>";
            }
        }
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../admin_css/admin_prd1.css">
  
</head>

<body>

<?php include 'admin_header.php'; ?>

   <!-- add products section starts  -->

   <section class="add-products">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>ADD NEW PRODUCTS</h3>
      
      <label for="product-name">Product Name:</label>
      <input type="text" id="product-name" required placeholder="enter product name" name="name" maxlength="100" class="box">
      
      <label for="product-price">Product Price:</label>
      <input type="number" id="product-price" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      
      <label for="product-category">Category:</label>
      <select id="product-category" name="category" class="box" required>
         <option value="" disabled selected>-- SELECT CATEGORY --</option>
         <option value="coffee">COFFEE</option>
         <option value="pasta">PASTA</option>
         <option value="pastries">PASTRIES</option>
         <option value="dishes">DISHES</option>
         <option value="drinks">DRINKS</option>
         <option value="desserts">DESSERTS</option> 
         <option value="sandwiches">SANDWICHES</option>
      </select>
      
      <label for="product-description">Product Description:</label>
      <input type="text" id="product-description" required placeholder="enter product description" name="description" maxlength="255" class="box">

      <label for="product-quantity">Product Quantity:</label>
<input type="number" id="product-quantity" min="1" required placeholder="enter product quantity" name="quantity" class="box">
      


<label for="product-image">Product Image:</label><small>Minimum resolution is 500x500 and maximum resolution is 1024x1024.</small>
<input type="file" id="product-image" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>
</section>

   <!-- add products section ends -->

   <!-- show products section starts  -->

   <section class="show-products">

<div class="table_header">
    <p>Product Details</p>
    <div>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="PRODUCT ID or Name">
            <button class="add_new" type="submit">Search</button>
        </form>
    </div>
</div>

<div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>PHOTO</th>
                <th>NAME</th>
                <th>STOCK</th>
                <th>PRICE</th>
                <th>CATEGORY</th>
                <th>ACTION</th>
                <th>DESCRIPTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if the search parameter is set
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            // Prepare the SQL query with a WHERE clause for searching
            if ($search) {
                $show_products = $conn->prepare("SELECT * FROM `products` WHERE `id` LIKE ? OR `name` LIKE ?");
                $searchTerm = "%" . $search . "%"; // Use wildcard for partial matching
                $show_products->execute([$searchTerm, $searchTerm]);
            } else {
                $show_products = $conn->prepare("SELECT * FROM `products`");
                $show_products->execute();
            }

            if ($show_products->rowCount() > 0) {
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?= $fetch_products['id']; ?></td>
                        <td><img style="height: 60px;" src="../uploaded_img/<?= $fetch_products['image']; ?>" alt=""></td>
                        <td><?= $fetch_products['name']; ?></td>
                        <td><?= $fetch_products['quantity']; ?></td>
                        <td><span>₱</span><?= $fetch_products['price']; ?><span></td>
                        <td><?= $fetch_products['category']; ?></td>
                        <td>
                            <a href="update_product.php?update=<?= $fetch_products['id']; ?>"><button>Update</button></a>
                            <a href="products.php?delete=<?= $fetch_products['id']; ?>" onclick="return confirm('delete this product?');"><button>Delete</button></a>
                        </td>
                        <td><?= $fetch_products['description']; ?></td>
                    </tr>

            <?php
                }
            } else {
                echo '<p class="empty">no products found!</p>';
            }
            ?>
        </tbody>
    </table>
</div>

</section>

  

</body>

</html>