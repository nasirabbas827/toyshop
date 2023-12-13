<?php
include('config.php');

session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gift_id = $_POST["gift_id"];
    $user_id = $_POST["user_id"];
    $quantity = 1;

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    if (isset($_SESSION["cart"][$gift_id])) {
        $_SESSION["cart"][$gift_id]["quantity"] += $quantity;
    } else {
        $_SESSION["cart"][$gift_id] = array(
            "user_id" => $user_id,
            "quantity" => $quantity,
        );
    }

    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit;
}
?>
