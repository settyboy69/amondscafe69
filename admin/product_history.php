<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

$search_query = ''; // Variable for the search query
$selected_date = ''; // Variable for the selected date

// Check if the form has been submitted
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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product History</title>
   <link rel="stylesheet" href="../admin_css/admin_prd1.css">
   <script>
       // Clear the selected date input field when the page loads
       window.onload = function() {
           document.getElementById("selected_date").value = "";
       };
   </script>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="show-history">
   <h3>Product Action History</h3>

   <!-- Search Form -->
   <form method="POST" action="">
       <input type="text" name="search_query" placeholder="Product Name or ID" value="<?= htmlspecialchars($search_query); ?>">
       <input type="date" name="selected_date" id="selected_date" value="<?= htmlspecialchars($selected_date); ?>">
       <input type="submit" name="search" value="Search">
   </form>

   <table class="table">
       <thead>
           <tr>
               <th>Product ID</th>
               <th>Product Name</th>
               <th>Price</th>
               <th>DATE.TIME</th>
               <th>Action</th>
           </tr>
       </thead>
       <tbody>
           <?php
           // Prepare the SQL query with filters
           if (isset($_POST['search'])) {
               $query = "SELECT * FROM `action_history` WHERE 1=1";
               $params = []; // Initialize params array

               if ($search_query) {
                   $query .= " AND (`name` LIKE ? OR `product_id` = ?)";
                   $params[] = "%" . $search_query . "%"; // For name search
                   $params[] = $search_query; // For ID search
               }

               if ($selected_date) {
                   $query .= " AND DATE(`date`) = ?";
                   $params[] = $selected_date; // Only filter by the selected date
               }

               $show_history = $conn->prepare($query);
               $show_history->execute($params);
           } else {
               // If no search parameters are set, display all history
               $show_history = $conn->prepare("SELECT * FROM `action_history` ORDER BY `date` DESC");
               $show_history->execute();
           }

           // Check if any records were found
           if ($show_history->rowCount() > 0) {
               while ($fetch_history = $show_history->fetch(PDO::FETCH_ASSOC)) {
           ?>
                   <tr>
                       <td><?= htmlspecialchars($fetch_history['product_id']); ?></td>
                       <td><?= htmlspecialchars($fetch_history['name']); ?></td> 
                       <td><?= htmlspecialchars($fetch_history['price']); ?></td> 
                       <td><?= htmlspecialchars($fetch_history['date']); ?></td>
                       <td><?= htmlspecialchars($fetch_history['action']); ?></td>
                   </tr>
           <?php
               }
           } else {
               echo '<tr><td colspan="5">No history found!</td></tr>';
           }
           ?>
       </tbody>
   </table>
</section>

</body>
</html>