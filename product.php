<?php
include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';
include 'components/add_cart.php';
// components/add_cart.php

if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    // Your existing code to add the product to the cart...
    $_SESSION['cart'][$pid] = [
        'name' => $name,
        'price' => $price,
        'quantity' => $qty
    ];

    // Set a session message
    $_SESSION['cart_message'] = "Added $qty of $name to your cart.";
}

function renderProducts($category, $conn) {
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE category=:category");
    $select_products->execute(['category' => $category]);
    if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            $available_quantity = (int)$fetch_products['quantity']; // Get the available quantity
            ?>
            <form action="" method="post" class="box-products">
                <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">

                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                <a href="category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
                <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                <div class="description"><?= htmlspecialchars($fetch_products['description']); ?></div>

                <div class="flex">
                    <div class="price"><span>₱</span><?= htmlspecialchars($fetch_products['price']); ?></div>
                    <div class="button-container">
                        <?php if ($available_quantity > 0): ?>
                            <input type="number" name="qty" class="qty" min="1" max="<?= $available_quantity; ?>" value="1" maxlength="2">
                            <button type="submit" class="add-to-cart-btn" name="add_to_cart">Add</button>
                        <?php else: ?>
                            <p class="sold-out">Sold Out</p>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="quantity-info">Available : <?= $available_quantity; ?></p> <!-- Display available quantity -->
            </form>
            <?php
        }
    } else {
        echo '<p class="empty">No products added yet!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
 <link rel="stylesheet" href="user_css/header.css"> 
    <link rel="stylesheet" href="user_css/product1.css">
    <link rel="stylesheet" href="user_css/menu_selection1.css">
    <link rel="stylesheet" href="user_css/menu.css"> <!-- New CSS file for menu styles -->
    <style>
        html {
            scroll-behavior: smooth; /* Enables smooth scrolling */
        }
        .cart-message {
    background-color: white; /* White background */
    color: #155724; /* Dark green text */
    padding: 10px;
    margin: 20px auto; /* Center the message with auto margins */
    border: 1px solid #c3e6cb; /* Border color */
    border-radius: 5px;
    max-width: 600px; /* Optional: Set a maximum width */
    text-align: center; /* Center the text */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
}
    </style>
</head>
<body>

<?php include 'components/header.php'; ?>

<h3>OUR MENU</h3>

<?php
// Check for cart message
$cart_message = $_SESSION['cart_message'] ?? '';
unset($_SESSION['cart_message']); // Clear the message after displaying it

if ($cart_message): ?>
    <div class="cart-message">
        <p><?= htmlspecialchars($cart_message); ?></p>
    </div>
<?php endif; ?>

<div class="menu-container">
    <div class="menu-selection-container">
        <ul class="menu-selection">
            <h1>MENU</h1>
            <li><a href="#Coffee">Coffee</a></li>
            <li><a href="#Pasta">Pasta</a></li>
            <li><a href="#Dishes">Dishes</a></li>
            <li><a href="#Pastries">Pastries</a></li>
            <li><a href="#Drinks">Drinks</a></li>
            <li><a href="#desserts">Desserts</a></li>
            <li><a href="#Sandwiches">Sandwiches</a></li>
        </ul>
    </div>

    <div class="menu-section-container">
        <section id="Coffee" class="products">
            <h1 class="title">Coffee / KAPE</h1>
            <div class="box-container">
                <?php renderProducts('coffee', $conn); ?>
            </div>
        </section>

        <section id="Pasta" class="products">
            <h1 class="title">Pasta</h1>
            <div class="box-container">
                <?php renderProducts('pasta', $conn); ?>
            </div>
        </section>

        <section id="Dishes" class="products">
            <h1 class="title">Dishes</h1>
            <div class="box-container">
                <?php renderProducts('dishes', $conn); ?>
            </div>
        </section>

        <section id="Pastries" class="products">
            <h1 class="title">Pastries</h1>
            <div class="box-container">
                <?php renderProducts('pastries', $conn); ?>
            </div>
        </section>

        <section id="Drinks" class="products">
            <h1 class="title">Drinks</h1>
            <div class="box-container">
                <?php renderProducts('drinks', $conn); ?>
            </div>
        </section>

        <section id="desserts" class="products">
            <h1 class="title">DESSERTS / PANG-HIMAGAS</h1>
            <div class="box-container">
                <?php renderProducts('desserts', $conn); ?>
            </div>
        </section>

        <section id="Sandwiches" class="products">
            <h1 class="title">Sandwiches</h1>
            <div class="box-container">
                <?php renderProducts('sandwiches', $conn); ?>
            </div>
        </section>
    </div>
</div>


</body>
</html>