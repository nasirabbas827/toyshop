<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all orders with item details and username
$ordersQuery = "
    SELECT 
        orders.order_id, 
        orders.user_id, 
        orders.order_date, 
        orders.status, 
        orders.subtotal,
        users.username,
        order_items.quantity,
        gifts.name AS gift_name,
        gifts.image_url,
        gifts.price
    FROM orders
    INNER JOIN order_items ON orders.order_id = order_items.order_id
    INNER JOIN gifts ON order_items.gift_id = gifts.gift_id
    INNER JOIN users ON orders.user_id = users.id
    ORDER BY orders.order_id DESC
";

$result = mysqli_query($conn, $ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Orders</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include('admin_navbar.php'); ?>

    <div class="container mt-5">
        <h2>Admin View Orders</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Item Image</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>

                    <th>Order Date</th>
                    <th>Order Status</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($orderDetails = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$orderDetails['order_id']}</td>";
                    echo "<td>{$orderDetails['username']}</td>";
                    echo "<td><img src='./uploads/{$orderDetails['image_url']}' alt='{$orderDetails['gift_name']}' style='max-width: 50px; max-height: 50px;'></td>";
echo "<td>{$orderDetails['gift_name']}</td>";
                    echo "<td>{$orderDetails['price']}</td>";
                    echo "<td>{$orderDetails['quantity']}</td>";
                    echo "<td>{$orderDetails['subtotal']}</td>";
                    echo "<td>{$orderDetails['status']}</td>";

                    echo "<td>{$orderDetails['order_date']}</td>";
                    
                    echo "<td>";
                    echo "<form method='post' action='admin_update_order.php'>";
                    echo "<input  type='hidden' name='order_id' value='{$orderDetails['order_id']}'>";
                    echo "<select class='form-control mb-2' name='status'>";
                    echo "<option value='pending' " . ($orderDetails['status'] == 'pending' ? 'selected' : '') . ">Pending</option>";
                    echo "<option value='shipped' " . ($orderDetails['status'] == 'shipped' ? 'selected' : '') . ">Shipped</option>";
                    echo "<option value='delivered' " . ($orderDetails['status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>";
                    echo "</select>";
                    echo "<button type='submit' class='btn btn-primary'>Update Status</button>";
                    echo "</form>";
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
