<?php
session_start();

include('config.php');

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_cart"])) {
    // Get quantities from the form submission
    $quantities = $_POST["quantity"];

    foreach ($quantities as $giftId => $quantity) {
        // Validate and sanitize the input
        $giftId = intval($giftId);
        $quantity = intval($quantity);

        // Update the database with the new quantity
        $updateQuery = "UPDATE gifts SET quantity = quantity - ? WHERE gift_id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ii", $quantity, $giftId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update the session cart with the new quantity
        $_SESSION["cart"][$giftId]["quantity"] = $quantity;
    }

    header("Location: view_cart.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
