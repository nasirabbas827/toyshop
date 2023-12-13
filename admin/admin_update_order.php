<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the necessary data is provided
    if (isset($_POST['order_id']) && isset($_POST['status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        // Update order status in the database
        $updateQuery = "UPDATE orders SET status = ? WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Order status updated successfully.";
        } else {
            echo "Error updating order status: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Invalid data provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
