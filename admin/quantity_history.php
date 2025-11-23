<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit(); // It's a good practice to exit after a header redirect
}

// Initialize variables
$search_query = ''; // Variable for the search query
$selected_date = ''; // Variable for the selected date
$no_history_message = ''; // Variable to hold the no history message

if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $selected_date = $_POST['selected_date'];

    // Convert date format from MM/DD/YY to YYYY-MM-DD
    if ($selected_date) {
        $selected_date = date('Y-m-d', strtotime($selected_date));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quantity History</title>
    <link rel="stylesheet" href="../admin_css/admin_prd1.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="quantity-history">
    <h3>Quantity History</h3>

    <!-- Search Form -->
    <form method="POST" action="">
        <input type="text" name="search_query" placeholder="Product Name or ID" value="<?= htmlspecialchars($search_query); ?>">
        <input type="date" name="selected_date" value="<?= htmlspecialchars($selected_date); ?>">
        <input type="submit" name="search" value="Search">
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Action</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
<?php
// Prepare the SQL query with filters
if ($search_query || $selected_date) {
    $query = "SELECT * FROM `quantity_history` WHERE 1=1";
    $params = []; // Initialize params array

    if ($search_query) {
        $query .= " AND (`name` LIKE ? OR `product_id` = ?)";
        $params[] = "%" . $search_query . "%"; // For name search
        $params[] = $search_query; // For ID search
    }

    if ($selected_date) {
        $query .= " AND DATE(`updated_at`) = ?";
        $params[] = $selected_date; // Only filter by the selected date
    }

    $show_history = $conn->prepare($query);
    $show_history->execute($params);

    // Check if any records were found
    if ($show_history->rowCount() > 0) {
        while ($fetch_history = $show_history->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= htmlspecialchars($fetch_history['product_id']); ?></td>
                <td><?= htmlspecialchars($fetch_history['name']); ?></td>
                <td><?= htmlspecialchars($fetch_history['quantity']); ?></td>
                <td><?= $fetch_history['quantity'] == 0 ? 'Sold Out' : htmlspecialchars($fetch_history['action']); ?></td>
                <td><?= htmlspecialchars($fetch_history['updated_at']); ?></td>
            </tr>
            <?php
        }
    } else {
        // Set the no history message if no records were found
        $no_history_message = 'No quantity history found for the specified criteria.';
    }
} else {
    // If no search parameters, show all history
    $show_history = $conn->prepare("SELECT * FROM `quantity_history` ORDER BY updated_at DESC, action DESC");
    $show_history->execute();

    // Check if any records were found
    if ($show_history->rowCount() > 0) {
        while ($fetch_history = $show_history->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= htmlspecialchars($fetch_history['product_id']); ?></td>
                <td><?= htmlspecialchars($fetch_history['name']); ?></td>
                <td><?= htmlspecialchars($fetch_history['quantity']); ?></td>
                <td><?= $fetch_history['quantity'] == 0 ? 'Sold Out' : htmlspecialchars($fetch_history['action']); ?></td>
                <td><?= htmlspecialchars($fetch_history['updated_at']); ?></td>
            </tr>
            <?php
        }
    } else {
        // Set the no history message if no records were found
        $no_history_message = 'No quantity history found.';
    }
}
?>
</tbody>
                    </table>
                
                    <!-- Display the no history message if applicable -->
                    <?php if ($no_history_message): ?>
                        <p class="empty"><?= htmlspecialchars($no_history_message); ?></p>
                    <?php endif; ?>
                </section>
                
                </body>
                </html>