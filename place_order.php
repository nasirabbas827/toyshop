<?php
// place_order.php

session_start();

include('config.php');

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Initialize subtotal
$subtotal = 0;

foreach ($_SESSION["cart"] as $giftId => $cartItem) {
    $quantity = $cartItem["quantity"];
    
    // Fetch the gift details from the database
    $giftQuery = "SELECT price FROM gifts WHERE gift_id = ?";
    $stmt = mysqli_prepare($conn, $giftQuery);
    mysqli_stmt_bind_param($stmt, "i", $giftId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $giftPrice);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Calculate the total price for each item and add it to the subtotal
    $subtotal += $giftPrice * $quantity;
}

// Insert the order along with the subtotal and set the order status as "pending"
$orderDate = date("Y-m-d H:i:s");
$orderStatus = "pending";  // Set the order status here
$insertOrderQuery = "INSERT INTO orders (user_id, order_date, subtotal, status) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insertOrderQuery);
mysqli_stmt_bind_param($stmt, "isds", $user_id, $orderDate, $subtotal, $orderStatus);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Insert order items into the 'order_items' table
foreach ($_SESSION["cart"] as $giftId => $cartItem) {
    $quantity = $cartItem["quantity"];
    $insertOrderItemQuery = "INSERT INTO order_items (order_id, gift_id, quantity) VALUES (LAST_INSERT_ID(), ?, ?)";
    $stmt = mysqli_prepare($conn, $insertOrderItemQuery);
    mysqli_stmt_bind_param($stmt, "ii", $giftId, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Clear the shopping cart after placing the order
unset($_SESSION["cart"]);

// Redirect the user to a confirmation page or any other page
header("location: order_confirmation.php");
exit;
?>
