<?php
session_start();

include('config.php');

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

$sql = "SELECT id, username, email, age FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $username, $email, $age);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["gift_id"])) {
    $gift_id = $_GET["gift_id"];

    // Remove the selected gift from the cart
    if (isset($_SESSION["cart"][$gift_id])) {
        unset($_SESSION["cart"][$gift_id]);
    }

    header("Location: view_cart.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
