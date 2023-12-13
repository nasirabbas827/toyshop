<?php
// view_orders.php

session_start();

include('config.php');

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch the user's orders
$ordersQuery = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC";
$stmt = mysqli_prepare($conn, $ordersQuery);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Your Orders</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Subtotal</th>
                    <th>Order Items</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($orderDetails = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$orderDetails['order_id']}</td>";
                    echo "<td>{$orderDetails['order_date']}</td>";
                    echo "<td>{$orderDetails['status']}</td>";
                    echo "<td>{$orderDetails['subtotal']}</td>";
                    echo "<td>";

                    // Fetch and display order items with additional details from gifts table
                    $orderItemsQuery = "SELECT order_items.quantity, gifts.name, gifts.image_url, gifts.price FROM order_items JOIN gifts ON order_items.gift_id = gifts.gift_id WHERE order_id = ?";
                    $stmt = mysqli_prepare($conn, $orderItemsQuery);
                    mysqli_stmt_bind_param($stmt, "i", $orderDetails['order_id']);
                    mysqli_stmt_execute($stmt);
                    $orderItemsResult = mysqli_stmt_get_result($stmt);

                    echo "<ul>";
                    while ($orderItem = mysqli_fetch_assoc($orderItemsResult)) {
                        $imageUrl = "./admin/uploads/" . $orderItem['image_url'];
                        echo "<li>";
                        echo "<strong>{$orderItem['name']}</strong> - Quantity: {$orderItem['quantity']} - Price: {$orderItem['price']}";
                        echo "<br>";
                        echo "<img src='{$imageUrl}' alt='{$orderItem['name']}' style='max-width: 50px; max-height: 50px;'>";
                        echo "</li>";
                    }
                    echo "</ul>";

                    mysqli_stmt_close($stmt);

                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <!-- Bootstrap JS and jQuery scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
