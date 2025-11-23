<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update History</title>
    <link rel="stylesheet" href="../admin_css/admin_prd.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="update-history">
    <h3>Update History</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $show_history = $conn->prepare("SELECT * FROM `update_history` ORDER BY updated_at DESC");
            $show_history->execute();

            if ($show_history->rowCount() > 0) {
                while ($fetch_history = $show_history->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $fetch_history['id']; ?></td>
                        <td><?= $fetch_history['product_id']; ?></td>
                        <td><?= $fetch_history['name']; ?></td>
                        <td><span>₱</span><?= $fetch_history['price']; ?></td>
                        <td><?= $fetch_history['action']; ?></td>
                        <td><?= $fetch_history['updated_at']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="6" class="empty">No update history found!</td></tr>';
            }
            ?>
        </tbody>
    </table>
</section>

</body>
</html>