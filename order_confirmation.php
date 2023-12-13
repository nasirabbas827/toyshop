<?php
// order_confirmation.php

session_start();

include('config.php');

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch the latest order details for the current user
$orderQuery = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC LIMIT 1";
$stmt = mysqli_prepare($conn, $orderQuery);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orderDetails = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$orderDetails) {
    // Handle the case where no order is found
    echo "No order found.";
    exit;
}

// Display order confirmation details
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Order Confirmation</h2>
        <p>Order ID: <?php echo $orderDetails['order_id']; ?></p>
        <p>Order Date: <?php echo $orderDetails['order_date']; ?></p>
        <p>Order Status: <?php echo $orderDetails['status']; ?></p>
        <p>Subtotal: $<?php echo $orderDetails['subtotal']; ?></p>

        <!-- Bootstrap button to go back to the home page -->
        <a href="home.php" class="btn btn-primary">Back to Home</a>
    </div>

    <!-- Bootstrap JS and jQuery scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
